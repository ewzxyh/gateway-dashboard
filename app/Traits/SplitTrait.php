<?php

namespace App\Traits;

use App\Models\SplitPayment;
use App\Models\Solicitacoes;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

trait SplitTrait
{
    /**
     * Processa splits para uma transação
     */
    public static function processSplits(Solicitacoes $solicitacao, User $user): array
    {
        try {
            Log::info('=== INÍCIO DO PROCESSAMENTO DE SPLIT ===', [
                'solicitacao_id' => $solicitacao->id,
                'idTransaction' => $solicitacao->idTransaction,
                'valor_bruto' => $solicitacao->amount,
                'valor_liquido' => $solicitacao->deposito_liquido,
                'split_email' => $solicitacao->split_email,
                'split_percentage' => $solicitacao->split_percentage,
                'user_id_original' => $user->user_id
            ]);
            
            // Verificar se já existem splits processados para esta transação
            $existingSplits = SplitPayment::where('solicitacao_id', $solicitacao->id)
                ->whereIn('split_status', [SplitPayment::STATUS_COMPLETED, SplitPayment::STATUS_PROCESSING])
                ->count();
            
            if ($existingSplits > 0) {
                Log::info('[SPLIT] Splits já processados para esta transação', [
                    'solicitacao_id' => $solicitacao->id,
                    'existing_splits' => $existingSplits
                ]);
                return [['status' => 'skipped', 'message' => 'Splits já processados para esta transação']];
            }
            
            $splits = [];
            
            // Verificar se há splits configurados na transação
            if ($solicitacao->split_email && $solicitacao->split_percentage) {
                Log::info('[SPLIT] Criando split da transação', [
                    'split_email' => $solicitacao->split_email,
                    'split_percentage' => $solicitacao->split_percentage,
                    'valor_bruto' => $solicitacao->amount,
                    'valor_liquido' => $solicitacao->deposito_liquido
                ]);
                $splits[] = self::createSplitFromSolicitacao($solicitacao, $user);
            }
            
            // Verificar se o usuário tem splits automáticos configurados
            $userSplits = self::getUserSplits($user);
            foreach ($userSplits as $splitConfig) {
                $splits[] = self::createSplitFromConfig($solicitacao, $user, $splitConfig);
            }
            
            // Processar todos os splits
            Log::info('[SPLIT] Iniciando processamento de splits', [
                'total_splits' => count($splits)
            ]);
            
            $results = [];
            foreach ($splits as $split) {
                Log::info('[SPLIT] Executando split individual', [
                    'split_id' => $split->id,
                    'split_amount' => $split->split_amount,
                    'split_email' => $split->split_email
                ]);
                $result = self::executeSplit($split, $solicitacao);
                $results[] = $result;
            }
            
            Log::info('[SPLIT] Splits processados com sucesso', [
                'solicitacao_id' => $solicitacao->id,
                'user_id' => $user->user_id,
                'splits_count' => count($splits),
                'results' => $results
            ]);
            
            Log::info('=== FIM DO PROCESSAMENTO DE SPLIT ===', [
                'solicitacao_id' => $solicitacao->id,
                'status' => 'concluido'
            ]);
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('[SPLIT] Erro ao processar splits', [
                'solicitacao_id' => $solicitacao->id,
                'user_id' => $user->user_id,
                'error' => $e->getMessage()
            ]);
            
            return [];
        }
    }
    
    /**
     * Cria split baseado nos dados da solicitação
     */
    private static function createSplitFromSolicitacao(Solicitacoes $solicitacao, User $user): SplitPayment
    {
        // Calcular split sobre o valor líquido, não o valor bruto
        $splitAmount = ($solicitacao->deposito_liquido * $solicitacao->split_percentage) / 100;
        
        Log::info('[SPLIT] Calculando valor do split', [
            'valor_liquido' => $solicitacao->deposito_liquido,
            'split_percentage' => $solicitacao->split_percentage,
            'split_amount_calculado' => $splitAmount,
            'formula' => "({$solicitacao->deposito_liquido} * {$solicitacao->split_percentage}) / 100"
        ]);
        
        $split = SplitPayment::create([
            'solicitacao_id' => $solicitacao->id,
            'user_id' => $user->user_id,
            'split_email' => $solicitacao->split_email,
            'split_percentage' => $solicitacao->split_percentage,
            'split_amount' => $splitAmount,
            'split_status' => SplitPayment::STATUS_PENDING,
            'split_type' => SplitPayment::TYPE_PERCENTAGE,
            'description' => "Split de {$solicitacao->split_percentage}% para {$solicitacao->split_email}"
        ]);
        
        Log::info('[SPLIT] Split criado no banco de dados', [
            'split_id' => $split->id,
            'split_amount' => $split->split_amount,
            'split_email' => $split->split_email
        ]);
        
        return $split;
    }
    
    /**
     * Cria split baseado na configuração do usuário
     */
    private static function createSplitFromConfig(Solicitacoes $solicitacao, User $user, array $config): SplitPayment
    {
        // Calcular split sobre o valor líquido, não o valor bruto
        $splitAmount = ($solicitacao->deposito_liquido * $config['percentage']) / 100;
        
        return SplitPayment::create([
            'solicitacao_id' => $solicitacao->id,
            'user_id' => $user->user_id,
            'split_email' => $config['email'],
            'split_percentage' => $config['percentage'],
            'split_amount' => $splitAmount,
            'split_status' => SplitPayment::STATUS_PENDING,
            'split_type' => $config['type'] ?? SplitPayment::TYPE_PERCENTAGE,
            'description' => $config['description'] ?? "Split automático de {$config['percentage']}%"
        ]);
    }
    
    /**
     * Obtém splits configurados para o usuário
     */
    private static function getUserSplits(User $user): array
    {
        // Aqui você pode implementar lógica para buscar splits automáticos
        // Por exemplo, de uma tabela de configurações de split por usuário
        // Por enquanto, retornamos array vazio
        return [];
    }
    
    /**
     * Executa um split específico
     */
    private static function executeSplit(SplitPayment $split, Solicitacoes $solicitacao): array
    {
        try {
            $split->markAsProcessing();
            
            // Verificar se o valor do split é válido
            if ($split->split_amount <= 0) {
                $split->markAsFailed('Valor do split inválido');
                return ['status' => 'failed', 'message' => 'Valor do split inválido'];
            }
            
            // Verificar se há saldo suficiente
            if ($solicitacao->deposito_liquido < $split->split_amount) {
                $split->markAsFailed('Saldo insuficiente para split');
                return ['status' => 'failed', 'message' => 'Saldo insuficiente'];
            }
            
            // Buscar usuário destinatário do split
            $splitUser = User::where('email', $split->split_email)->first();
            
            if (!$splitUser) {
                // Se o usuário não existe, criar um registro pendente
                $split->markAsFailed('Usuário destinatário não encontrado');
                return ['status' => 'failed', 'message' => 'Usuário não encontrado'];
            }
            
            // Executar o split
            $result = self::transferSplitAmount($split, $splitUser, $solicitacao);
            
            if ($result['success']) {
                $split->markAsCompleted();
                return ['status' => 'completed', 'message' => 'Split executado com sucesso'];
            } else {
                $split->markAsFailed($result['message']);
                return ['status' => 'failed', 'message' => $result['message']];
            }
            
        } catch (\Exception $e) {
            $split->markAsFailed($e->getMessage());
            Log::error('[SPLIT] Erro ao executar split', [
                'split_id' => $split->id,
                'error' => $e->getMessage()
            ]);
            
            return ['status' => 'failed', 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Transfere o valor do split para o usuário destinatário
     */
    private static function transferSplitAmount(SplitPayment $split, User $splitUser, Solicitacoes $solicitacao): array
    {
        try {
            Log::info('[SPLIT] Iniciando transferência de valores', [
                'split_id' => $split->id,
                'split_amount' => $split->split_amount,
                'from_user' => $solicitacao->user_id,
                'to_user' => $splitUser->user_id
            ]);
            
            // Buscar usuário original
            $originalUser = User::where('user_id', $solicitacao->user_id)->first();
            
            Log::info('[SPLIT] Saldos ANTES da transferência', [
                'usuario_original' => $originalUser ? $originalUser->saldo : 'N/A',
                'usuario_split' => $splitUser->saldo
            ]);
            
            // Creditar o valor para o usuário destinatário
            Helper::incrementAmount($splitUser, $split->split_amount, 'saldo');
            Helper::calculaSaldoLiquido($splitUser->user_id);
            
            Log::info('[SPLIT] Valor creditado para usuário de split', [
                'user_id' => $splitUser->user_id,
                'amount' => $split->split_amount,
                'novo_saldo' => $splitUser->fresh()->saldo
            ]);
            
            // Debitar o valor do usuário original
            if ($originalUser) {
                Helper::decrementAmount($originalUser, $split->split_amount, 'saldo');
                Helper::calculaSaldoLiquido($originalUser->user_id);
                
                Log::info('[SPLIT] Valor debitado do usuário original', [
                    'user_id' => $originalUser->user_id,
                    'amount' => $split->split_amount,
                    'novo_saldo' => $originalUser->fresh()->saldo
                ]);
            }
            
            // Log da transação de split (sem criar registro na tabela transactions por enquanto)
            Log::info('[SPLIT] Transação de split registrada', [
                'split_id' => $split->id,
                'user_id' => $splitUser->user_id,
                'amount' => $split->split_amount,
                'reference' => "split_{$split->id}"
            ]);
            
            Log::info('[SPLIT] Saldos APÓS a transferência', [
                'usuario_original' => $originalUser ? $originalUser->fresh()->saldo : 'N/A',
                'usuario_split' => $splitUser->fresh()->saldo
            ]);
            
            Log::info('[SPLIT] Split transferido com sucesso', [
                'split_id' => $split->id,
                'from_user' => $solicitacao->user_id,
                'to_user' => $splitUser->user_id,
                'amount' => $split->split_amount
            ]);
            
            return ['success' => true, 'message' => 'Split transferido com sucesso'];
            
        } catch (\Exception $e) {
            Log::error('[SPLIT] Erro ao transferir split', [
                'split_id' => $split->id,
                'error' => $e->getMessage()
            ]);
            
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Obtém estatísticas de splits
     */
    public static function getSplitStats(User $user = null, $startDate = null, $endDate = null): array
    {
        $query = SplitPayment::query();
        
        if ($user) {
            $query->where('user_id', $user->user_id);
        }
        
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        $stats = [
            'total_splits' => $query->count(),
            'pending_splits' => $query->where('split_status', SplitPayment::STATUS_PENDING)->count(),
            'completed_splits' => $query->where('split_status', SplitPayment::STATUS_COMPLETED)->count(),
            'failed_splits' => $query->where('split_status', SplitPayment::STATUS_FAILED)->count(),
            'total_amount' => $query->where('split_status', SplitPayment::STATUS_COMPLETED)->sum('split_amount'),
            'pending_amount' => $query->where('split_status', SplitPayment::STATUS_PENDING)->sum('split_amount'),
        ];
        
        return $stats;
    }
    
    /**
     * Processa splits pendentes em lote
     */
    public static function processPendingSplits(): array
    {
        $pendingSplits = SplitPayment::pending()->with(['solicitacao', 'user'])->get();
        $results = [];
        
        foreach ($pendingSplits as $split) {
            $result = self::executeSplit($split, $split->solicitacao);
            $results[] = [
                'split_id' => $split->id,
                'result' => $result
            ];
        }
        
        Log::info('[SPLIT] Processamento em lote concluído', [
            'processed_count' => count($pendingSplits),
            'results' => $results
        ]);
        
        return $results;
    }
    
    /**
     * Cancela um split
     */
    public static function cancelSplit(SplitPayment $split, string $reason = null): bool
    {
        try {
            if ($split->isCompleted()) {
                return false; // Não pode cancelar split já processado
            }
            
            $split->update([
                'split_status' => SplitPayment::STATUS_CANCELLED,
                'error_message' => $reason ?? 'Split cancelado pelo usuário',
                'processed_at' => now()
            ]);
            
            Log::info('[SPLIT] Split cancelado', [
                'split_id' => $split->id,
                'reason' => $reason
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('[SPLIT] Erro ao cancelar split', [
                'split_id' => $split->id,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}

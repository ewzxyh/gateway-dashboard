<?php

namespace App\Http\Controllers\Admin\Financeiro;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Solicitacoes;
use Carbon\Carbon;

class EntradasController extends Controller
{
    public function index(Request $request)
    {
        $dataHoje = Carbon::today()->toDateString();
        $mesAtual = Carbon::now()->format('Y-m');

        $totalaprovadasHoje = $this->contarTransacoes('PAID_OUT', $dataHoje);
        $totalaprovadasMes = $this->contarTransacoes('PAID_OUT', null, $mesAtual);
        $totalaprovadas = $this->contarTransacoes('PAID_OUT');
        $totalaprovadas = $this->contarTransacoes();

        $valorAprovadoHoje = $this->somarValores('amount', 'PAID_OUT', $dataHoje);
        $valorAprovadoMes = $this->somarValores('amount', 'PAID_OUT', null, $mesAtual);
        $valorAprovadoTotal = $this->somarValores('amount', 'PAID_OUT');

        $valorSaqueAprovadoHoje = $this->somarValores('deposito_liquido', 'PAID_OUT', $dataHoje);
        $valorSaqueAprovadoMes = $this->somarValores('deposito_liquido', 'PAID_OUT', null, $mesAtual);
        $valorSaqueAprovadoTotal = $this->somarValores('deposito_liquido', 'PAID_OUT');

        $totalsolicitacoes = Solicitacoes::count();

        $limit = PHP_INT_MAX; // Número de registros por página
        $page = $request->input('page', 1); // Página atual
        $offset = ($page - 1) * $limit;

        // Filtros de data
        $dataInicio = $request->input('data_inicio', '');
        $dataFim = $request->input('data_fim', '');

        // Query para obter a soma filtrada com status PAID_OUT
        $query = Solicitacoes::where('status', '!=', '');

        if (!empty($dataInicio) && !empty($dataFim)) {
            $query->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalResults = $query->selectRaw('SUM(deposito_liquido) AS total_deposito_liquido_filtrado, SUM(amount) AS total_deposito_bruto_filtrada')->first();

        $total_deposito_liquido_filtrado = $totalResults->total_deposito_liquido_filtrado ?: 0;
        $total_deposito_bruto_filtrada = $totalResults->total_deposito_bruto_filtrada ?: 0;

        $lucro_plataforma_filtrada = $total_deposito_bruto_filtrada - $total_deposito_liquido_filtrado;

        // Consulta para obter o número total de registros
        $totalRecords = Solicitacoes::whereIn('status', ['RELEASE','PAID_OUT', 'CANCELED', 'WAITING_FOR_APPROVAL']);

        if (!empty($dataInicio) && !empty($dataFim)) {
            $totalRecords->whereBetween('date', [$dataInicio, $dataFim]);
        }

        $totalRecords = $totalRecords->count();
        $totalPages = ceil($totalRecords / $limit);

        // Consulta para obter os registros com paginação e filtro de data
        $cashOuts = Solicitacoes::whereIn('status', ['RELEASE','PAID_OUT', 'CANCELED', 'WAITING_FOR_APPROVAL'])
            ->when(!empty($dataInicio) && !empty($dataFim), function ($query) use ($dataInicio, $dataFim) {
                return $query->whereBetween('date', [$dataInicio, $dataFim]);
            })
            ->orderBy('date', 'desc')
            ->paginate($limit);

        // Retornar a view com os dados
        return view('admin.financeiro.entradas', compact(
            'totalaprovadasHoje',
            'totalaprovadasMes',
            'totalaprovadas',
            'totalaprovadas',
            'valorAprovadoHoje',
            'valorAprovadoMes',
            'valorAprovadoTotal',
            'valorSaqueAprovadoHoje',
            'valorSaqueAprovadoMes',
            'valorSaqueAprovadoTotal',
            'totalsolicitacoes',
            'cashOuts',
            'total_deposito_liquido_filtrado',
            'total_deposito_bruto_filtrada',
            'lucro_plataforma_filtrada',
            'totalPages',
            'page',
            'limit',
            'dataInicio',
            'dataFim'
        ));
    }

    private function contarTransacoes($status = null, $data = null, $mes = null)
    {
        return DB::table('solicitacoes')
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($data, fn($query) => $query->whereDate('date', $data))
            ->when($mes, fn($query) => $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mes]))
            ->count();
    }

    private function somarValores($campo, $status = null, $data = null, $mes = null)
    {
        return DB::table('solicitacoes')
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($data, fn($query) => $query->whereDate('date', $data))
            ->when($mes, fn($query) => $query->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$mes]))
            ->sum($campo) ?? 0;
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:PAID_OUT,WAITING_FOR_APPROVAL,RELEASE,CANCELLED'
        ]);

        $solicitacao = Solicitacoes::findOrFail($id);
        $solicitacao->status = $request->status;
        $solicitacao->save();

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!'
        ]);
    }

    public function getTransactionDetails($id)
    {
        try {
            $solicitacao = Solicitacoes::with('user')->findOrFail($id);
            
            // Buscar dados do usuário
            $user = $solicitacao->user;
            $clientName = $user ? $user->name : 'N/A';
            
            // Preparar dados da transação no formato do modal
            $transaction = [
                'id' => $solicitacao->id,
                'idTransaction' => $solicitacao->idTransaction,
                'amount' => $solicitacao->amount,
                'deposito_liquido' => $solicitacao->deposito_liquido,
                'method' => $solicitacao->method,
                'status' => $solicitacao->status,
                'date' => $solicitacao->date,
                'client_name' => $clientName,
                'user_id' => $solicitacao->user_id,
                'taxa' => $solicitacao->amount - $solicitacao->deposito_liquido,
                'card_number' => $solicitacao->card_number ?? '**** **** **** ****',
                'card_expiry' => $solicitacao->card_expiry ?? '12/25',
                'card_brand' => $solicitacao->card_brand ?? 'VISA',
                'pix_key' => $solicitacao->pix_key ?? null,
                'billet_url' => $solicitacao->billet_url ?? null,
                'empresa' => 'HKPAY',
                'assinatura_status' => 'ATIVA',
                'assinatura_data' => $solicitacao->date
            ];

            return response()->json([
                'success' => true,
                'transaction' => $transaction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar detalhes da transação: ' . $e->getMessage()
            ], 500);
        }
    }

    public function enviarMediacao($id)
    {
        try {
            // Validar ID
            if (!is_numeric($id) || $id <= 0) {
                return response()->json(['success' => false, 'message' => 'ID inválido'], 400);
            }
            
            \Log::info('Tentativa de envio para mediação', ['id' => $id]);
            
            $solicitacao = Solicitacoes::findOrFail((int)$id);
            \Log::info('Solicitação encontrada', ['status' => $solicitacao->status]);
            
            // Verificar se a transação pode ser enviada para mediação
            if ($solicitacao->status !== 'PAID_OUT') {
                \Log::warning('Tentativa de mediação em transação não aprovada - Status: ' . $solicitacao->status);
                return response()->json(['success' => false, 'message' => 'Apenas transações aprovadas podem ser enviadas para mediação'], 400);
            }
            
            // Atualizar status para mediação
            $solicitacao->update([
                'status' => 'MEDIATION',
                'updated_at' => now()
            ]);
            
            // Buscar usuário para bloquear o valor
            $user = $solicitacao->user;
            if ($user) {
                // Bloquear o valor (remover do saldo disponível)
                $user->decrement('saldo', $solicitacao->deposito_liquido);
                
                // Adicionar ao saldo bloqueado (se existir campo) ou criar log
                // Aqui você pode implementar a lógica específica para bloquear o valor
                // Por exemplo, criar um registro em uma tabela de valores bloqueados
            }
            
            \Log::info('Mediação executada com sucesso', ['id' => $id]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Transação enviada para mediação com sucesso. O valor foi bloqueado e ficará pendente para liberação manual.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar transação para mediação: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Erro ao enviar transação para mediação: ' . $e->getMessage()], 500);
        }
    }
}

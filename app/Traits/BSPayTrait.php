<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Solicitacoes;
use App\Models\SolicitacoesCashOut;
use App\Models\App;
use App\Models\User;
use App\Helpers\Helper;
use App\Services\BSPayService;
use App\Traits\SplitTrait;
use App\Traits\IPManagementTrait;
use App\Helpers\TaxaFlexivelHelper;

trait BSPayTrait
{
    /**
     * Verifica se o IP está autorizado para operações de saque
     */
    public static function checkIPForWithdraw(User $user): array
    {
        $clientIP = IPManagementTrait::getClientIP();
        
        if (!IPManagementTrait::isIPAllowed($clientIP, $user)) {
            return [
                'success' => false,
                'message' => 'IP não autorizado para realizar saques',
                'client_ip' => $clientIP
            ];
        }
        
        return [
            'success' => true,
            'client_ip' => $clientIP
        ];
    }

    /**
     * Processa depósito via PIX (Cash-in)
     */
    public static function requestDepositBSPay($data)
    {
        try {
            $bspay = new BSPayService();
            
            // Gera external_id único para a transação
            $externalId = Str::uuid()->toString();
            
            $qrCodeData = [
                'amount' => $data->amount,
                'external_id' => $externalId,
                'postback_url' => env('APP_URL') . '/callback',
                'description' => 'Depósito via PIX - ' . env('APP_NAME'),
                'debtor_name' => $data->debtor_name ?? 'Cliente',
                'debtor_document_number' => $data->debtor_document_number ?? '00000000000',
                'email' => $data->email ?? 'redacted@example.invalid',
                'phone' => $data->phone ?? '11999999999'
            ];

            $response = $bspay->generateQrCode($qrCodeData);

            if (!$response || !isset($response['transactionId'])) {
                return [
                    'status' => 500,
                    'data' => ['message' => 'Erro ao gerar QR Code PIX']
                ];
            }

            $setting = App::first();
            $user = $data->user;

            // Calcula taxas usando apenas configurações globais
            $taxaCalculada = TaxaFlexivelHelper::calcularTaxaDeposito($data->amount, $setting, $user);
            $deposito_liquido = $taxaCalculada['deposito_liquido'];
            $taxa_cash_in = $taxaCalculada['taxa_cash_in'];
            $descricao = $taxaCalculada['descricao'];

            $date = Carbon::now();

            $cashin = [
                "user_id" => $data->user->username,
                "externalreference" => $externalId,
                "amount" => $data->amount,
                "client_name" => $data->debtor_name,
                "client_document" => $data->debtor_document_number,
                "client_email" => $data->email,
                "date" => $date,
                "status" => 'WAITING_FOR_APPROVAL',
                "idTransaction" => $externalId,
                "deposito_liquido" => $deposito_liquido,
                "qrcode_pix" => $response['qrcode'],
                "paymentcode" => $response['qrcode'],
                "paymentCodeBase64" => $response['qrcode'],
                "adquirente_ref" => 'bspay',
                "taxa_cash_in" => $taxa_cash_in,
                "taxa_pix_cash_in_adquirente" => 0,
                "taxa_pix_cash_in_valor_fixo" => 0,
                "client_telefone" => $data->phone,
                "executor_ordem" => 'bspay',
                "descricao_transacao" => $descricao,
                "callback" => $data->postback,
                "split_email" => $data->split_email ?? null,
                "split_percentage" => $data->split_percentage ?? null,
            ];

            Solicitacoes::create($cashin);

            // UTMfy integration
            if (!is_null($user->integracao_utmfy)) {
                $ip = $data->header('X-Forwarded-For') ?
                    $data->header('X-Forwarded-For') : ($data->header('CF-Connecting-IP') ?
                        $data->header('CF-Connecting-IP') :
                        $data->ip());

                $msg = "PIX Gerado " . env('APP_NAME');
                UtmfyTrait::gerarUTM('pix', 'waiting_payment', $cashin, $user->integracao_utmfy, $ip, $msg);
            }

            return [
                'status' => 200,
                'data' => [
                    'idTransaction' => $externalId,
                    'qrcode' => $response['qrcode'],
                    'qr_code_image_url' => $response['qr_code_image_url'] ?? 'https://quickchart.io/qr?text=' . urlencode($response['qrcode']),
                    // Estrutura compatível com o frontend
                    'charge' => [
                        'id' => $externalId,
                        'qrCode' => $response['qr_code_image_url'] ?? 'https://quickchart.io/qr?text=' . urlencode($response['qrcode']),
                        'brCode' => $response['qrcode']
                    ]
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Erro no BSPayTrait::requestDepositBSPay: ' . $e->getMessage());
            return [
                'status' => 500,
                'data' => ['message' => 'Erro interno do servidor']
            ];
        }
    }

    /**
     * Processa saque via PIX (Cash-out)
     */
    public static function requestPaymentBSPay($request)
    {
        try {
            $data = $request->all();
            $user = User::where('id', $request->user->id)->first();
            $setting = App::first();

            Log::info('=== BSPAYTRAIT REQUEST PAYMENT INICIADO ===');
            Log::info('BSPayTrait::requestPaymentBSPay - Dados da requisição:', [
                'user_id' => $user->id,
                'username' => $user->username,
                'amount' => $request->amount,
                'pix_key' => $request->pixKey,
                'pix_key_type' => $request->pixKeyType,
                'baasPostbackUrl' => $request->baasPostbackUrl,
                'is_interface_web' => $request->input('baasPostbackUrl') === 'web'
            ]);

            // Determinar se é saque via interface web ou API
            $isInterfaceWeb = $request->input('baasPostbackUrl') === 'web';

            // Verificar se deve usar taxa por fora para saques via API
            $taxaPorFora = $setting->taxa_por_fora_api ?? true;

            // Calcula taxas de saque usando o helper centralizado
            $taxaCalculada = \App\Helpers\TaxaSaqueHelper::calcularTaxaSaque((float)$request->amount, $setting, $user, $isInterfaceWeb, $taxaPorFora);
            $cashout_liquido = $taxaCalculada['saque_liquido'];
            $taxa_cash_out = $taxaCalculada['taxa_cash_out'];
            $descricao = $taxaCalculada['descricao'];
            $valor_total_descontar = $taxaCalculada['valor_total_descontar'] ?? $request->amount;

            Log::info('BSPayTrait::requestPaymentBSPay - Cálculo de taxas:', [
                'amount_original' => $request->amount,
                'taxa_cash_out' => $taxa_cash_out,
                'cashout_liquido' => $cashout_liquido,
                'descricao' => $descricao,
                'user_saldo' => $user->saldo,
                'is_interface_web' => $isInterfaceWeb
            ]);

            // Verificar saldo considerando taxa por fora
            $saldo_necessario = $valor_total_descontar; // Sempre usar valor total a descontar
            if ($user->saldo < $saldo_necessario) {
                // Calcular valor máximo que pode ser sacado
                $valorMaximo = \App\Helpers\TaxaSaqueHelper::calcularValorMaximoSaque($user->saldo, $setting, $user, $isInterfaceWeb);
                
                Log::warning('BSPayTrait::requestPaymentBSPay - Saldo insuficiente:', [
                    'user_saldo' => $user->saldo,
                    'valor_solicitado' => $request->amount,
                    'valor_total_descontar' => $saldo_necessario,
                    'valor_maximo_saque' => $valorMaximo['valor_maximo'],
                    'taxa_total' => $valorMaximo['taxa_total']
                ]);
                
                return [
                    'status' => 401,
                    'data' => [
                        'message' => "Saldo insuficiente para realizar a operação. Considere o valor + a taxa de saque.",
                        'valor_solicitado' => $request->amount,
                        'taxa_total' => $taxa_cash_out,
                        'valor_total_necessario' => $saldo_necessario,
                        'saldo_disponivel' => $user->saldo,
                        'deficit' => $saldo_necessario - $user->saldo,
                        'valor_maximo_saque' => $valorMaximo['valor_maximo'],
                        'saldo_restante' => $valorMaximo['saldo_restante']
                    ]
                ];
            }

            $date = Carbon::now();

            // Se for web, verificar se é saque automático
            if ($request->baasPostbackUrl === 'web') {
                Log::info('BSPayTrait::requestPaymentBSPay - Interface web detectada:', [
                    'saque_automatico' => $request->has('saque_automatico') ? $request->saque_automatico : false,
                    'has_saque_automatico' => $request->has('saque_automatico')
                ]);
                
                if ($request->has('saque_automatico') && $request->saque_automatico) {
                    Log::info('BSPayTrait::requestPaymentBSPay - Processando saque automático');
                    // Processar saque automático diretamente via API
                    return self::processarSaqueAutomatico($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
                } else {
                    Log::info('BSPayTrait::requestPaymentBSPay - Processando saque manual');
                    // Processar como manual (criar solicitação para aprovação)
                    return self::generateTransactionPaymentManual($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
                }
            }

            Log::info('BSPayTrait::requestPaymentBSPay - Processando via API (não web)');
            
            $bspay = new BSPayService();
            $externalId = Str::uuid()->toString();

            // Prepara dados do PIX
            $pixKey = $request->pixKey;
            switch ($request->pixKeyType) {
                case 'cpf':
                case 'cnpj':
                case 'telefone':
                case 'phone':
                    $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                    break;
            }

            // Mapear tipos de chave PIX para o formato da API BSPay
            $pixKeyTypeMapping = [
                'cpf' => 'CPF',
                'cnpj' => 'CNPJ', 
                'email' => 'EMAIL',
                'telefone' => 'PHONE',
                'phone' => 'PHONE',
                'aleatoria' => 'RANDOM',
                'random' => 'RANDOM',
                'crypto' => 'CRYPTO'
            ];
            
            $pixKeyType = $pixKeyTypeMapping[strtolower($request->pixKeyType)] ?? strtoupper($request->pixKeyType);

            Log::info('BSPayTrait::requestPaymentBSPay - Dados PIX processados:', [
                'pix_key_original' => $request->pixKey,
                'pix_key_processed' => $pixKey,
                'pix_key_type_original' => $request->pixKeyType,
                'pix_key_type_mapped' => $pixKeyType,
                'external_id' => $externalId
            ]);

            $paymentData = [
                'amount' => $request->amount,
                'external_id' => $externalId,
                'postback_url' => env('APP_URL') . '/callback',
                'pix_key' => $pixKey,
                'pix_key_type' => $pixKeyType,
                'beneficiary_name' => $request->user->name,
                'beneficiary_document' => $pixKey
            ];

            Log::info('BSPayTrait::requestPaymentBSPay - Dados enviados para BSPayService:', $paymentData);

            $response = $bspay->makePayment($paymentData);

            Log::info('BSPayTrait::requestPaymentBSPay - Resposta do BSPayService:', [
                'response' => $response,
                'has_transaction_id' => isset($response['transactionId']),
                'is_error' => isset($response['error'])
            ]);

            if (!$response || !isset($response['transactionId'])) {
                Log::error('BSPayTrait::requestPaymentBSPay - Erro na resposta do BSPayService:', [
                    'response' => $response,
                    'external_id' => $externalId
                ]);
                return [
                    'status' => 500,
                    'data' => [
                        'message' => 'Erro ao processar pagamento via BSPay',
                        'bspay_error' => true,
                        'details' => $response['details'] ?? null,
                        'bspay_raw_response' => $response['raw_response'] ?? null
                    ]
                ];
            }

            // Criar registro de saque
            $cashout = [
                "user_id" => $request->user->username,
                "externalreference" => $externalId,
                "amount" => $request->amount,
                "cash_out_liquido" => $cashout_liquido,
                "taxa_cash_out" => $taxa_cash_out,
                "pix" => $pixKey,
                "pixkey" => $pixKeyType,
                "beneficiaryname" => $request->user->name,
                "beneficiarydocument" => $pixKey,
                "date" => $date,
                "status" => 'PENDING',
                "idTransaction" => $externalId,
                "end_to_end" => $externalId,
                "descricao_transacao" => $descricao,
                "executor_ordem" => 'bspay',
                "type" => "PIX",
                "callback" => $request->baasPostbackUrl === 'web' ? env('APP_URL') . '/callback' : $request->baasPostbackUrl
            ];

            Log::info('BSPayTrait::requestPaymentBSPay - Criando registro de saque:', $cashout);
            $solicitacao = SolicitacoesCashOut::create($cashout);
            Log::info('BSPayTrait::requestPaymentBSPay - Registro de saque criado com sucesso');

            // Debitar saldo do usuário imediatamente
            $user = User::where('id', $request->user->id)->first();
            if ($user) {
                // Para taxa por fora, descontar valor + taxa do saldo
                $taxaPorFora = \App\Models\App::first()->taxa_por_fora_api ?? true;
                $valor_para_descontar = $taxaPorFora ? ($request->amount + $taxa_cash_out) : $request->amount;
                
                Log::info('BSPayTrait::requestPaymentBSPay - Descontando saldo:', [
                    'user_id' => $user->user_id,
                    'saldo_antes' => $user->saldo,
                    'valor_para_descontar' => $valor_para_descontar,
                    'taxa_por_fora' => $taxaPorFora
                ]);
                
                \App\Helpers\Helper::decrementAmount($user, $valor_para_descontar, 'saldo');
                $user->increment('valor_sacado', $request->amount);
                
                // Log específico para saque
                \App\Helpers\BalanceLogHelper::logSaqueOperation(
                    'SAQUE_REQUEST',
                    $user,
                    $request->amount,
                    [
                        'adquirente' => 'BSPAY',
                        'valor_bruto' => $request->amount,
                        'valor_descontado' => $valor_para_descontar,
                        'taxa_cash_out' => $taxa_cash_out,
                        'taxa_por_fora' => $taxaPorFora,
                        'external_id' => $externalId,
                        'operacao' => 'requestPaymentBSPay'
                    ]
                );
                
                Log::info('BSPayTrait::requestPaymentBSPay - Saldo atualizado:', [
                    'user_id' => $user->user_id,
                    'saldo_depois' => $user->fresh()->saldo,
                    'valor_sacado' => $user->fresh()->valor_sacado
                ]);
            }

            $responseData = [
                'status' => 200,
                'data' => [
                    'id' => $externalId,
                    'amount' => $request->amount,
                    'pixKey' => $pixKey,
                    'pixKeyType' => $pixKeyType,
                    'withdrawStatusId' => 'PendingProcessing',
                    'createdAt' => $date->toISOString(),
                    'updatedAt' => $date->toISOString()
                ]
            ];

            Log::info('BSPayTrait::requestPaymentBSPay - Resposta final:', $responseData);
            Log::info('=== FIM BSPAYTRAIT REQUEST PAYMENT ===');

            return $responseData;

        } catch (\Exception $e) {
            Log::error('BSPayTrait::requestPaymentBSPay - Exceção capturada:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'status' => 500,
                'data' => ['message' => 'Erro interno do servidor']
            ];
        }
    }

    /**
     * Processa saque automático via BSPay
     */
    private static function processarSaqueAutomatico($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        try {
            Log::info('=== BSPAYTRAIT PROCESSAR SAQUE AUTOMÁTICO INICIADO ===');
            Log::info('BSPayTrait::processarSaqueAutomatico - Dados recebidos:', [
                'user_id' => $user->id,
                'username' => $user->username,
                'amount' => $request->amount,
                'taxa_cash_out' => $taxa_cash_out,
                'cashout_liquido' => $cashout_liquido,
                'descricao' => $descricao
            ]);

            $bspay = new BSPayService();
            $externalId = Str::uuid()->toString();

            // Prepara dados do PIX
            $pixKey = $request->pixKey;
            switch ($request->pixKeyType) {
                case 'cpf':
                case 'cnpj':
                case 'telefone':
                case 'phone':
                    $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                    break;
            }

            // Mapear tipos de chave PIX
            $pixKeyTypeMapping = [
                'cpf' => 'CPF',
                'cnpj' => 'CNPJ', 
                'email' => 'EMAIL',
                'telefone' => 'PHONE',
                'phone' => 'PHONE',
                'aleatoria' => 'RANDOM',
                'random' => 'RANDOM',
                'crypto' => 'CRYPTO'
            ];
            
            $pixKeyType = $pixKeyTypeMapping[strtolower($request->pixKeyType)] ?? strtoupper($request->pixKeyType);

            Log::info('BSPayTrait::processarSaqueAutomatico - Dados PIX processados:', [
                'pix_key_original' => $request->pixKey,
                'pix_key_processed' => $pixKey,
                'pix_key_type_original' => $request->pixKeyType,
                'pix_key_type_mapped' => $pixKeyType,
                'external_id' => $externalId
            ]);

            $paymentData = [
                'amount' => $request->amount,
                'external_id' => $externalId,
                'postback_url' => env('APP_URL') . '/callback',
                'pix_key' => $pixKey,
                'pix_key_type' => $pixKeyType,
                'beneficiary_name' => $request->user->name,
                'beneficiary_document' => $pixKey
            ];

            Log::info('BSPayTrait::processarSaqueAutomatico - Dados enviados para BSPayService:', $paymentData);

            $response = $bspay->makePayment($paymentData);

            Log::info('BSPayTrait::processarSaqueAutomatico - Resposta do BSPayService:', [
                'response' => $response,
                'has_transaction_id' => isset($response['transactionId']),
                'is_error' => isset($response['error'])
            ]);

            if (!$response || !isset($response['transactionId'])) {
                Log::error('BSPayTrait::processarSaqueAutomatico - Erro na resposta do BSPayService:', [
                    'response' => $response,
                    'external_id' => $externalId
                ]);
                return [
                    'status' => 500,
                    'data' => [
                        'message' => 'Erro ao processar saque automático via BSPay',
                        'bspay_error' => true,
                        'details' => $response['details'] ?? null,
                        'bspay_raw_response' => $response['raw_response'] ?? null
                    ]
                ];
            }

            // Criar registro de saque
            $cashout = [
                "user_id" => $request->user->username,
                "externalreference" => $externalId,
                "amount" => $request->amount,
                "cash_out_liquido" => $cashout_liquido,
                "taxa_cash_out" => $taxa_cash_out,
                "pix" => $pixKey,
                "pixkey" => $pixKeyType,
                "beneficiaryname" => $request->user->name,
                "beneficiarydocument" => $pixKey,
                "date" => $date,
                "status" => 'PENDING',
                "idTransaction" => $externalId,
                "end_to_end" => $externalId,
                "descricao_transacao" => $descricao,
                "executor_ordem" => 'bspay',
                "type" => "PIX",
                "callback" => $request->baasPostbackUrl === 'web' ? env('APP_URL') . '/callback' : $request->baasPostbackUrl
            ];

            Log::info('BSPayTrait::processarSaqueAutomatico - Criando registro de saque:', $cashout);
            $solicitacao = SolicitacoesCashOut::create($cashout);
            Log::info('BSPayTrait::processarSaqueAutomatico - Registro de saque criado com sucesso');

            // Atualizar saldo do usuário (Jhon Martins)
            // Para taxa por fora, descontar valor + taxa do saldo
            $taxaPorFora = \App\Models\App::first()->taxa_por_fora_api ?? true;
            $valor_para_descontar = $taxaPorFora ? ($request->amount + $taxa_cash_out) : $request->amount;
            
            Log::info('=== BSPAYTRAIT::processarSaqueAutomatico - DESCONTO DE SALDO ===', [
                'user_id' => $user->user_id,
                'saldo_antes' => $user->saldo,
                'valor_saque' => $request->amount,
                'taxa_cash_out' => $taxa_cash_out,
                'taxa_por_fora' => $taxaPorFora,
                'valor_para_descontar' => $valor_para_descontar,
                'valor_sacado_antes' => $user->valor_sacado
            ]);
            
            \App\Helpers\Helper::decrementAmount($user, $valor_para_descontar, 'saldo');
            $user->increment('valor_sacado', $request->amount);
            
            Log::info('BSPayTrait::processarSaqueAutomatico - Saldo atualizado:', [
                'user_id' => $user->user_id,
                'saldo_depois' => $user->fresh()->saldo,
                'valor_sacado' => $user->fresh()->valor_sacado
            ]);

            $responseData = [
                'status' => 200,
                'data' => [
                    'id' => $externalId,
                    'amount' => $request->amount,
                    'pixKey' => $pixKey,
                    'pixKeyType' => $pixKeyType,
                    'withdrawStatusId' => 'PendingProcessing',
                    'createdAt' => $date->toISOString(),
                    'updatedAt' => $date->toISOString()
                ]
            ];

            Log::info('BSPayTrait::processarSaqueAutomatico - Resposta final:', $responseData);
            Log::info('=== FIM BSPAYTRAIT PROCESSAR SAQUE AUTOMÁTICO ===');

            return $responseData;

        } catch (\Exception $e) {
            Log::error('BSPayTrait::processarSaqueAutomatico - Exceção capturada:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'status' => 500,
                'data' => ['message' => 'Erro interno do servidor']
            ];
        }
    }

    /**
     * Gera transação manual para aprovação
     */
    private static function generateTransactionPaymentManual($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        try {
            $externalId = Str::uuid()->toString();

            // Prepara dados do PIX
            $pixKey = $request->pixKey;
            switch ($request->pixKeyType) {
                case 'cpf':
                case 'cnpj':
                case 'telefone':
                case 'phone':
                    $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                    break;
            }

            // Mapear tipos de chave PIX
            $pixKeyTypeMapping = [
                'cpf' => 'CPF',
                'cnpj' => 'CNPJ', 
                'email' => 'EMAIL',
                'telefone' => 'PHONE',
                'phone' => 'PHONE',
                'aleatoria' => 'RANDOM',
                'random' => 'RANDOM',
                'crypto' => 'CRYPTO'
            ];
            
            $pixKeyType = $pixKeyTypeMapping[strtolower($request->pixKeyType)] ?? strtoupper($request->pixKeyType);

            // Criar registro de saque manual
            $cashout = [
                "user_id" => $request->user->username,
                "externalreference" => $externalId,
                "amount" => $request->amount,
                "cash_out_liquido" => $cashout_liquido,
                "taxa_cash_out" => $taxa_cash_out,
                "pix" => $pixKey,
                "pixkey" => $pixKeyType,
                "beneficiaryname" => $request->user->name,
                "beneficiarydocument" => $pixKey,
                "date" => $date,
                "status" => 'PENDING_APPROVAL',
                "idTransaction" => $externalId,
                "end_to_end" => $externalId,
                "descricao_transacao" => $descricao,
                "executor_ordem" => 'bspay',
                "type" => "PIX",
                "callback" => $request->baasPostbackUrl === 'web' ? env('APP_URL') . '/callback' : $request->baasPostbackUrl
            ];

            SolicitacoesCashOut::create($cashout);

            return [
                'status' => 200,
                'data' => [
                    'id' => $externalId,
                    'amount' => $request->amount,
                    'pixKey' => $pixKey,
                    'pixKeyType' => $pixKeyType,
                    'withdrawStatusId' => 'PendingApproval',
                    'createdAt' => $date->toISOString(),
                    'updatedAt' => $date->toISOString(),
                    'message' => 'Solicitação de saque criada e aguardando aprovação'
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Erro no BSPayTrait::generateTransactionPaymentManual: ' . $e->getMessage());
            return [
                'status' => 500,
                'data' => ['message' => 'Erro interno do servidor']
            ];
        }
    }

    /**
     * Libera saque manual via BSPay
     */
    public static function liberarSaqueManual($id)
    {
        try {
            Log::info('=== BSPAYTRAIT LIBERAR SAQUE MANUAL INICIADO ===');
            Log::info('BSPayTrait::liberarSaqueManual - ID da solicitação:', ['id' => $id]);

            $cashout = SolicitacoesCashOut::where('id', $id)->with('user')->first();
            
            if (!$cashout) {
                Log::warning('BSPayTrait::liberarSaqueManual - Solicitação não encontrada:', ['id' => $id]);
                return back()->with('error', 'Solicitação de saque não encontrada.');
            }

            Log::info('BSPayTrait::liberarSaqueManual - Solicitação encontrada:', [
                'id' => $cashout->id,
                'user_id' => $cashout->user_id,
                'amount' => $cashout->amount,
                'cash_out_liquido' => $cashout->cash_out_liquido,
                'status' => $cashout->status,
                'type' => $cashout->type,
                'pix' => $cashout->pix,
                'pixkey' => $cashout->pixkey
            ]);

            $bspay = new BSPayService();
            $externalId = Str::uuid()->toString();

            if ($cashout->type == "CRYPTO") {
                Log::info('BSPayTrait::liberarSaqueManual - Processando saque CRYPTO (manual)');
                
                // Para crypto, mantém o comportamento manual
                $pixcashout = [
                    "externalreference" => $externalId,
                    "idTransaction" => $externalId,
                    "end_to_end" => $externalId,
                    "descricao_transacao" => "LIBERADOADMIN"
                ];

                Log::info('BSPayTrait::liberarSaqueManual - Atualizando registro CRYPTO:', $pixcashout);
                $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
                Log::info('BSPayTrait::liberarSaqueManual - Registro CRYPTO atualizado com sucesso');
                Log::info('=== FIM BSPAYTRAIT LIBERAR SAQUE MANUAL (CRYPTO) ===');
                return back()->with('success', 'Pedido de saque enviado com sucesso!');
            }

            // Para PIX, processa via API
            Log::info('BSPayTrait::liberarSaqueManual - Processando saque PIX via API');
            
            $paymentData = [
                'amount' => $cashout->cash_out_liquido,
                'external_id' => $externalId,
                'postback_url' => env('APP_URL') . '/callback',
                'pix_key' => $cashout->pix,
                'pix_key_type' => strtoupper($cashout->pixkey),
                'beneficiary_name' => $cashout->beneficiaryname,
                'beneficiary_document' => $cashout->beneficiarydocument
            ];

            Log::info('BSPayTrait::liberarSaqueManual - Dados enviados para BSPayService:', $paymentData);

            $response = $bspay->makePayment($paymentData);

            Log::info('BSPayTrait::liberarSaqueManual - Resposta do BSPayService:', [
                'response' => $response,
                'has_transaction_id' => isset($response['transactionId']),
                'is_error' => isset($response['error'])
            ]);

            if (!$response || !isset($response['transactionId'])) {
                Log::error('BSPayTrait::liberarSaqueManual - Erro na resposta do BSPayService:', [
                    'response' => $response,
                    'external_id' => $externalId
                ]);
                return back()->with('error', 'Erro ao processar pagamento via BSPay.');
            }

            $pixcashout = [
                "externalreference" => $externalId,
                "idTransaction" => $externalId,
                "end_to_end" => $externalId,
                "descricao_transacao" => "LIBERADOADMIN"
            ];

            Log::info('BSPayTrait::liberarSaqueManual - Atualizando registro PIX:', $pixcashout);
            $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
            Log::info('BSPayTrait::liberarSaqueManual - Registro PIX atualizado com sucesso');
            Log::info('=== FIM BSPAYTRAIT LIBERAR SAQUE MANUAL (PIX) ===');
            return back()->with('success', 'Pedido de saque enviado com sucesso!');

        } catch (\Exception $e) {
            Log::error('BSPayTrait::liberarSaqueManual - Exceção capturada:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'id' => $id
            ]);
            Log::info('=== FIM BSPAYTRAIT LIBERAR SAQUE MANUAL (ERRO) ===');
            return back()->with('error', 'Erro interno do servidor.');
        }
    }
}

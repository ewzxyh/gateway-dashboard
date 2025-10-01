<?php

namespace App\Traits;

use App\Models\SolicitacoesCashOut;
use App\Models\Solicitacoes;
use App\Models\User;
use App\Services\WooviService;
use App\Helpers\Helper;
use App\Traits\IPManagementTrait;
use App\Helpers\TaxaFlexivelHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait WooviTrait
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
     * Processar pagamento via Woovi (Cash In)
     */
    public static function requestPaymentWoovi($request)
    {
        try {
            $woovi = \App\Models\Woovi::first();
            if (!$woovi || !$woovi->status) {
                return [
                    "status" => 500,
                    "data" => [
                        "status" => "error",
                        "message" => "Woovi não configurado ou inativo."
                    ]
                ];
            }

            // Usar o usuário já autenticado pelo middleware
            $user = $request->user;
            if (!$user) {
                return [
                    "status" => 404,
                    "data" => [
                        "status" => "error",
                        "message" => "Usuário não encontrado."
                    ]
                ];
            }

            $valor = (float) $request->amount;
            $setting = \App\Models\App::first();
            $user = $request->user;
            $taxafixa = $user->taxa_cash_in_fixa;

            // Calcula taxas usando o sistema flexível (com prioridade do usuário)
            $taxaCalculada = TaxaFlexivelHelper::calcularTaxaDeposito($valor, $setting, $user);
            $valor_liquido = $taxaCalculada['deposito_liquido'];
            $taxa_cash_in = $taxaCalculada['taxa_cash_in'];
            $descricao_taxa = $taxaCalculada['descricao'];

            // Adiciona taxa fixa do usuário (se configurada)
            if ($taxafixa > 0) {
                $valor_liquido = $valor_liquido - $taxafixa;
                $taxa_cash_in = $taxa_cash_in + $taxafixa;
            }

            $date = Carbon::now();
            $descricao = "Depósito PIX via Woovi - R$ " . number_format($valor, 2, ',', '.');

            $wooviService = new WooviService();

            // Validar CPF/CNPJ antes de enviar para o Woovi
            $documentNumber = $request->debtor_document_number ?? $user->cpf_cnpj ?? '00000000000';
            
            // Se não for um documento padrão de teste, validar
            if ($documentNumber !== '00000000000') {
                $cleanDocument = preg_replace('/\D/', '', $documentNumber);
                
                // Verificar se é um CPF (11 dígitos) - válido ou inválido
                if (strlen($cleanDocument) === 11) {
                    if (!Helper::validarCPF($documentNumber)) {
                        return [
                            "status" => 400,
                            "data" => [
                                "status" => "error",
                                "message" => "CPF inválido. Por favor, verifique o número do documento."
                            ]
                        ];
                    }
                }
                
                // Verificar se é um CNPJ (14 dígitos)
                if (strlen($cleanDocument) === 14) {
                    if (!Helper::validarCNPJ($documentNumber)) {
                        return [
                            "status" => 400,
                            "data" => [
                                "status" => "error",
                                "message" => "CNPJ inválido. Por favor, verifique o número do documento."
                            ]
                        ];
                    }
                }
            }

            // Dados para criar cobrança
            $chargeData = [
                'correlationID' => 'dep_' . $user->id . '_' . time(),
                'value' => $valor * 100, // Woovi usa centavos
                'comment' => $descricao,
                'customer' => [
                    'name' => $request->debtor_name ?? $user->name,
                    'taxID' => $documentNumber,
                    'email' => $request->email ?? $user->email,
                    'phone' => $request->phone ?? $user->telefone ?? '00000000000'
                ]
            ];

            $response = $wooviService->createCharge($chargeData);

            if (isset($response['error']) && $response['error']) {
                return [
                    "status" => 500,
                    "data" => [
                        "status" => "error",
                        "message" => $response['message']
                    ]
                ];
            }

            // Criar registro de solicitação
            $solicitacao = Solicitacoes::create([
                'user_id' => $user->user_id,
                'externalreference' => $response['charge']['correlationID'] ?? uniqid(),
                'amount' => $valor,
                'deposito_liquido' => $valor_liquido,
                'taxa_cash_in' => $taxa_cash_in,
                'taxa_pix_cash_in_adquirente' => $taxa_cash_in,
                'taxa_pix_cash_in_valor_fixo' => 0,
                'client_name' => $request->debtor_name ?? $user->name,
                'client_document' => $documentNumber,
                'client_email' => $request->email ?? $user->email,
                'client_telefone' => $request->phone ?? $user->telefone ?? '00000000000',
                'executor_ordem' => 'SISTEMA',
                'status' => 'PENDING',
                'descricao_transacao' => 'DEPOSITO_WOOVI',
                'idTransaction' => $response['charge']['correlationID'] ?? uniqid(),
                'woovi_identifier' => $response['charge']['identifier'] ?? null,
                'qrcode_pix' => $response['charge']['qrCodeImage'] ?? null,
                'paymentcode' => $response['charge']['pixKey'] ?? null,
                'paymentCodeBase64' => $response['charge']['qrCodeImage'] ?? null,
                'method' => 'PIX',
                'adquirente_ref' => 'woovi',
                'callback' => $request->postback ?? $user->webhook_url ?? env('APP_URL') . '/callback/',
                'date' => $date,
                'created_at' => $date,
                'updated_at' => $date
            ]);

            return [
                "status" => 200,
                "data" => [
                    "status" => "success",
                    "message" => "Cobrança PIX criada com sucesso",
                    "idTransaction" => $response['charge']['correlationID'],
                    "qrcode" => $response['charge']['brCode'] ?? null, // Código PIX para copiar e colar
                    "qr_code_image_url" => $response['charge']['qrCodeImage'] ?? null, // URL da imagem do QR Code
                    "charge" => [
                        "id" => $response['charge']['correlationID'],
                        "value" => $valor,
                        "qrCode" => $response['charge']['qrCodeImage'] ?? null,
                        "brCode" => $response['charge']['brCode'] ?? null,
                        "pixKey" => $response['charge']['pixKey'] ?? null,
                        "expiresAt" => $response['charge']['expiresAt'] ?? null
                    ],
                    "solicitacao_id" => $solicitacao->id
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Erro no WooviTrait::requestPaymentWoovi: ' . $e->getMessage());
            return [
                "status" => 500,
                "data" => [
                    "status" => "error",
                    "message" => "Erro interno do servidor."
                ]
            ];
        }
    }

    /**
     * Processar saque via Woovi (Cash Out)
     */
    public static function requestSaqueWoovi($request)
    {
        try {
            $woovi = \App\Models\Woovi::first();
            if (!$woovi || !$woovi->status) {
                return [
                    "status" => 500,
                    "data" => [
                        "status" => "error",
                        "message" => "Woovi não configurado ou inativo."
                    ]
                ];
            }

            // Usar o usuário já autenticado pelo middleware
            $user = $request->user;
            if (!$user) {
                return [
                    "status" => 404,
                    "data" => [
                        "status" => "error",
                        "message" => "Usuário não encontrado."
                    ]
                ];
            }

            // Verificar se o IP está autorizado para saques
            $ipCheck = self::checkIPForWithdraw($user);
            if (!$ipCheck['success']) {
                return [
                    "status" => 403,
                    "data" => [
                        "status" => "error",
                        "message" => $ipCheck['message'],
                        "client_ip" => $ipCheck['client_ip']
                    ]
                ];
            }

            $valor = (float) $request->amount;
            $setting = \App\Models\App::first();
            $user = $request->user;

            // Determinar se é saque via interface web ou API
            $isInterfaceWeb = $request->input('baasPostbackUrl') === 'web';

            // Verificar se deve usar taxa por fora para saques via API
            $taxaPorFora = $setting->taxa_por_fora_api ?? true;

            // Calcula taxas de saque usando o helper centralizado
            $taxaCalculada = \App\Helpers\TaxaSaqueHelper::calcularTaxaSaque($valor, $setting, $user, $isInterfaceWeb, $taxaPorFora);
            $cashout_liquido = $taxaCalculada['saque_liquido'];
            $taxa_cash_out = $taxaCalculada['taxa_cash_out'];
            $descricao_taxa = $taxaCalculada['descricao'];
            $valor_total_descontar = $taxaCalculada['valor_total_descontar'] ?? $valor;

            $date = Carbon::now();
            $descricao = "Saque PIX via Woovi - R$ " . number_format($valor, 2, ',', '.');

            // Verificar saldo considerando taxa por fora
            $saldo_necessario = $taxaPorFora ? $valor_total_descontar : $cashout_liquido;
            if ($user->saldo < $saldo_necessario) {
                return [
                    "status" => 401,
                    "data" => [
                        "status" => "error",
                        "message" => "Saldo insuficiente. Necessário: R$ " . number_format($saldo_necessario, 2, ',', '.') . ", Disponível: R$ " . number_format($user->saldo, 2, ',', '.')
                    ]
                ];
            }

            // Verificar se é saque automático ou manual
            if ($request->has('saque_automatico') && $request->saque_automatico) {
                return self::processarSaqueAutomatico($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
            } else {
                return self::generateTransactionPaymentManual($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
            }

        } catch (\Exception $e) {
            Log::error('Erro no WooviTrait::requestSaqueWoovi: ' . $e->getMessage());
            return [
                "status" => 500,
                "data" => [
                    "status" => "error",
                    "message" => "Erro interno do servidor."
                ]
            ];
        }
    }

    /**
     * Processar saque automático via Woovi
     */
    protected static function processarSaqueAutomatico($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        try {
            $wooviService = new WooviService();

            // Validar chave PIX primeiro (desabilitado temporariamente para teste)
            // $validation = $wooviService->validatePixKey($request->pixKey, $request->pixKeyType);
            // if (!$validation['valid']) {
            //     return [
            //         "status" => 400,
            //         "data" => [
            //             "status" => "error",
            //             "message" => "Chave PIX inválida: " . $validation['message']
            //         ]
            //     ];
            // }

            // Dados para criar saque
            $withdrawalData = [
                'value' => $request->amount * 100, // Woovi usa centavos
                'pixKey' => $request->pixKey,
                'pixKeyType' => $request->pixKeyType,
                'description' => $descricao
            ];

            $response = $wooviService->createWithdrawal($withdrawalData);

            if (isset($response['error']) && $response['error']) {
                return [
                    "status" => 500,
                    "data" => [
                        "status" => "error",
                        "message" => $response['message']
                    ]
                ];
            }

            // Criar registro de saque completado
            $solicitacao = SolicitacoesCashOut::create([
                'user_id' => $user->user_id,
                'externalreference' => $response['transactionId'] ?? uniqid('woovi_saque_'),
                'amount' => $request->amount,
                'beneficiaryname' => $user->name ?? 'Usuário',
                'beneficiarydocument' => $user->cpf_cnpj ?? '00000000000',
                'pix' => $request->pixKey,
                'pixkey' => $request->pixKey,
                'date' => $date,
                'status' => 'COMPLETED',
                'type' => 'PIX',
                'idTransaction' => $response['transactionId'] ?? uniqid(),
                'taxa_cash_out' => $taxa_cash_out,
                'cash_out_liquido' => $cashout_liquido,
                'descricao_transacao' => 'AUTOMATICO',
                'callback' => $user->webhook_url ?? null,
                'created_at' => $date,
                'updated_at' => $date
            ]);

            // Atualizar saldo do usuário
            // Para taxa por fora, descontar valor + taxa do saldo
            $valor_para_descontar = $taxaPorFora ? $valor_total_descontar : $request->amount;
            Helper::decrementAmount($user, $valor_para_descontar, 'saldo');
            $user->increment('valor_sacado', $request->amount);

            return [
                "status" => 200,
                "data" => [
                    "status" => "success",
                    "message" => "Saque processado automaticamente com sucesso",
                    "transaction" => [
                        "id" => $response['transactionId'] ?? $solicitacao->id_transacao,
                        "value" => $request->amount,
                        "status" => "COMPLETED",
                        "processedAt" => $date->toISOString()
                    ],
                    "solicitacao_id" => $solicitacao->id
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Erro no WooviTrait::processarSaqueAutomatico: ' . $e->getMessage());
            return [
                "status" => 500,
                "data" => [
                    "status" => "error",
                    "message" => "Erro ao processar saque automático via Woovi."
                ]
            ];
        }
    }

    /**
     * Gerar transação de saque manual
     */
    protected static function generateTransactionPaymentManual($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        try {
            $solicitacao = SolicitacoesCashOut::create([
                'user_id' => $user->user_id,
                'externalreference' => uniqid('woovi_manual_'),
                'amount' => $request->amount,
                'beneficiaryname' => $user->name ?? 'Usuário',
                'beneficiarydocument' => $user->cpf_cnpj ?? '00000000000',
                'pix' => $request->pixKey,
                'pixkey' => $request->pixKey,
                'date' => $date,
                'status' => 'PENDING',
                'type' => 'PIX',
                'idTransaction' => uniqid('woovi_'),
                'taxa_cash_out' => $taxa_cash_out,
                'cash_out_liquido' => $cashout_liquido,
                'descricao_transacao' => 'MANUAL',
                'created_at' => $date,
                'updated_at' => $date
            ]);

            return [
                "status" => 200,
                "data" => [
                    "status" => "success",
                    "message" => "Solicitação de saque criada com sucesso. Aguarde aprovação.",
                    "transaction" => [
                        "id" => $solicitacao->id_transacao,
                        "value" => $request->amount,
                        "status" => "PENDING",
                        "createdAt" => $date->toISOString()
                    ],
                    "solicitacao_id" => $solicitacao->id
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Erro no WooviTrait::generateTransactionPaymentManual: ' . $e->getMessage());
            return [
                "status" => 500,
                "data" => [
                    "status" => "error",
                    "message" => "Erro ao criar solicitação de saque."
                ]
            ];
        }
    }
}

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
use App\Models\Xgate;
use App\Helpers\Helper;
use App\Services\XGate as XGateService;

trait XgateTrait
{

    public static function requestDepositXgate($data)
    {

        $xgate = new XGateService();
        $response = $xgate->genPayment($data);

        if (isset($response['id'])) {
            $setting = App::first();
            $user = $data->user;

            // Calcula taxas usando apenas configurações globais
            $taxaCalculada = TaxaFlexivelHelper::calcularTaxaDeposito($data->amount, $setting, $user);
            $deposito_liquido = $taxaCalculada['deposito_liquido'];
            $taxa_cash_in = $taxaCalculada['taxa_cash_in'];
            $descricao = $taxaCalculada['descricao'];

            $date = Carbon::now();

            $cashin = [
                "user_id"                       => $data->user->username,
                "externalreference"             => $response['id'],
                "amount"                        => $data->amount,
                "client_name"                   => $data->debtor_name,
                "client_document"               => $data->debtor_document_number,
                "client_email"                  => $data->email,
                "date"                             => $date,
                "status"                        => 'WAITING_FOR_APPROVAL',
                "idTransaction"                 => $response['id'],
                "deposito_liquido"              => $deposito_liquido,
                "qrcode_pix"                    => $response['code'],
                "paymentcode"                   => $response['code'],
                "paymentCodeBase64"             => $response['code'],
                "adquirente_ref"                => 'xgate',
                "taxa_cash_in"                  => $taxa_cash_in,
                "taxa_pix_cash_in_adquirente"   => 0,
                "taxa_pix_cash_in_valor_fixo"   => 0,
                "client_telefone"               => $data->phone,
                "executor_ordem"                => 'xgate',
                "descricao_transacao"           => $descricao,
                "callback"                      => env('APP_URL') . '/callback/',
                "split_email"                   => null,
                "split_percentage"              => null,
            ];

            Solicitacoes::create($cashin);

            if (!is_null($user->integracao_utmfy)) {

                $ip = $data->header('X-Forwarded-For') ?
                    $data->header('X-Forwarded-For') : ($data->header('CF-Connecting-IP') ?
                        $data->header('CF-Connecting-IP') :
                        $data->ip());

                $msg = "PIX Gerado " . env('APP_NAME');
                UtmfyTrait::gerarUTM('pix', 'waiting_payment', $cashin, $user->integracao_utmfy, $ip, $msg);
            }

            return [
                "data" => [
                    "idTransaction" => $response['id'],
                    "qrcode" => $response['code'],
                    "qr_code_image_url" => 'https://quickchart.io/qr?text=' . urlencode($response['code'])
                ],
                "status" => 200
            ];
        }
    }

    public static function requestPaymentXgate($request)
    {
        $data = $request->all();

        $user = User::where('id', $request->user->id)->first();

        $setting = App::first();

        $user = $request->user;
        
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

        // Verificar saldo considerando taxa por fora
        $saldo_necessario = $taxaPorFora ? $valor_total_descontar : $cashout_liquido;
        if ($user->saldo < $saldo_necessario) {
            return [
                'status' => 401,
                'data' => ['message' => "Saldo insuficiente. Necessário: R$ " . number_format($saldo_necessario, 2, ',', '.') . ", Disponível: R$ " . number_format($user->saldo, 2, ',', '.')]
            ];
        }

        $date = Carbon::now();

        if ($request->baasPostbackUrl === 'web') {
            if ($request->has('saque_automatico') && $request->saque_automatico) {
                // Processar saque automático diretamente via API
                return self::processarSaqueAutomatico($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
            } else {
                // Processar como manual (criar solicitação para aprovação)
                return self::generateTransactionPaymentManual($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user);
            }
        }

        $xgate = new XGateService();
        $response = $xgate->genWithdraw($data);

        if (isset($response['message'])) {
            return [
                'status' => 401,
                'data' => ['message' => "Houve um erro. Tente novamente mais tarde."]
            ];
        }

        if (isset($response['status'])) {
            $name = $request->user->name;

            $pixKey = $request->pixKey;

            switch ($request->pixKeyType) {
                case 'cpf':
                case 'cnpj':
                case 'phone':
                    $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                    break;
            }

            $pixcashout = [
                "user_id"               => $request->user->username,
                "externalreference"     => $response['id'],
                "amount"                => $request->amount,
                "beneficiaryname"       => $name,
                "beneficiarydocument"   => $pixKey,
                "pix"                   => $pixKey,
                "pixkey"                => strtolower($request->pixKeyType),
                "date"                  => $date,
                "status"                => "PENDING",
                "type"                  => "PIX",
                "idTransaction"         => $response['id'],
                "taxa_cash_out"         => $taxa_cash_out,
                "cash_out_liquido"      => $cashout_liquido,
                "end_to_end"            => $response['id'],
                "callback"              => env('APP_URL') . '/callback/',
                "descricao_transacao"   => $descricao
            ];

            SolicitacoesCashOut::create($pixcashout);

            return [
                "status" => 200,
                "data" => [
                    "id"                => $response['id'],
                    "amount"            => $request->amount,
                    "pixKey"            => $request->pixKey,
                    "pixKeyType"        => $request->pixKeyType,
                    "withdrawStatusId"  => "PendingProcessing",
                    "createdAt"         => $date,
                    "updatedAt"         => $date
                ]
            ];
        }
    }

    protected static function generateTransactionPaymentManual($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        $idTransaction = Str::uuid()->toString();

        $name = $request->user->name;
        $pixKey = $request->pixKey;

        switch ($request->pixKeyType) {
            case 'cpf':
            case 'cnpj':
            case 'phone':
                $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                break;
        }

        $pixcashout = [
            "user_id"               => $request->user->username,
            "externalreference"     => $idTransaction,
            "amount"                => $request->amount,
            "beneficiaryname"       => $name,
            "beneficiarydocument"   => $pixKey,
            "pix"                   => $pixKey,
            "pixkey"                => strtolower($request->pixKeyType),
            "date"                  => $date,
            "status"                => "PENDING",
            "type"                  => $request->pixKeyType == "crypto" ? "CRYPTO" : "PIX",
            "idTransaction"         => $idTransaction,
            "taxa_cash_out"         => $taxa_cash_out,
            "cash_out_liquido"      => $cashout_liquido,
            "end_to_end"            => $idTransaction,
            "callback"              => $request->baasPostbackUrl,
            "blockchainNetwork"     => $request->blockchainNetwork ?? null,
            "cryptocurrency"        => $request->cryptocurrency ?? null,
            "descricao_transacao"   => "WEB"
        ];

        $cashout = SolicitacoesCashOut::create($pixcashout);

        return [
            "status" => 200,
            "data" => [
                "id"                => $idTransaction,
                "amount"            => $request->amount,
                "pixKey"            => $request->pixKey,
                "pixKeyType"        => $request->pixKeyType,
                "withdrawStatusId"  => "PendingProcessing",
                "createdAt"         => $date,
                "updatedAt"         => $date
            ]
        ];
    }

    public static function liberarSaqueManual($id)
    {

        $cashout = SolicitacoesCashOut::where('id', $id)->with('user')->first();
        $callback = url("cashtime/callback/withdraw");

        $xgate = new XGateService();
        if ($cashout->type == "CRYPTO") {
            $payload = [];
            $payload['amount'] = (float) $cashout->cash_out_liquido;
            $payload["blockchainNetwork"] = $cashout->blockchainNetwork;
            $payload["cryptocurrency"] = $cashout->cryptocurrency;
            $payload["wallet"] = $cashout->pix;

            $dt = [];
            $dt["user"] = $cashout->user;

            $response = $xgate->genWithdrawCrypto($payload, $dt);
            if (isset($response['message'])) {
                return back()->with('error', $response['message']);
            }


            $pixcashout = [
                "externalreference"     => $response['id'],
                "idTransaction"         => $response['id'],
                "end_to_end"            => $response['id'],
                "descricao_transacao"   => "LIBERADOADMIN"
            ];

            $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
            return back()->with('success', 'Pedido de saque enviado com sucesso!');
        }

        $data = [
            'amount' => $cashout->cash_out_liquido,
            'pixKeyType' => $cashout->pixkey,
            'pixKey' => $cashout->pix,
            'user' => $cashout->user
        ];
        $response = $xgate->genWithdraw($data);

        if (isset($response['message'])) {
            return back()->with('error', $response['message']);
        }


        $pixcashout = [
            "externalreference"     => $response['id'],
            "idTransaction"         => $response['id'],
            "end_to_end"            => $response['id'],
            "descricao_transacao"   => "LIBERADOADMIN"
        ];

        $cashout = SolicitacoesCashOut::where('id', $id)->update($pixcashout);
        return back()->with('success', 'Pedido de saque enviado com sucesso!');
    }

    /**
     * Processa saque automático diretamente via API
     */
    protected static function processarSaqueAutomatico($request, $taxa_cash_out, $cashout_liquido, $date, $descricao, $user)
    {
        $data = [
            'amount' => $request->amount,
            'pix_key' => $request->pixKey,
            'pix_key_type' => $request->pixKeyType,
            'description' => "Saque automático - " . $request->user->name,
            'beneficiary_name' => $request->user->name,
            'beneficiary_document' => $request->pixKey
        ];

        $xgate = new XGateService();
        $response = $xgate->genWithdraw($data);

        if (isset($response['message'])) {
            return [
                "status" => 500,
                "data" => [
                    "status" => "error",
                    "message" => $response['message']
                ]
            ];
        }

        if (isset($response['id'])) {
            // Criar registro de saque automático
            $idTransaction = $response['id'];
            $name = $request->user->name;
            $pixKey = $request->pixKey;

            switch ($request->pixKeyType) {
                case 'cpf':
                case 'cnpj':
                case 'phone':
                    $pixKey = preg_replace('/[^0-9]/', '', $pixKey);
                    break;
            }

            $pixcashout = [
                "user_id"               => $request->user->username,
                "externalreference"     => $idTransaction,
                "amount"                => $request->amount,
                "beneficiaryname"       => $name,
                "beneficiarydocument"   => $pixKey,
                "pix"                   => $pixKey,
                "pixkey"                => strtolower($request->pixKeyType),
                "date"                  => $date,
                "status"                => "COMPLETED", // Status de completado para saque automático
                "type"                  => "PIX",
                "idTransaction"         => $idTransaction,
                "taxa_cash_out"         => $taxa_cash_out,
                "cash_out_liquido"      => $cashout_liquido,
                "end_to_end"            => $idTransaction,
                "callback"              => env('APP_URL') . '/callback/',
                "descricao_transacao"   => "AUTOMATICO"
            ];

            $cashout = SolicitacoesCashOut::create($pixcashout);

            // Atualizar saldo do usuário
            Helper::decrementAmount($user, $cashout_liquido, 'saldo');
            Helper::incrementAmount($user, $request->amount, 'valor_sacado');
            Helper::calculaSaldoLiquido($user->user_id);
            
            // Log específico para saque
            \App\Helpers\BalanceLogHelper::logSaqueOperation(
                'SAQUE_REQUEST',
                $user,
                $request->amount,
                [
                    'adquirente' => 'XGATE',
                    'valor_bruto' => $request->amount,
                    'valor_descontado' => $cashout_liquido,
                    'taxa_cash_out' => $taxa_cash_out,
                    'external_id' => $externalId,
                    'operacao' => 'generateTransactionPaymentManual'
                ]
            );

            return [
                "status" => 200,
                "data" => [
                    "id"                => $idTransaction,
                    "amount"            => $request->amount,
                    "pixKey"            => $request->pixKey,
                    "pixKeyType"        => $request->pixKeyType,
                    "withdrawStatusId"  => "Completed",
                    "createdAt"         => $date,
                    "updatedAt"         => $date
                ]
            ];
        } else {
            return [
                "status" => 500,
                "data" => [
                    "status" => "error",
                    "message" => "Erro ao processar saque automático via API XGate."
                ]
            ];
        }
    }
}

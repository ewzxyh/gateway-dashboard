<?php

namespace App\Http\Controllers\Admin\Ajustes;

use App\Http\Controllers\Controller;
use App\Models\AdMercadopago;
use App\Models\Adquirente;
use Illuminate\Http\Request;
use App\Models\Cashtime;
use App\Models\App;
use App\Models\Efi;
use App\Models\Pagarme;
use App\Models\Witetec;
use App\Models\Xgate;
use App\Models\Pixup;
use App\Models\BSPay;
use App\Models\Woovi;
use App\Models\Asaas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use App\Traits\EfiTrait;

class AdquirentesController extends Controller
{
    public function index()
    {
        $cashtime = Cashtime::first();
        $mercadopago = AdMercadopago::first();
        $pagarme = Pagarme::first();
        $efi = Efi::first();
        $xgate = Xgate::first();
        $witetec = Witetec::first();
        $pixup = Pixup::first();
        $bspay = BSPay::first();
        $woovi = Woovi::first();
        $asaas = Asaas::first();
        $settings = App::first();
        $adquirente_default = Adquirente::where('is_default', 1)->first();
        $default = $adquirente_default ? $adquirente_default->referencia : null;
        $adquirentes = Adquirente::all();

        return view("admin.ajustes.adquirentes", compact(
            'efi',
            'xgate',
            'witetec',
            'cashtime',
            'mercadopago',
            'pagarme',
            'pixup',
            'bspay',
            'woovi',
            'asaas',
            'settings',
            'default',
            'adquirentes'
        ));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        $payload = [];
        foreach ($data as $key => $value) {
            if ($key == 'secret') {
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        //dd($request->all());
        $setting = Cashtime::first()->update($payload);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updateEfi(Request $request)
    {
        $data = $request->except(['_token', '_method']);
        //dd($data);
        //$payload = [];
        $data['billet_tx_fixed'] = (float) str_replace(',', '.', $data['billet_tx_fixed']) ?? 0;
        $data['billet_tx_percent'] = (float) str_replace(',', '.', $data['billet_tx_percent']) ?? 0;
        $data['billet_days_availability'] = (int) $data['billet_days_availability'] ?? 0;

        $data['card_tx_fixed'] = (float) str_replace(',', '.', $data['card_tx_fixed']) ?? 0;
        $data['card_tx_percent'] = (float) str_replace(',', '.', $data['card_tx_percent']) ?? 0;
        $data['card_days_availability'] = (int) $data['card_days_availability'] ?? 0;
       
        if ($request->hasFile('cert') && $request->file('cert')->isValid()) {
            $certificado = $request->file('cert');
            $data['cert'] = "Certificado adcionado";
            // Armazena como 'producao.pem'
            Storage::disk('certificados')->put('producao.p12', file_get_contents($certificado));
            $certPath = storage_path('app/private/certificados/producao.p12');
            $pemPath = storage_path('app/private/certificados/producao.pem');
            $process = new Process([
                'openssl',
                'pkcs12',
                '-in',
                $certPath,
                '-out',
                $pemPath,
                '-nodes',
                '-password',
                'pass:'
            ]);
            $process->run();

            if ($process->isSuccessful()) {
                Log::debug("Certificado convertido com sucesso.");
            } else {
                Log::error('Erro OpenSSL: ' . $process->getErrorOutput());
            }
        }


        Efi::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');
    }

    public function updateMercadopago(Request $request)
    {
        AdMercadopago::first()->update([
            'access_token' => $request->input('access_token')
        ]);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updatePagarme(Request $request)
    {
        $data = [];

        $data['secret'] = $request->input('secret');
        $data['taxa_pix_cash_in'] = (float) str_replace(',','.',$request->input('taxa_pix_cash_in'));
        $data['taxa_pix_cash_out'] = (float) str_replace(',','.',$request->input('taxa_pix_cash_out'));

        Pagarme::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updateXgate(Request $request)
    {
        $data = [];

        $data['email'] = $request->input('email');
        $data['password'] = $request->input('password');
        
        Xgate::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updateWitetec(Request $request)
    {
        $data = [];

        $data['api_token'] = $request->input('api_token');
        
        Witetec::first()->update($data);

        return back()->with('success', 'Dados alterados com sucesso!');

        // Retornar uma resposta de sucesso
        return response('success');
    }

    public function updatePixup(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'url' => 'required|string',
        ]);

        $data = $request->except(['_token', '_method']);
        
        // Campos que devem ser salvos como string
        $stringFields = ['client_id', 'client_secret', 'url'];
        
        $payload = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $stringFields)) {
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        
        // Garante que existe um registro na tabela
        $pixup = Pixup::first();
        if (!$pixup) {
            $pixup = Pixup::create($payload);
        } else {
            $pixup->update($payload);
        }

        return back()->with('success', 'Dados alterados com sucesso!');
    }

    public function updateBSPay(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'url' => 'required|string',
        ]);

        $data = $request->except(['_token', '_method']);
        
        // Campos que devem ser salvos como string
        $stringFields = ['client_id', 'client_secret', 'url'];
        
        $payload = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $stringFields)) {
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        
        // Garante que existe um registro na tabela
        $bspay = BSPay::first();
        if (!$bspay) {
            $bspay = BSPay::create($payload);
        } else {
            $bspay->update($payload);
        }

        return back()->with('success', 'Dados alterados com sucesso!');
    }

    public function updateWoovi(Request $request)
    {
        $request->validate([
            'app_id' => 'required|string',
            'api_key' => 'required|string',
            'url' => 'required|string',
        ]);

        $data = $request->except(['_token', '_method']);
        
        // Campos que devem ser salvos como string
        $stringFields = ['app_id', 'api_key', 'webhook_secret', 'url'];
        
        $payload = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $stringFields)) {
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        
        // Garante que existe um registro na tabela
        $woovi = Woovi::first();
        if (!$woovi) {
            $woovi = Woovi::create($payload);
        } else {
            $woovi->update($payload);
        }

        return back()->with('success', 'Dados da Woovi alterados com sucesso!');
    }

    public function adquirenteDefault(Request $request)
    {
        $default = $request->input('adquirente');
        
        // Atualizar a adquirente padrão global
        Adquirente::query()->update(['is_default' => 0]);
        Adquirente::where('referencia', $default)->first()->update(['is_default' => 1]);

        return back()->with('success', 'Adquirente padrão global alterada com sucesso!');
    }

    public function toggleAdquirente(Request $request)
    {
        $adquirenteReferencia = $request->input('adquirente');
        $adquirente = Adquirente::where('referencia', $adquirenteReferencia)->first();
        
        if (!$adquirente) {
            return back()->with('error', 'Adquirente não encontrada!');
        }

        $adquirente->update(['status' => !$adquirente->status]);
        
        $status = $adquirente->status ? 'ativada' : 'desativada';
        return back()->with('success', "Adquirente {$adquirente->adquirente} {$status} com sucesso!");
    }

    public function efiRegistrarWebhook(Request $request)
    {
        EfiTrait::cadastrarWebhook();
        return back()->with('success', 'Webhooks Efí atualizados com sucesso!');
    }

    public function updateAsaas(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'environment' => 'required|in:sandbox,production',
            'webhook_token' => 'nullable|string',
            'url' => 'nullable|string',
        ]);

        $data = $request->except(['_token', '_method']);
        
        // Campos que devem ser salvos como string
        $stringFields = ['api_key', 'environment', 'webhook_token', 'url'];
        
        $payload = [];
        foreach ($data as $key => $value) {
            if (in_array($key, $stringFields)) {
                $payload[$key] = $value;
            } else {
                $payload[$key] = (float) $value;
            }
        }
        
        // Garante que existe um registro na tabela
        $asaas = Asaas::first();
        if (!$asaas) {
            $asaas = Asaas::create($payload);
        } else {
            $asaas->update($payload);
        }

        return back()->with('success', 'Dados alterados com sucesso!');
    }

    public function updateWooviWebhookToken(Request $request)
    {
        try {
            $request->validate([
                'webhook_secret' => 'required|string|min:16'
            ]);

            $woovi = Woovi::first();
            if (!$woovi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuração do Woovi não encontrada'
                ], 404);
            }

            $woovi->update([
                'webhook_secret' => $request->webhook_secret
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Webhook token atualizado com sucesso!',
                'token' => $request->webhook_secret
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar webhook token: ' . $e->getMessage()
            ], 500);
        }
    }
}

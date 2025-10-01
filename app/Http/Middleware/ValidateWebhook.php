<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ValidateWebhook
{
    public function handle(Request $request, Closure $next)
    {
        // Verificar se é um webhook de teste
        if ($request->has('test_webhook') && $request->get('test_webhook') === 'true') {
            return $next($request);
        }

        // Validar assinatura do webhook baseada no adquirente
        $adquirente = $this->detectAdquirente($request);
        
        if (!$this->validateWebhookSignature($request, $adquirente)) {
            Log::warning('Webhook inválido recebido', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'adquirente' => $adquirente,
                'timestamp' => now()
            ]);
            
            return response()->json(['status' => 'error', 'message' => 'Webhook inválido'], 401);
        }

        return $next($request);
    }

    private function detectAdquirente(Request $request): string
    {
        $path = $request->path();
        
        if (str_contains($path, 'pixup')) return 'pixup';
        if (str_contains($path, 'bspay')) return 'bspay';
        if (str_contains($path, 'woovi')) return 'woovi';
        if (str_contains($path, 'efi')) return 'efi';
        if (str_contains($path, 'xgate')) return 'xgate';
        if (str_contains($path, 'cashtime')) return 'cashtime';
        if (str_contains($path, 'mercadopago')) return 'mercadopago';
        if (str_contains($path, 'pagarme')) return 'pagarme';
        if (str_contains($path, 'witetec')) return 'witetec';
        
        return 'unknown';
    }

    private function validateWebhookSignature(Request $request, string $adquirente): bool
    {
        switch ($adquirente) {
            case 'woovi':
                return $this->validateWooviWebhook($request);
            case 'pixup':
                return $this->validatePixupWebhook($request);
            case 'bspay':
                return $this->validateBSPayWebhook($request);
            case 'efi':
                return $this->validateEfiWebhook($request);
            case 'xgate':
                return $this->validateXgateWebhook($request);
            case 'cashtime':
                return $this->validateCashtimeWebhook($request);
            case 'mercadopago':
                return $this->validateMercadoPagoWebhook($request);
            case 'pagarme':
                return $this->validatePagarmeWebhook($request);
            case 'witetec':
                return $this->validateWitetecWebhook($request);
            default:
                // Para adquirentes desconhecidos, aceitar apenas em ambiente de desenvolvimento
                return app()->environment('local', 'development');
        }
    }

    private function validateWooviWebhook(Request $request): bool
    {
        $woovi = \App\Models\Woovi::first();
        if (!$woovi || !$woovi->webhook_secret) {
            return false;
        }

        $authorization = $request->get('authorization');
        return $authorization === $woovi->webhook_secret;
    }

    private function validatePixupWebhook(Request $request): bool
    {
        $pixup = \App\Models\Pixup::first();
        if (!$pixup || !$pixup->webhook_secret) {
            return false;
        }

        $signature = $request->header('X-Pixup-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $pixup->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    private function validateBSPayWebhook(Request $request): bool
    {
        $bspay = \App\Models\BSPay::first();
        if (!$bspay || !$bspay->webhook_secret) {
            return false;
        }

        $signature = $request->header('X-BSPay-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $bspay->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    private function validateEfiWebhook(Request $request): bool
    {
        $efi = \App\Models\Efi::first();
        if (!$efi || !$efi->webhook_secret) {
            return false;
        }

        $signature = $request->header('X-EFI-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $efi->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    private function validateXgateWebhook(Request $request): bool
    {
        $xgate = \App\Models\Xgate::first();
        if (!$xgate || !$xgate->webhook_secret) {
            return false;
        }

        $signature = $request->header('X-XGate-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $xgate->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    private function validateCashtimeWebhook(Request $request): bool
    {
        $cashtime = \App\Models\Cashtime::first();
        if (!$cashtime || !$cashtime->webhook_secret) {
            return false;
        }

        $signature = $request->header('X-Cashtime-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $cashtime->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    private function validateMercadoPagoWebhook(Request $request): bool
    {
        $mercadopago = \App\Models\AdMercadopago::first();
        if (!$mercadopago || !$mercadopago->webhook_secret) {
            return false;
        }

        $signature = $request->header('X-MercadoPago-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $mercadopago->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    private function validatePagarmeWebhook(Request $request): bool
    {
        $pagarme = \App\Models\Pagarme::first();
        if (!$pagarme || !$pagarme->webhook_secret) {
            return false;
        }

        $signature = $request->header('X-Pagarme-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $pagarme->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }

    private function validateWitetecWebhook(Request $request): bool
    {
        $witetec = \App\Models\Witetec::first();
        if (!$witetec || !$witetec->webhook_secret) {
            return false;
        }

        $signature = $request->header('X-Witetec-Signature');
        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $witetec->webhook_secret);
        
        return hash_equals($expectedSignature, $signature);
    }
}

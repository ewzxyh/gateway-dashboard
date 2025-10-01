<?php

use App\Http\Controllers\Api\BilletController;
use App\Http\Controllers\Api\CallbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SaqueController;
use App\Http\Controllers\Api\DepositController;
use App\Http\Controllers\Api\Adquirentes\PixupController;
use App\Http\Controllers\Api\Adquirentes\BSPayController;
use App\Http\Controllers\Api\Adquirentes\AsaasController;
use Illuminate\Support\Facades\Artisan;

Route::get('/link-storage', function (Request $request) {
    // Verificar se está em ambiente local e se o usuário está autenticado
    if (!app()->environment('local') || !auth()->check()) {
        abort(403, 'Acesso não autorizado.');
    }
    
    $action = $request->get('action');
    if($action == 'migrate'){
        Artisan::call('migrate');
    } elseif($action == 'storage'){
        Artisan::call('storage:unlink');
        Artisan::call('storage:link');
    }
    
    return redirect('/');
})->middleware('auth:sanctum');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/* PIX */
Route::middleware(['check.token.secret', 'throttle:60,1'])->post('wallet/deposit/payment', [DepositController::class, 'makeDeposit']);
Route::middleware(['check.token.secret', 'check.allowed.ip', 'throttle:30,1'])->post('pixout', [SaqueController::class, 'makePayment']);
Route::middleware('throttle:20,1')->post('status', [DepositController::class, 'statusDeposito']);

/* BOLETO */
Route::middleware(['check.token.secret', 'throttle:5,1'])->post('billet/charge', [BilletController::class, 'charge']);

/* PIXUP CALLBACKS */
Route::post('pixup/callback/deposit', [PixupController::class, 'callbackDeposit'])->middleware(['validate.webhook', 'throttle:30,1']);
Route::post('pixup/callback/withdraw', [PixupController::class, 'callbackWithdraw'])->middleware(['validate.webhook', 'throttle:30,1']);
Route::post('pixup/test', [PixupController::class, 'testCallback'])->middleware(['validate.webhook', 'throttle:10,1']);

/* WOOVI CALLBACKS */
Route::post('woovi/callback', [CallbackController::class, 'callbackWoovi'])->middleware(['validate.webhook', 'throttle:30,1']);
Route::post('woovi/callback/withdraw', [CallbackController::class, 'callbackWooviWithdraw'])->middleware(['validate.webhook', 'throttle:30,1']);

/* BSPAY CALLBACKS */
Route::post('bspay/callback/deposit', [BSPayController::class, 'callbackDeposit'])->middleware(['validate.webhook', 'throttle:30,1']);
Route::post('bspay/callback/withdraw', [BSPayController::class, 'callbackWithdraw'])->middleware(['validate.webhook', 'throttle:30,1']);
Route::post('bspay/test', [BSPayController::class, 'testCallback'])->middleware(['validate.webhook', 'throttle:10,1']);

/* ASAAS CALLBACKS */
Route::post('asaas/callback/deposit', [AsaasController::class, 'callbackDeposit'])->middleware(['validate.webhook', 'throttle:30,1']);
Route::post('asaas/callback/withdraw', [AsaasController::class, 'callbackWithdraw'])->middleware(['validate.webhook', 'throttle:30,1']);
Route::post('asaas/test', [AsaasController::class, 'testCallback'])->middleware(['validate.webhook', 'throttle:10,1']);
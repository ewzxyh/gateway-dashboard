<?php

use App\Http\Controllers\IntegracaoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardControlller;
use App\Http\Controllers\EnviarDocControlller;
use App\Http\Controllers\DocumentacaoControlller;
use App\Http\Controllers\User\ChavesApiControlller;
use App\Http\Controllers\User\CheckoutControlller;
use App\Http\Controllers\User\FinanceiroControlller;
use App\Http\Controllers\User\RelatoriosControlller;
use App\Http\Controllers\Admin\Ajustes\LandingPageController;
use App\Http\Controllers\Admin\Ajustes\NivelController;
use App\Http\Controllers\User\OrderbumpController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\WebhookController;
use App\Http\Controllers\Api\Adquirentes\PixupController;
use App\Http\Controllers\Api\UnifiedCallbackController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

Route::get('/', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'welcome']);

// Callback unificado - redireciona para adquirente correta
Route::post('/callback/', [UnifiedCallbackController::class, 'handleCallback']);
Route::post('/callback/withdraw', [UnifiedCallbackController::class, 'handleWithdrawCallback']);
Route::post('/callback/test', [UnifiedCallbackController::class, 'testCallback']);

// Rota de teste da API (sem autenticação)

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardControlller::class, 'index'])->name('dashboard');
    Route::get('/enviar-doc', [EnviarDocControlller::class, 'index'])->name('profile.index');
    Route::post('/enviar-docs/{id}', [EnviarDocControlller::class, 'enviarDocs'])->where('id', ".*")->name('profile.enviardocs');

  	Route::group(['middleware' => 'check.auth'], function (){
    
      Route::get('/webhook', [WebhookController::class, 'edit'])->name('webhook.index');
      Route::post('/webhook/update', [WebhookController::class, 'update'])->name('webhook.update');
      Route::get('/documentacao', [DocumentacaoControlller::class, 'index'])->name('documentacao');

      Route::get('/my-profile', [ProfileController::class, 'index'])->name('my.profile.index');
      Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
      Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
      Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
      Route::post('/user/avatar-upload', [ProfileController::class, 'uploadAvatar'])->name('profile.avatar.upload');
      
      // Rotas para gerenciar IPs permitidos
      Route::post('/profile/add-ip', [ProfileController::class, 'addAllowedIP'])->name('profile.add-ip');
      Route::post('/profile/remove-ip', [ProfileController::class, 'removeAllowedIP'])->name('profile.remove-ip');
      Route::get('/profile/allowed-ips', [ProfileController::class, 'getAllowedIPs'])->name('profile.allowed-ips');
      
      // Rotas para gerenciar PIN
      Route::post('/profile/create-pin', [ProfileController::class, 'createPin'])->name('profile.create-pin');
      Route::post('/profile/change-pin', [ProfileController::class, 'changePin'])->name('profile.change-pin');
      Route::post('/profile/toggle-pin', [ProfileController::class, 'togglePin'])->name('profile.toggle-pin');
      Route::post('/profile/remove-pin', [ProfileController::class, 'removePin'])->name('profile.remove-pin');
      Route::post('/profile/verify-pin', [ProfileController::class, 'verifyPin'])->name('profile.verify-pin');

      // Rotas do 2FA
      Route::get('/2fa/status', [App\Http\Controllers\TwoFactorAuthController::class, 'status'])->name('2fa.status');
      Route::post('/2fa/generate-qr', [App\Http\Controllers\TwoFactorAuthController::class, 'generateQrCode'])->name('2fa.generate-qr');
      Route::post('/2fa/verify', [App\Http\Controllers\TwoFactorAuthController::class, 'verifyCode'])->name('2fa.verify');
      Route::post('/2fa/enable', [App\Http\Controllers\TwoFactorAuthController::class, 'enable'])->name('2fa.enable');
      Route::post('/2fa/disable', [App\Http\Controllers\TwoFactorAuthController::class, 'disable'])->name('2fa.disable');

      Route::group(['prefix' => 'relatorio'], function () {
          Route::get('/entradas', [RelatoriosControlller::class, 'pixentrada'])->name('profile.relatorio.pixentrada');
          Route::get('/entradas/detalhes/{id}', [RelatoriosControlller::class, 'getTransactionDetails'])->name('profile.relatorio.entradas.detalhes');
          Route::post('/entradas/estornar/{id}', [RelatoriosControlller::class, 'estornarTransacao'])->name('profile.relatorio.entradas.estornar')->middleware('throttle:5,1');
          Route::post('/entradas/cancelar/{id}', [RelatoriosControlller::class, 'cancelarTransacao'])->name('profile.relatorio.entradas.cancelar')->middleware('throttle:5,1');
          Route::get('/saidas', [RelatoriosControlller::class, 'pixsaida'])->name('profile.relatorio.pixsaida');
          Route::get('/saidas/consulta', [RelatoriosControlller::class, 'consulta'])->name('profile.relatorio.consulta');
      });

      Route::get('/financeiro', [FinanceiroControlller::class, 'index'])->name('profile.financeiro');
      Route::get('/chaves', [ChavesApiControlller::class, 'index'])->name('profile.chavesapi');

      
    }); 
  	

    Route::group(['prefix' => 'produtos'], function () {
        Route::get('/', [CheckoutControlller::class, 'index'])->name('profile.checkout');
        Route::get('/visualizar/{id}', [CheckoutControlller::class, 'indexEdit'])->name('profile.checkout.produto');
        Route::put('/editar/{id}', [CheckoutControlller::class, 'edit'])->name('profile.checkout.produto.editar');

        Route::post('/', [CheckoutControlller::class, 'create'])->name('profile.checkout.create');


        Route::delete('checkout/{id}', [CheckoutControlller::class, 'destroy'])->name('profile.checkout.delete');

        Route::post('/depoimento/salvar', [CheckoutControlller::class, 'salvarDepoimento']);
        Route::post('/depoimento/remover', [CheckoutControlller::class, 'removerDepoimento']);
        Route::group(['prefix' => 'orderbumps'], function () {
            Route::post('create/{id}', [OrderbumpController::class, 'create'])->where('id', '.*')->name('checkout.orderbumps.create');
            Route::put('edit/{id}', [OrderbumpController::class, 'edit'])->where('id', '.*')->name('checkout.orderbumps.edit');
            Route::delete('remove/{id}', [OrderbumpController::class, 'removeBump'])->where('id', '.*')->name('checkout.orderbumps.remove');
        });

        Route::group(['prefix' => 'orders'], function () {
            Route::get('/', [OrderController::class, 'index'])->name('profile.orders');
        });
    });


    Route::group(['prefix'=> 'integracoes'], function () {
        Route::get('/', [IntegracaoController::class, 'index'])->name('integracoes.home');
        Route::post('/utmfy', [IntegracaoController::class, 'utmfy'])->name('integracoes.utmfy.edit');
    });


    Route::group(['prefix' => 'gerencia'], function () {
        Route::get('clientes', [App\Http\Controllers\Gerencia\ClientesController::class, 'index'])->name('gerencia.index');
        Route::get('relatorio', [App\Http\Controllers\Gerencia\ClientesController::class, 'relatorio'])->name('gerencia.relatorio');
        Route::get('material', [App\Http\Controllers\Gerencia\ClientesController::class, 'material'])->name('gerencia.material');
        Route::get('/cliente/detalhes/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'detalhes'])->name('gerencia.detalhes');
        Route::post('/cliente/status', [App\Http\Controllers\Gerencia\ClientesController::class, 'usuarioStatus'])->name('gerencia.mudarstatus');
        Route::put('/cliente/edit/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'edit'])->name('gerencia.edit');
        //Route::post('/cliente/resetsenha/{id}', [App\Http\Controllers\Gerencia\ClientesController::class, 'resetsenha'])->name('gerencia.resetsenha');
    });

    Route::group(['prefix' => env("ADM_ROUTE"), 'middleware'=> 'check.admin'], function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/usuarios', [App\Http\Controllers\Admin\UsuariosController::class, 'index'])->name('admin.usuarios');

        Route::get('/usuario/detalhes/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'detalhes'])->name('admin.usuario.detalhes');
        Route::post('/usuario/status', [App\Http\Controllers\Admin\UsuariosController::class, 'usuarioStatus'])->name('admin.usuarios.mudarstatus');
        Route::delete('/usuario/delete/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'destroy'])->name('admin.usuarios.delete');
        Route::put('/usuario/edit/{id}', [App\Http\Controllers\Admin\UsuariosController::class, 'edit'])->name('admin.usuarios.edit');
        
        // Rotas para gerenciar taxas personalizadas dos usuários
        Route::get('/usuario/{id}/taxas', [App\Http\Controllers\Admin\Usuarios\TaxasController::class, 'carregarTaxas'])->name('admin.usuario.taxas.carregar');
        Route::post('/usuario/{id}/taxas', [App\Http\Controllers\Admin\Usuarios\TaxasController::class, 'salvarTaxas'])->name('admin.usuario.taxas.salvar');
        Route::delete('/usuario/{id}/taxas', [App\Http\Controllers\Admin\Usuarios\TaxasController::class, 'desativarTaxasPersonalizadas'])->name('admin.usuario.taxas.desativar');

        Route::group(['prefix' => 'financeiro'], function () {
            Route::get('/transacoes', [App\Http\Controllers\Admin\Financeiro\TransacoesController::class, 'index'])->name('admin.financeiro.transacoes');
            Route::get('/carteiras', [App\Http\Controllers\Admin\Financeiro\CarteirasController::class, 'index'])->name('admin.financeiro.carteiras');
            Route::get('/entradas', [App\Http\Controllers\Admin\Financeiro\EntradasController::class, 'index'])->name('admin.financeiro.entradas');
            Route::get('/entradas/detalhes/{id}', [App\Http\Controllers\Admin\Financeiro\EntradasController::class, 'getTransactionDetails'])->name('admin.financeiro.entradas.detalhes');
            Route::post('/entradas/mediacao/{id}', [App\Http\Controllers\Admin\Financeiro\EntradasController::class, 'enviarMediacao'])->name('admin.financeiro.entradas.mediacao')->middleware('throttle:10,1');
            Route::get('/saidas', [App\Http\Controllers\Admin\Financeiro\SaidasController::class, 'index'])->name('admin.financeiro.saidas');
            Route::put('/entrada/{id}/status', [App\Http\Controllers\Admin\Financeiro\EntradasController::class, 'updateStatus'])->name('admin.financeiro.entrada.status');
            Route::put('/saida/{id}/status', [App\Http\Controllers\Admin\Financeiro\SaidasController::class, 'updateStatus'])->name('admin.financeiro.saida.status');
        });

        Route::group(['prefix' => 'transacoes'], function () {
            Route::get('/procurar', [App\Http\Controllers\Admin\Transacoes\ProcurarController::class, 'index'])->name('admin.transacoes.procurar');
            Route::get('/entrada', [App\Http\Controllers\Admin\Transacoes\EntradaController::class, 'index'])->name('admin.transacoes.entradas');
            Route::post('/entrada', [App\Http\Controllers\Admin\Transacoes\EntradaController::class, 'addentrada'])->name('admin.transacoes.addentrada');
            Route::get('/saida', [App\Http\Controllers\Admin\Transacoes\SaidaController::class, 'index'])->name('admin.transacoes.saidas');
            Route::post('/saida', [App\Http\Controllers\Admin\Transacoes\SaidaController::class, 'addsaida'])->name('admin.transacoes.addsaida');
            Route::post('/saida', [App\Http\Controllers\Admin\Transacoes\SaidaController::class, 'addsaida'])->name('admin.transacoes.addsaida');
        });

        Route::get('/aprovar-saques', [App\Http\Controllers\Admin\SaquesController::class, 'index'])->name('admin.saques');
        Route::put('/saques/aprovar/{id}', [App\Http\Controllers\Admin\SaquesController::class, 'aprovar'])->where('id', '.*')->name('admin.saques.aprovar');
        Route::put('/saques/rejeitar/{id}', [App\Http\Controllers\Admin\SaquesController::class, 'rejeitar'])->where('id', '.*')->name('admin.saques.rejeitar');
        
        Route::get('/saque-config', [App\Http\Controllers\Admin\SaqueConfigController::class, 'index'])->name('admin.saque-config');
        Route::put('/saque-config', [App\Http\Controllers\Admin\SaqueConfigController::class, 'update'])->name('admin.saque-config.update');

        Route::group(['prefix' => 'ajustes'], function () {
            Route::get('/adquirentes', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'index'])->name('admin.ajustes.adquirentes');
            Route::post('/cashtime', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'update'])->name('admin.adquirentes.cashtime');
            Route::post('/mercadopago', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateMercadopago'])->name('admin.adquirentes.mercadopago');
            Route::post('/efi', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateEfi'])->name('admin.adquirentes.efi');
            Route::post('/pagarme', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updatePagarme'])->name('admin.adquirentes.pagarme');
            Route::post('/xgate', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateXgate'])->name('admin.adquirentes.xgate');
            Route::post('/witetec', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateWitetec'])->name('admin.adquirentes.witetec');
            Route::post('/pixup', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updatePixup'])->name('admin.adquirentes.pixup');
            Route::post('/bspay', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateBSPay'])->name('admin.adquirentes.bspay');
            Route::post('/woovi', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateWoovi'])->name('admin.adquirentes.woovi');
            Route::post('/woovi/webhook-token', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateWooviWebhookToken'])->name('admin.adquirentes.woovi.webhook-token');
            Route::post('/asaas', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'updateAsaas'])->name('admin.adquirentes.asaas');
            Route::post('/efi/registrar-webhook', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'efiRegistrarWebhook'])->name('admin.adquirentes.efi.regitrar');
            Route::post('/ad-default', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'adquirenteDefault'])->name('admin.adquirentes.default');
            Route::post('/toggle', [App\Http\Controllers\Admin\Ajustes\AdquirentesController::class, 'toggleAdquirente'])->name('admin.adquirentes.toggle');
            Route::get('/landing-page', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'index'])->name('admin.landing.index');
            Route::post('/landing-page', [App\Http\Controllers\Admin\Ajustes\LandingPageController::class, 'update'])->name('admin.landing.update');
            Route::get('/gerais', [App\Http\Controllers\Admin\Ajustes\SegurancaController::class, 'index'])->name('admin.ajustes.seguranca');
            Route::post('/gerais', [App\Http\Controllers\Admin\Ajustes\SegurancaController::class, 'update'])->name('admin.ajustes.gerais');
            Route::post('/active-niveis', [NivelController::class, 'activeNiveis']);
            Route::group(['prefix' => 'niveis'], function () {
                Route::get('/', [NivelController::class, 'index'])->name('admin.niveis.index');
                Route::post('/', [NivelController::class, 'store']);
                Route::put('/{id}', [NivelController::class, 'update']);
                Route::delete('/{id}', [NivelController::class, 'destroy']);
            });
            Route::get('/gerentes', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'index'])->name('admin.ajustes.gerente');
            Route::post('/gerentes', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'create'])->name('admin.ajustes.gerente.add');
            Route::put('/gerentes/{id}', [App\Http\Controllers\Admin\Ajustes\GerenteController::class, 'update'])->where('id', '.*')->name('admin.ajustes.gerente.update');

            Route::group(['prefix' => 'apoio'], function () {
                Route::get('/', [App\Http\Controllers\Gerencia\ApoioController::class, 'index'])->name('admin.ajustes.apoio');
                Route::post('/', [App\Http\Controllers\Gerencia\ApoioController::class, 'create'])->name('admin.ajustes.apoio.add');
                Route::put('/{id}', [App\Http\Controllers\Gerencia\ApoioController::class, 'update'])->where('id', '.*')->name('admin.ajustes.apoio.update');
                Route::delete('/{id}', [App\Http\Controllers\Gerencia\ApoioController::class, 'destroy'])->where('id', '.*')->name('admin.ajustes.apoio.delete');
            });
        });
    });
});

Route::post('/checkout/cliente/pedido/gerar', [CheckoutControlller::class, 'gerarPedido'])->name('profile.checkout.pedido.gerar');
Route::post('/checkout/cliente/pedido/status', [CheckoutControlller::class, 'statusPedido'])->name('profile.checkout.pedido.status');
Route::get('checkout/produto/v1/{id}', [CheckoutControlller::class, 'v1'])->where('id', '.*')->name('profile.checkout.v1');
//Route::get('checkout/produto/v2', [CheckoutControlller::class, 'v2'])->name('profile.checkout.v2');

Route::get('/download-boleto', function () {
    $url = request('url');

    $response = Http::get($url);

    return Response::make($response->body(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="boleto.pdf"',
    ]);
});

require __DIR__ . '/auth.php';

// Rotas de 2FA para login
Route::get('/2fa/verify', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'show2FAForm'])->name('2fa.verify');
Route::post('/2fa/verify', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'verify2FA'])->name('2fa.verify.post');
require __DIR__ . '/groups/adquirentes/cashtime.php';
require __DIR__ . '/groups/adquirentes/mercadopago.php';
require __DIR__ . '/groups/adquirentes/efi.php';
require __DIR__ . '/groups/adquirentes/pagarme.php';
require __DIR__ . '/groups/adquirentes/xgate.php';
require __DIR__ . '/groups/adquirentes/witetec.php';
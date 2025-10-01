@php
$setting = \App\Helpers\Helper::getSetting();

$produto_tipo = $checkout->produto_tipo;
$meta_active = !empty($checkout->checkout_ads_meta);
$google_active = !empty($checkout->checkout_ads_google);

$efi = \App\Models\Adquirente::where('referencia', 'efi')->first()->status;
$regefi = \App\Models\Efi::first();
$identificar_conta = $regefi->identificador_conta ?? null;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme-mode="light" data-header-styles="transparent" style="" data-menu-styles="light">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="Description" content="{{env('APP_NAME')}}">
<meta name="Author" content="{{env('APP_NAME')}}">
<meta name="keywords" content="{{env('APP_NAME')}}">
<link rel="icon" type="image/x-icon" href="{{ asset('assets/images/site_logo/logo_white.png') }}">
<title>{{ env('APP_NAME') }} - {{ $checkout->produto_name }}</title>
<link rel="icon" href="../img/logo.png" type="image/x-icon">
<link id="style" href="{{ asset("assets-check/libs/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
<link href="{{ asset("assets-check/css/styles.css") }}" rel="stylesheet">
<link href="{{ asset("assets-check/icon-fonts/icons.css") }}" rel="stylesheet">
<link href="{{ asset("assets-check/libs/node-waves/waves.min.css") }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset("assets-check/libs/simplebar/simplebar.min.css") }}">
<link rel="stylesheet" href="{{ asset("assets-check/libs/flatpickr/flatpickr.min.css") }}">
<link rel="stylesheet" href="{{ asset("assets-check/libs/@simonwep/pickr/themes/nano.min.css") }}">
<link rel="stylesheet" href="{{ asset("assets-check/libs/@tarekraafat/autocomplete.js/css/autoComplete.css") }}">
<link rel="stylesheet" href="{{ asset("assets-check/libs/choices.js/public/assets/styles/choices.min.css") }}">
<script src="{{ asset("assets-check/libs/choices.js/public/assets/scripts/choices.min.js") }}"></script>
<script src="{{ asset("assets-check/js/main.js") }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js" integrity="sha512-F5Ul1uuyFlGnIT1dk2c4kB4DBdi5wnBJjVhL7gQlGh46Xn0VhvD8kgxLtjdZ5YN83gybk/aASUAlpdoWUjRR3g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets-checkout/css/style-checkout.css">
<style>
    :root {
        --gateway-color: {{$setting->gateway_color}};
        --checkout-color: {{$checkout->checkout_color_default ?? $setting->gateway_color}};
        --color-default: {{$checkout->checkout_color_default }};
    }
    .guide.current .guide-text .step-number,
    .qtde {
    background: var(--checkout-color) !important;
    color: #ffffff !important;
}
        input,
        select,
        [type='search'] {
            border-color: rgb(221, 220, 220) !important;
        }
        input:focus,
        select:focus {
            border-color: var(--color-gateway) !important;
            box-shadow: 1px solid var(--color-gateway) !important;
        }

    .btn-outline-custom {
        border: 2px solid rgba(44, 44, 44, 0.35);
        width: 100px !important;
        height: 100px !important;
        color: var(--color-default);
        font-weight: bold;
        background-color: transparent;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        padding-top: 0;
    }

    .btn-outline-custom:hover {
        border: 2px solid var(--color-default) !important;
    }

    .btn-outline-custom i {
        font-size: 24px;
    }
    .btn-check:checked + .btn-outline-custom {
        background-color: var(--color-default);
        color: #fff;
    }
    .swal2-confirm {
        background-color: var(--color-default) !important;
        background: var(--color-default) !important;
    }
    
</style>

@if($meta_active)
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;
    n.push=n;
    n.loaded=!0;
    n.version='2.0';
    n.queue=[];
    t=b.createElement(e);
    t.async=!0;
    t.src=v;
    s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}
    (window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');

    fbq('init', "{{ $checkout->checkout_ads_meta }}");
    fbq('track', 'PageView');
</script>

<noscript>
    <img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id={{ $checkout->checkout_ads_meta }}&ev=PageView&noscript=1"
    />
</noscript>
@endif

@if($google_active)
<script async src="https://www.googletagmanager.com/gtag/js?id={{$checkout->checkout_ads_google}}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', "{{$checkout->checkout_ads_google}}");
</script>
@endif

<body style="overflow-x:hidden;position: relative;padding:0;margin:0;width:100vw;height:100vh;background:{{$checkout->checkout_color ?? "rgb(245,242,242)"}}">
    <div id="countdown_background" style="width:100%;background-color: {{$checkout->checkout_timer_cor_fundo ?? $setting->gateway_color}}; display: {{ $checkout->checkout_timer_active ? 'block' : 'none' }};">
        <h5 class="text-center" id="texto-contador" style="padding: 12px; gap: 25px;display:flex;align-items:center;justify-content:center;gap:15px;">
            <span id="countdown_text" style="font-size: 20px !important; color: rgb(255, 255, 255);">{{ $checkout->checkout_timer_tempo ? $checkout->checkout_timer_tempo < 10 ? "0".$checkout->checkout_timer_tempo : $checkout->checkout_timer_tempo : "02" }}:00</span>
            <i id="countdown_icon" class="fa-solid fa-clock" style="font-size: 20px !important; color: rgb(255, 255, 255);"></i>
            <h8 style="font-size: 14px !important; color: rgb(255, 255, 255);" id="countdown_description">{{$checkout->checkout_timer_texto ?? "Garanta antes da oferta acabar" }}</h8>
        </h5>
    </div>
<div id="background_color" >

    <div class="container px-4">
        <div id="headerContainer" style=";background:url('{{$checkout->checkout_banner_active ? $checkout->checkout_banner : 'transparent'}}')">
                <figure style="align-content: center;">
                    <img  id="header_image1" src="{{ $checkout->checkout_header_logo ?? $setting->gateway_logo}}" alt="Logo" style="aspect-ratio: auto; display: {{ $checkout->checkout_header_logo_active ? 'block' : 'none' }};">
                </figure>

                <figure style="align-content: center;">
                    <img  id="header_image2" src="{{ $checkout->checkout_header_image ?? $setting->gateway_logo}}" alt="Logo" style="aspect-ratio: auto; display: {{ $checkout->checkout_header_image_active ? 'block' : 'none' }};">
                </figure>
        </div>
    </div>
</div>
<div id="topbar_background" style="min-height:51.60px;margin-left:0;margin-right:0;background:{{$checkout->checkout_topbar_color ?? $setting->gateway_color}};display:{{$checkout->checkout_topbar_active ? "flex" : "none" }};">
    <div id="topbar_text">
       {{ $checkout->checkout_topbar_text }}
    </div>
</div>
<div class="container">
    <div id="for_add" class="py-3 container-fluid">
        <div class="row gx-4">
            <!-- Lado A -->
            <div id="container-grid1" class="mb-4 col-lg-7 mb-lg-0">

                <!-- Steps -->
                <div class="p-3 mb-4 rounded steps-reorder-item d-flex justify-content-between bg-steps-form card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                    <div id="contact_data" class="guide ativo current">
                        <div class="text-center guide-text ativo current d-flex flex-column flex-lg-row align-items-center justify-content-center">
                            <span class="step-number"><span class="number">1</span></span>
                            <div class="mt-2 mt-lg-0 ml-lg-2 step-text default-font-color">Identificação</div>
                        </div>
                    </div>
                    @if($produto_tipo == 'fisico')
                        <div id="delivery_data" class="guide">
                            <div class="text-center guide-text d-flex flex-column flex-lg-row align-items-center justify-content-center">
                                <span class="step-number"><span class="number">2</span></span>
                                <span class="mt-2 mt-lg-0 ml-lg-2 step-text default-font-color">Entrega</span>
                            </div>
                        </div>
                    @endif
                    <div id="payment_data" class="guide">
                        <div class="text-center guide-text payment-data-text d-flex flex-column flex-lg-row align-items-center justify-content-center">
                            <span class="step-number"><span class="number">{{ $produto_tipo == 'fisico' ? '3' : '2' }}</span></span>
                            <span class="mt-2 mt-lg-0 ml-lg-2 step-text default-font-color">Pagamento</span>
                        </div>
                    </div>
                </div>

                <form id="form-paid" method="POST" action="">
                    @csrf
                    <div class="body-container card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                        <!-- Formulário step 1 -->
                        <div class="step-content" data-step="1">
                            <div class="row ">
                                <div class="mb-3 col-12 col-sm-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" style="height:42px;" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="redacted@example.invalid" required>
                                    @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3 col-12 col-sm-6">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" style="height:42px;" class="form-control @error('telefone') is-invalid @enderror" name="telefone" placeholder="(99) 99999-9999" maxlength="15" required>
                                    @error('telefone') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3 col-12 col-sm-6">
                                    <label for="name" class="form-label">Nome completo</label>
                                    <input type="text" style="height:42px;" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nome e Sobrenome" required>
                                    @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3 col-12 col-sm-6">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" style="height:42px;" class="form-control @error('cpf') is-invalid @enderror" name="cpf" placeholder="123.456.789-12" maxlength="14" required>
                                    @error('cpf') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Info segura -->
                            <div class="p-3 mt-4 info-segura">
                                <div class="mb-3 about_purchase" style="font-weight: bold;">Usamos seus dados de forma 100% segura para garantir a sua satisfação:</div>

                                <div class="mb-2 d-flex align-items-start">
                                    <img src="/assets-check/images/checkmarkSecurity.svg" class="me-2" alt="">
                                    <span class="sub">Enviar o seu comprovante de compra e pagamento;</span>
                                </div>

                                <div class="mb-2 d-flex align-items-start">
                                    <img src="/assets-check/images/checkmarkSecurity.svg" class="me-2" alt="">
                                    <span class="sub">Ativar a sua garantia de devolução caso não fique satisfeito;</span>
                                </div>

                                <div class="d-flex align-items-start">
                                    <img src="/assets-check/images/checkmarkSecurity.svg" class="me-2" alt="">
                                    <span class="sub">Acompanhar o andamento do seu pedido;</span>
                                </div>
                            </div>

                            <!-- Botão -->
                            <div class="mt-4 text-end">
                                <button onclick="metaAddToCart()" type="button" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-lg btn-wave waves-effect waves-light btn-form-checkout">
                                    {{ $produto_tipo == 'fisico' ? 'IR PARA ENTREGA' : 'IR PARA PAGAMENTO' }}
                                </button>
                            </div>
                        </div>
                        @if($produto_tipo == 'fisico')
                            <!-- Step 2: Entrega -->
                            <div class="step-content d-none" data-step="2">
                                <div class="row">
                                    <div class="mb-3 col-12 col-sm-3">
                                        <label for="cep" class="form-label">CEP</label>
                                        <input type="text" class="form-control" name="cep" placeholder="00000-000" maxlength="9" required>
                                    </div>
                                    <div class="mb-3 col-12 col-sm-9">
                                        <label for="endereco" class="form-label">Endereço</label>
                                        <input type="text" class="form-control" name="endereco" placeholder="Rua Exemplo" required>
                                    </div>
                                    <div class="mb-3 col-3">
                                        <label for="numero" class="form-label">Número</label>
                                        <input type="text" class="form-control" name="numero" placeholder="123" required>
                                    </div>
                                    <div class="mb-3 col-9">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" name="complemento" placeholder="Apto, bloco..." required>
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="bairro" class="form-label">Bairro</label>
                                        <input type="text" class="form-control" name="bairro" placeholder="Centro" required>
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="cidade" class="form-label">Cidade</label>
                                        <input type="text" class="form-control" name="cidade" placeholder="São Paulo" required>
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="cidade" class="form-label">Estado (UF)</label>
                                        <input type="text" class="form-control" name="estado" placeholder="São Paulo" required>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-dark prev-step">VOLTAR</button>
                                    <button type="button" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-lg next-step btn-form-checkout" required>IR PARA PAGAMENTO</button>
                                </div>
                            </div>
                        @endif
                        <!-- Step 3: Pagamento -->
                        <div class="step-content d-none" data-step="{{ $produto_tipo == 'fisico' ? '3' : '2' }}">
                            <div class="row">
                            <div class="mb-4 col-12 md:justify-center chk-payment-flags justify-content-sm-start selected">
                                @php
                                $metodos = json_decode($checkout->methods);
                                @endphp
                                
                                <div class="d-flex align-items-center justify-content-center w-100 gap-4">
                                    @foreach($metodos as $metodo)
                                        @if($metodo == 'pix')
                                            <input type="radio" class="btn-check" name="metodo" id="{{ $metodo }}" value="{{ $metodo }}" autocomplete="off">
                                            <label class="btn btn-outline-custom"  for="{{$metodo}}">
                                                <i class="fa-brands fa-pix"></i>&nbsp;{{'PIX'}}
                                            </label>
                                            @elseif($efi && $metodo == 'billet')
                                            <input type="radio" class="btn-check" name="metodo" id="{{ $metodo }}" value="{{ $metodo }}" autocomplete="off">
                                            <label class="btn btn-outline-custom"  for="{{$metodo}}">
                                                <i class="fa-solid fa-barcode"></i>&nbsp;{{'BOLETO'}}
                                            </label>
                                            @elseif($efi && $metodo == 'card')
                                            <input type="radio" class="btn-check" name="metodo" id="{{ $metodo }}" value="{{ $metodo }}" autocomplete="off">
                                            <label class="btn btn-outline-custom"  for="{{$metodo}}">
                                                <i class="fa-solid fa-credit-card"></i>&nbsp;{{'CARTÃO'}}
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                                <div id="pix-payment-0" class="chk-flag-option pixPayment selected">
                                    <div class="container-pix">
                                        
                                        @if(count($checkout->bumps) > 0)
                                            <span class="chk-flag-option-discount ">
                                                5% OFF
                                            </span>
                                        @endif
                                    </div>
                                    <div id="pix_data_payment" class="p-4 tab-pane fade method_data_payment pix show active" role="tabpanel" aria-labelledby="pix_data_payment-tab">
                                        <div class="mb-2 row no-gutters " style="display: flex">
                                            <div class="col-5 col-sm-3">
                                                <div class="p-1 mt-1 d-flex justify-content-center percent-discount mt-md-0">
                                                    5% OFF
                                                </div>
                                            </div>
                                            <div class="col-7 col-sm-9 d-flex align-items-center">
                                                <div class="row no-gutters discount-text">
                                                    <div class="col-12">
                                                        Garanta&nbsp;<span class="economize-value"> R$ </span>
                                                        <span id="discount_pix_span" class="mr-1 economize-value">10,35</span>
                                                        &nbsp;de desconto pagando via Pix

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row d-flex justify-content-end">
                                            <div class="col-12">
                                                <p class="obs">
                                                    Ao selecionar o Pix, você será encaminhado para um ambiente seguro para finalizar
                                                    seu pagamento.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3 col-12">
                                        @foreach($checkout->bumps as $key => $bump)
                                            <div class="p-3 mb-4 position-relative container-item" data-id="{{ $bump->id }}">
                                                <div class="row no-gutters align-items-start">
                                                    <!-- Imagem -->
                                                    <div class="text-center col-4 col-sm-3 col-md-2">
                                                        <img
                                                            src="{{ $bump->image }}"
                                                            class="rounded img-fluid"
                                                            style="max-height: 100px; object-fit: cover;"
                                                            alt="{{ $bump->nome }}"
                                                        >
                                                    </div>

                                                    <!-- Conteúdo -->
                                                    <div class="pl-3 col-8 col-sm-9 col-md-10">
                                                        <h6 class="mb-1 font-weight-bold" style="font-size: 1.1rem;">
                                                            {{ $bump->nome }}
                                                        </h6>
                                                        <p class="mb-2 text-muted">
                                                            {{ $bump->descricao }}
                                                        </p>

                                                        <p class="mb-1 text-danger" style="text-decoration: line-through;">
                                                            {{ "R$ " . number_format($bump->valor_de, 2, ',', '.') }}
                                                        </p>
                                                        <p class="text-success h5 font-weight-bold">
                                                            {{ "R$ " . number_format($bump->valor_por, 2, ',', '.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="position-absolute" style="bottom: -20px; right: 10px;">
                                                    <button type="button"
                                                        class="btn-add-bump btn btn-warning btn-lg btn-block font-weight-bold d-flex align-items-center justify-content-center toggle-bump"
                                                        style="font-size: 1.2rem;"
                                                        data-id="{{ $bump->id }}"> <!-- Pode ser o ID real se preferir -->
                                                        <i class="fa-solid fa-plus"></i>&nbsp;
                                                        <span>PEGAR OFERTA</span>
                                                    </button>
                                                </div>
                                                <span class="ob-purchased">
                                                    <span>OFERTA ADQUIRIDA</span>
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-dark prev-step">VOLTAR</button>
                                <button type="submit" style="background:{{$checkout->checkout_color_default ?? $setting->gateway_color}}" class="btn btn-form-checkout-prev btn-form-checkout btn-lg">FINALIZAR COMPRA</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Lado B -->
            <div id="container-grid2" class="p-2 pt-0 col-lg-5">
                <div class="p-0 rounded w-100 h-100">
                    <div class="p-2 pt-0 mb-4 rounded item produto-reorder-item justify-content-between bg-steps-form card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                        <div class="row ">
                            <div class="col-12">
                                <div class="p-2 mb-4 card produto card-bg" style="background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                                    <div class="row justify-content-between sidetop">
                                        <div class="pl-2 col-6 cart">
                                            Seu carrinho
                                            <span class="pt-2 pr-2 small collapse collapse-toggle d-lg-none">
                                                Informações da sua compra
                                            </span>
                                        </div>

                                        <div class="col-6">
                                            <div class="d-flex align-items-center justify-content-end h-100" style="position: relative;">
                                                <span class="valor_total collapse collapse-toggle" style="z-index: 2">R$ 207,00</span>
                                                {{-- <i class="fa-solid fa-cart-shopping" style="color:{{$checkout->checkout_color_default }};position: absolute;font-size:24px;bottom:-16.5px;right:-2px;z-index:1;"></i> --}}
                                                <div class="text-center qtde">1</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="purchase-summary__body" class="mt-2 d-lg-block">
                                        <div class="mb-3 product-list text-start">
                                            <div class="product-grid">
                                                <div class="d-flex ">
                                                    <img class="product-img" src="{{ $checkout->produto_image ?? 'assets-check/images/product_default.png' }}" onerror="this.src='https://cloudfox-files.s3.amazonaws.com/produto.svg'">
                                                </div>
                                            <div>
                                            <p class="text-lg text-start ellipsis-h" style="color:black;font-size: 18px;font-weight:bold;" > {{ $checkout->produto_name }}<p>
                                            <p class="text-start ellipsis-h" style="font-size:14px;margin-top:-15px;"> {{ $checkout->produto_descricao }}<p>
                                        </div>
                                        <div class="mt-3 d-flex align-items-center justify-content-end">
                                            <div class="input-number">
                                                <button class="btn-sub" required>
                                                    <img src="/assets-check/images/minus.svg">
                                                </button>
                                                <span class="number-qtd" readonly>1</span>
                                                <button type="button" class="btn-add" required>
                                                    <img src="/assets-check/images/plus.svg">
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <div class="ob-preview">
                                    <div class="ob-purchased-info"></div>
                                    <div class="ob-preview-content"></div>
                                </div>

                                <div>
                                    <hr>
                                </div>
                                    <div class="mb-1 cp-subtotal">
                                        <div class="p-0 mb-2 row justify-content-between">
                                            <div class="text-start col-6">
                                                <span class="subtotal">Subtotal</span>
                                            </div>
                                                <div class="text-end col-6">
                                                    <span class="text-end subtotal">
                                                        R$ <span class="subtotal-value">{{ number_format($checkout->produto_valor, '2',',','.') }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="p-0 mb-2 row justify-content-between">
                                                <div class="text-start col-6">
                                                    <span class="subtotal">Frete</span>
                                                </div>
                                                <div class="text-end col-6">
                                                    <span class="text-end subtotal valor_frete" id="valor_frete"> - </span>
                                                </div>
                                            </div>
                                            <div id="div_progressive_discount" class="p-0 mb-2 row justify-content-between progressive-discount-class" style="display:none">
                                                <input type="hidden" id="progressive_discount">
                                                <div class="text-start col-7">
                                                    <span class="subtotal">Desconto progressivo</span>
                                                </div>
                                                <div class="text-end col-5">
                                                    <span class="subtotal discount-span progressive-discount-span-text"></span>
                                                </div>
                                            </div>
                                            <div class="p-0 mb-1 row justify-content-between d-none automatic-discount">
                                                <div class="text-start col-6">
                                                    <span class="text-automatic-discount subtotal">Desconto cartão</span>
                                                </div>
                                                <div class="text-end col-6">
                                                    <span class="subtotal value-automatic-discount discount-span"> R$ 0 </span>
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="mt-0">
                                        <div class="cp-total" style="position: relative">
                                            <div class="row justify-content-between total_container">
                                                <div class="text-start col-6">Total</div>
                                                <div class="text-end col-6">
                                                    R$&nbsp;
                                                    <span class="valor_total">{{ number_format($checkout->produto_valor, '2',',','.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 security d-lg-flex align-items-center justify-content-lg-center justify-content-center">
                        <button class="text-center btn btn-security">
                            <img src="/assets-check/images/safe.svg" alt="Green Shield Icon">
                            &nbsp;Ambiente seguro
                        </button>
                    </div>
                    <div id="depoimento-visual-list" class="py-2">
                            @foreach($checkout->depoimentos as $depoimento)
                                <div class="d-lg-block" style="">
                                    <div class="mb-0 card card-bg depoimento-container" style="border-bottom: 1px solid rgb(231, 231, 231)background:{{$checkout->checkout_color_card ?? "#ffffff"}}">
                                        <div class="card-body ">
                                            <div class="row no-gutters">
                                                <div class="col-8 d-flex">
                                                    <img class="rounded-circle preview-image" style="object-fit: cover;width:48px!important;height:48px!important;" src="{{ $depoimento->avatar }}">
                                                    <span class="pt-1 pl-2 text-ccblack d-inline-block preview-nome" style="width: 80%;">{{ $depoimento->nome }}</span>
                                                </div>
                                                <div class="pt-1 text-end d-none d-md-flex col-4 align-items-center justify-content-end">
                                                    <div class="stars d-flex" style="color: #f8ce1c">
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mt-2 text-start review-desc col review-description preview-depoimento">{{ $depoimento->depoimento }}</div>
                                            </div>
                                            <div class="mt-4 d-flex d-md-none align-items-center justify-content-start">
                                                <div class="stars d-flex" style="color: #f8ce1c">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div id="footer" class="w-full p-2 pt-4 mb-0 text-center card card-bg d-flex flex-column align-items-center footer-cfx" style="background:{{$checkout->checkout_color_card ?? '#ffffff'}}">
        <p class="mb-2 text-white">Formas de pagamento</p>
        <div class="d-flex" style="gap: 0.5rem;">
            <i class="fa-brands fa-pix text-white" style="font-size:44px;"></i>
            <!-- <img src="https://pay.ment-deveuperdeu.shop/assets/img/card-pix.svg" width="44"> -->
            </div>
            <p class="mt-4 text-white">© {{ date('Y') }} All rights reserved.</p>
            <div class="mt-4 security d-none sm-flex">
                <button class="btn btn-security">
                <img src="/assets-check/images/safe.svg" alt="Green Shield Icon"> Ambiente seguro </button>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8/inputmask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="https://cdn.jsdelivr.net/gh/efipay/js-payment-token-efi/dist/payment-token-efi-umd.min.js"></script>

    <script>
        window.tempo = Number("{{ $checkout->checkout_timer_tempo }}");
        window.produto_valor = parseFloat("{{ $checkout->produto_valor }}");
        window.checkout_color_default = "{{ $checkout->checkout_color_default }}";
        window.bumps = @Json($checkout->bumps);
        window.checkout_id = Number("{{ $checkout->id }}");
      	window.endereco_active = "{{ $checkout->produto_tipo }}" === 'fisico';
        window.meta_active = Boolean("{{ $meta_active }}");
        window.taxas_cartao_fixa = Number("{{ $regefi->card_tx_fixed ?? 0 }}");
        window.taxas_cartao_porcentagem = Number("{{ $regefi->card_tx_percent ?? 0 }}");
        window.redirect_tankyou = "{{$checkout->url_pagina_vendas}}";
        console.log(window.meta_active)
      //console.log(window.endereco_active);
    </script>
   
    <script>
    function metaAddToCart(){
        fbq('track', 'AddToCart');
    }

    </script>
    @if($efi)
        @if(env('EFI_ENV') == 'production')
            <script type='text/javascript'>var s=document.createElement('script');s.type='text/javascript';var v=parseInt(Math.random()*1000000);s.src='https://cobrancas.api.efipay.com.br/v1/cdn/{{$identificar_conta}}/'+v;s.async=false;s.id='{{$identificar_conta}}';if(!document.getElementById('{{$identificar_conta}}')){document.getElementsByTagName('head')[0].appendChild(s);};$gn={validForm:true,processed:false,done:{},ready:function(fn){$gn.done=fn;}};</script>
        @else
            <script type='text/javascript'>var s=document.createElement('script');s.type='text/javascript';var v=parseInt(Math.random()*1000000);s.src='https://cobrancas-h.api.efipay.com.br/v1/cdn/{{$identificar_conta}}/'+v;s.async=false;s.id='{{$identificar_conta}}';if(!document.getElementById('{{$identificar_conta}}')){document.getElementsByTagName('head')[0].appendChild(s);};$gn={validForm:true,processed:false,done:{},ready:function(fn){$gn.done=fn;}};</script>
        @endif
        <script>
            EfiPay.CreditCard.debugger(true);
                $gn.ready(function (checkout) {
                    window.checkoutefi = checkout;
             });
        </script>
    @endif
  <script type="text/javascript" src="{{ asset('assets-checkout/js/checkout.js'.'?ver='.uniqid()) }}"></script>
    </body>
</html>



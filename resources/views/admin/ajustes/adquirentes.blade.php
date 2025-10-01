<x-app-layout :route="'[ADMIN] Ajustes de adquirentes'">

    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-center">
                <div style="display:flex;align-items:center;justify-content:flex-start;"
                    class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5 glassmorphism-page-title">Ajuste de adquirentes</h1>
                </div>
            </div>

            <!-- Start::row-0 -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.default') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <label for="access_token" class="form-label">Adquirente Padrão Global</label>
                                        <select
                                            class="glassmorphism-form-control @error('access_token') is-invalid @enderror"
                                            name="adquirente" value="{{ $default }}" required>
                                            @foreach($adquirentes as $adquirente)
                                            <option value="{{ $adquirente->referencia }}"
                                                {{ $default == $adquirente->referencia ? 'selected' : '' }}>
                                                @php
                                                $nomeFormatado = $adquirente->adquirente;
                                                switch(strtolower($adquirente->adquirente)) {
                                                case 'cashtime':
                                                $nomeFormatado = 'Cash Time';
                                                break;
                                                case 'sixxpayments':
                                                $nomeFormatado = 'Sixx Payments';
                                                break;
                                                case 'mercadopago':
                                                $nomeFormatado = 'Mercado Pago';
                                                break;
                                                case 'efi':
                                                $nomeFormatado = 'EFI';
                                                break;
                                                case 'xgate':
                                                $nomeFormatado = 'XGate';
                                                break;
                                                case 'witetec':
                                                $nomeFormatado = 'WiteTec';
                                                break;
                                                case 'pixup':
                                                $nomeFormatado = 'PixUP BR';
                                                break;
                                                case 'bspay':
                                                $nomeFormatado = 'BSPay';
                                                break;
                                                case 'woovi':
                                                $nomeFormatado = 'Woovi';
                                                break;
                                                }
                                                @endphp
                                                {{ $nomeFormatado }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">
                                            Esta será a adquirente usada por usuários que não têm uma adquirente
                                            específica configurada.
                                        </small>
                                        @error('access_token')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar Padrão Global</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gerenciamento de Adquirentes -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="card-header">
                            <h5 class="card-title">🎛️ Gerenciar Adquirentes</h5>
                            <p class="text-muted mb-0">Ative/desative adquirentes individuais. Usuários podem usar
                                qualquer adquirente ativa.</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($adquirentes as $adquirente)
                                <div class="col-md-6 col-lg-2 mb-4">
                                    <div
                                        class="card glassmorphism-switch-card {{ $adquirente->status ? 'border-success' : 'border-secondary' }}">
                                        <div class="card-body p-4">
                                            <div class="text-center mb-3">
                                                <h5 class="card-title-large mb-3">
                                                    @php
                                                    $nomeFormatado = $adquirente->adquirente;
                                                    switch(strtolower($adquirente->adquirente)) {
                                                    case 'cashtime':
                                                    $nomeFormatado = 'Cash Time';
                                                    break;
                                                    case 'sixxpayments':
                                                    $nomeFormatado = 'Sixx Payments';
                                                    break;
                                                    case 'mercadopago':
                                                    $nomeFormatado = 'Mercado Pago';
                                                    break;
                                                    case 'efi':
                                                    $nomeFormatado = 'EFI';
                                                    break;
                                                    case 'xgate':
                                                    $nomeFormatado = 'XGate';
                                                    break;
                                                    case 'witetec':
                                                    $nomeFormatado = 'WiteTec';
                                                    break;
                                                    case 'pixup':
                                                    $nomeFormatado = 'PixUP BR';
                                                    break;
                                                    case 'bspay':
                                                    $nomeFormatado = 'BSPay';
                                                    break;
                                                    case 'woovi':
                                                    $nomeFormatado = 'Woovi';
                                                    break;
                                                    }
                                                    @endphp
                                                    {{ $nomeFormatado }}
                                                </h5>
                                                <div class="d-flex flex-column gap-2 align-items-center">
                                                    @if($adquirente->is_default)
                                                    <span class="badge badge-default">
                                                        <i class="fa-solid fa-crown me-1"></i>PADRÃO
                                                    </span>
                                                    @endif
                                                    <span
                                                        class="badge badge-large {{ $adquirente->status ? 'bg-success' : 'bg-secondary' }}">
                                                        <i
                                                            class="fa-solid {{ $adquirente->status ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                                        {{ $adquirente->status ? 'ATIVA' : 'INATIVA' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <p class="text-muted text-center mb-3">
                                                <strong>{{ $adquirente->referencia }}</strong>
                                            </p>

                                            <form method="POST" action="{{ route('admin.adquirentes.toggle') }}"
                                                class="d-inline">
                                                @csrf
                                                <input type="hidden" name="adquirente"
                                                    value="{{ $adquirente->referencia }}">
                                                <button type="submit"
                                                    class="glassmorphism-btn btn-sm {{ $adquirente->status ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                                    <i
                                                        class="fa-solid {{ $adquirente->status ? 'fa-toggle-off' : 'fa-toggle-on' }} me-1"></i>
                                                    {{ $adquirente->status ? 'Desativar' : 'Ativar' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Cashtime
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.cashtime') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="secret" class="form-label">Chave Secreta</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('secret') is-invalid @enderror"
                                                    name="secret" value="{{ $cashtime->secret }}" required>
                                                @error('secret')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                                <input type="number" step="0.01"
                                                    class="glassmorphism-form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                                    name="taxa_pix_cash_in" value="{{ $cashtime->taxa_pix_cash_in }}"
                                                    required>
                                                @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                                <input type="number" step="0.01"
                                                    class="glassmorphism-form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                                    name="taxa_pix_cash_out" value="{{ $cashtime->taxa_pix_cash_out }}"
                                                    required>
                                                @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start::row-4 -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Mercado Pago
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.mercadopago') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="access_token" class="form-label">Access Token</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control mercadopago-access-token-input @error('access_token') is-invalid @enderror"
                                                    name="access_token" value="{{ $mercadopago->access_token }}"
                                                    required>
                                                @error('access_token')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                PagarMe
                                <br />
                                <small>Registrar o webhook no painel da pagar.me: <span
                                        class="text-warning">{{env('APP_URL')}}/pagarme/webhook</span></small>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.pagarme') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="secret" class="form-label">Chave Secreta</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('secret') is-invalid @enderror"
                                                    name="secret" value="{{ $pagarme->secret ?? null }}" required>
                                                @error('secret')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="taxa_pix_cash_in" class="form-label">Taxa (PIX-IN)</label>
                                                <input type="number" step="0.01"
                                                    class="glassmorphism-form-control @error('taxa_pix_cash_in') is-invalid @enderror"
                                                    name="taxa_pix_cash_in"
                                                    value="{{ $pagarme->taxa_pix_cash_in ?? 0 }}" required>
                                                @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="taxa_pix_cash_out" class="form-label">Taxa (PIX-OUT)</label>
                                                <input type="number" step="0.01"
                                                    class="glassmorphism-form-control @error('taxa_pix_cash_out') is-invalid @enderror"
                                                    name="taxa_pix_cash_out"
                                                    value="{{ $pagarme->taxa_pix_cash_out ?? 0 }}" required>
                                                @error('taxa_pix_cash_in')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title d-flex align-items-center justify-content-between">
                                <span>Efí</span>
                                <div>
                                    <form method="POST" action="{{ route('admin.adquirentes.efi.regitrar') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="glassmorphism-btn">Registrar Webhooks</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.efi') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12 mb-3 mt-3 pb-1"
                                        style="border-bottom: 1px solid rgba(27, 27, 27, 0.47);">
                                        <h3 class="fs-5">Gerais&nbsp;<small class="text-warning">(Chaves)</small></h3>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="client_id" class="form-label">Client ID</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('client_id') is-invalid @enderror"
                                                    name="client_id" value="{{ $efi->client_id }}">
                                                @error('client_id')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="client_secret" class="form-label">Client Secret</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('client_secret') is-invalid @enderror"
                                                    name="client_secret" value="{{ $efi->client_secret }}">
                                                @error('client_secret')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="chave_pix" class="form-label">Chave PIX</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('chave_pix') is-invalid @enderror"
                                                    name="chave_pix" value="{{ $efi->chave_pix }}">
                                                @error('chave_pix')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="identificador_conta" class="form-label">Identificador de
                                                    conta</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('identificador_conta') is-invalid @enderror"
                                                    name="identificador_conta" value="{{ $efi->identificador_conta }}">
                                                @error('identificador_conta')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12">
                                        <label for="cert" class="form-label">Certificado</label>
                                        <input id="input-cert" type="file"
                                            class="filepond glassmorphism-form-control @error('cert') is-invalid @enderror"
                                            name="cert" hidden value="{{ $efi->cert }}">
                                        <br />
                                        <button id="bt-add-cert" type="button" class="w-100 glassmorphism-btn"
                                            onclick="adcionarCertificado()">Selecionar certificado</button>
                                        <small style="display: none;" class="text-success">Certificado
                                            selecionado</small>
                                        @error('cert')
                                        <span style="color: red;">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-12 mb-3 mt-5 pb-1"
                                        style="border-bottom: 1px solid rgba(27, 27, 27, 0.47);">
                                        <h3 class="fs-5">Boleto&nbsp;<small class="text-warning">(Taxas e
                                                prazos)</small></h3>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="billet_tx_percent" class="form-label">Taxa (%)</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('billet_tx_percent') is-invalid @enderror"
                                                    name="billet_tx_percent" value="{{ $efi->billet_tx_percent }}">
                                                @error('billet_tx_percent')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="billet_tx_fixed" class="form-label">Taxa fixa (R$)</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('billet_tx_fixed') is-invalid @enderror"
                                                    name="billet_tx_fixed" value="{{ $efi->billet_tx_fixed }}">
                                                @error('billet_tx_fixed')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="billet_days_availability" class="form-label">Tempo de
                                                    liberação</label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">D+</span>
                                                    <input type="number" min="1" class="glassmorphism-form-control"
                                                        placeholder="ex.: 21" name="billet_days_availability"
                                                        id="billet_days_availability"
                                                        value="{{ $efi->billet_days_availability }}"
                                                        aria-label="Dias para liberar"
                                                        aria-describedby="billet_days_availability">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3 mt-5 pb-1"
                                        style="border-bottom: 1px solid rgba(27, 27, 27, 0.47);">
                                        <h3 class="fs-5">Cartão&nbsp;<small class="text-warning">(Taxas e
                                                prazos)</small></h3>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="card_tx_percent" class="form-label">Taxa (%)</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('card_tx_percent') is-invalid @enderror"
                                                    name="card_tx_percent" value="{{ $efi->card_tx_percent }}">
                                                @error('card_tx_percent')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="card_tx_fixed" class="form-label">Taxa fixa (R$)</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('card_tx_fixed') is-invalid @enderror"
                                                    name="card_tx_fixed" value="{{ $efi->card_tx_fixed }}">
                                                @error('card_tx_fixed')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="card_days_availability" class="form-label">Tempo de
                                                    liberação</label>
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text" id="basic-addon1">D+</span>
                                                    <input type="number" min="1" class="glassmorphism-form-control"
                                                        placeholder="ex.: 21" name="card_days_availability"
                                                        id="card_days_availability"
                                                        value="{{ $efi->card_days_availability }}"
                                                        aria-label="Dias para liberar"
                                                        aria-describedby="card_days_availability">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                XGate
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.xgate') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('email') is-invalid @enderror"
                                                    name="email" value="{{ $xgate->email ?? null }}" required>
                                                @error('email')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="password" class="form-label">Senha</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('password') is-invalid @enderror"
                                                    name="password" value="{{ $xgate->password ?? null }}" required>
                                                @error('password')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Witetec
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.witetec') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="api_token" class="form-label">API Key</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control witetec-api-key-input @error('api_token') is-invalid @enderror"
                                                    name="api_token" value="{{ $witetec->api_token ?? null }}" required>
                                                @error('api_token')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pixup -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Pixup
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.pixup') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="client_id" class="form-label">Client ID</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('client_id') is-invalid @enderror"
                                                    name="client_id" value="{{ $pixup->client_id ?? null }}" required>
                                                @error('client_id')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="url" class="form-label">URL</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control pixup-url-input @error('url') is-invalid @enderror"
                                                    name="url"
                                                    value="{{ $pixup->url ?? 'https://api.pixupbr.com/v2/' }}" required>
                                                @error('url')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="client_secret" class="form-label">Client Secret</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control pixup-client-secret-input @error('client_secret') is-invalid @enderror"
                                                    name="client_secret" value="{{ $pixup->client_secret ?? null }}"
                                                    required>
                                                @error('client_secret')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BSPay -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                BSPay
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.bspay') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="client_id" class="form-label">Client ID</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('client_id') is-invalid @enderror"
                                                    name="client_id" value="{{ $bspay->client_id ?? null }}" required>
                                                @error('client_id')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="url" class="form-label">URL</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control pixup-url-input @error('url') is-invalid @enderror"
                                                    name="url" value="{{ $bspay->url ?? 'https://api.bspay.co/v2/' }}"
                                                    required>
                                                @error('url')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="client_secret" class="form-label">Client Secret</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control pixup-client-secret-input @error('client_secret') is-invalid @enderror"
                                                    name="client_secret" value="{{ $bspay->client_secret ?? null }}"
                                                    required>
                                                @error('client_secret')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 text-end">
                                        <button type="submit" class="glassmorphism-btn">Alterar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Woovi -->
            <div class="row mb-3">
                <div class="col-xl-12">
                    <div class="card glassmorphism-card">
                        <div class="bg-transparent card-header justify-content-between">
                            <div class="card-title">
                                Woovi
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.adquirentes.woovi') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('POST')
                                <div class="row gy-2">
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="app_id" class="form-label">App ID</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('app_id') is-invalid @enderror"
                                                    name="app_id" value="{{ $woovi->app_id ?? null }}" required>
                                                @error('app_id')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="url" class="form-label">URL</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('url') is-invalid @enderror"
                                                    name="url" value="{{ $woovi->url ?? 'https://api.woovi.com' }}"
                                                    required>
                                                @error('url')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="api_key" class="form-label">API Key</label>
                                                <input type="text"
                                                    class="glassmorphism-form-control @error('api_key') is-invalid @enderror"
                                                    name="api_key" value="{{ $woovi->api_key ?? null }}" required>
                                                @error('api_key')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="webhook_secret" class="form-label">Webhook Secret</label>
                                                <div style="display: flex; gap: 10px; align-items: center;">
                                                    <input type="text" id="webhook_secret_input"
                                                        class="glassmorphism-form-control @error('webhook_secret') is-invalid @enderror"
                                                        name="webhook_secret"
                                                        value="{{ $woovi->webhook_secret ?? null }}" style="flex: 1;">
                                                    <button type="button" id="generate_webhook_token"
                                                        class="glassmorphism-btn"
                                                        style="padding: 8px 16px; font-size: 12px; white-space: nowrap;">
                                                        <i class="fa fa-refresh"></i> Gerar Token
                                                    </button>
                                                </div>
                                                <small class="text-muted">Clique em "Gerar Token" para criar um novo
                                                    webhook secret automaticamente</small>
                                                @error('webhook_secret')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="api_url" class="form-label">URL da API</label>
                                                <input type="text" id="api_url_display"
                                                    class="glassmorphism-form-control"
                                                    value="{{ $woovi->getApiUrl() ?? 'https://api.woovi.com' }}"
                                                    readonly
                                                    style="background-color: #2a2a2a; color: #00ff88; font-weight: bold;">
                                                <small class="text-muted">URL atual baseada na configuração de
                                                    Sandbox/Produção</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-row-aligned">
                                            <div class="form-group">
                                                <label for="sandbox" class="form-label">Sandbox</label>
                                                <select
                                                    class="glassmorphism-form-control @error('sandbox') is-invalid @enderror"
                                                    name="sandbox">
                                                    <option value="0"
                                                        {{ ($woovi->sandbox ?? false) ? '' : 'selected' }}>Produção
                                                    </option>
                                                    <option value="1"
                                                        {{ ($woovi->sandbox ?? false) ? 'selected' : '' }}>Sandbox
                                                    </option>
                                                </select>
                                                @error('sandbox')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="status" class="form-label">Status</label>
                                                <select
                                                    class="glassmorphism-form-control @error('status') is-invalid @enderror"
                                                    name="status">
                                                    <option value="0" {{ ($woovi->status ?? false) ? '' : 'selected' }}>
                                                        Inativo</option>
                                                    <option value="1" {{ ($woovi->status ?? false) ? 'selected' : '' }}>
                                                        Ativo</option>
                                                </select>
                                                @error('status')
                                                <span style="color: red;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="glassmorphism-btn">Salvar Configurações</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function adcionarCertificado() {
        document.getElementById('input-cert').click();
    }
    document.getElementById('input-cert').addEventListener('change', function(ev) {
        ev.preventDefault();
        console.log(ev.target.value)
        document.getElementById('bt-add-cert').innerText = "Alterar Certificado";
        document.querySelector('#container-btn-cert small').style.display = 'block';
    })

    // Função para atualizar a URL da API dinamicamente
    function updateApiUrl() {
        const sandboxSelect = document.querySelector('select[name="sandbox"]');
        const urlDisplay = document.getElementById('api_url_display');

        if (sandboxSelect && urlDisplay) {
            const isSandbox = sandboxSelect.value === '1';
            const apiUrl = isSandbox ? 'https://api.woovi-sandbox.com' : 'https://api.woovi.com';

            urlDisplay.value = apiUrl;

            // Adicionar efeito visual de mudança
            urlDisplay.style.transition = 'all 0.3s ease';
            urlDisplay.style.backgroundColor = '#1a4d1a';
            setTimeout(() => {
                urlDisplay.style.backgroundColor = '#2a2a2a';
            }, 300);
        }
    }

    // Função para gerar token do webhook
    function generateWebhookToken() {
        const tokenLength = 32;
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let token = '';

        for (let i = 0; i < tokenLength; i++) {
            token += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        const webhookInput = document.getElementById('webhook_secret_input');
        const generateBtn = document.getElementById('generate_webhook_token');

        if (webhookInput && generateBtn) {
            // Desabilitar botão durante o processo
            generateBtn.disabled = true;
            generateBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Salvando...';

            // Fazer chamada AJAX para salvar no banco
            fetch('{{ route("admin.adquirentes.woovi.webhook-token") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        webhook_secret: token
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        webhookInput.value = token;

                        // Efeito visual de geração
                        webhookInput.style.transition = 'all 0.3s ease';
                        webhookInput.style.backgroundColor = '#1a4d1a';
                        webhookInput.style.borderColor = '#00ff88';

                        setTimeout(() => {
                            webhookInput.style.backgroundColor = '';
                            webhookInput.style.borderColor = '';
                        }, 1000);

                        // Mostrar notificação de sucesso
                        showNotification('Token gerado e salvo com sucesso!', 'success');
                    } else {
                        showNotification('Erro ao salvar token: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showNotification('Erro ao salvar token no banco de dados', 'error');
                })
                .finally(() => {
                    // Reabilitar botão
                    generateBtn.disabled = false;
                    generateBtn.innerHTML = '<i class="fa fa-refresh"></i> Gerar Token';
                });
        }
    }

    // Função para mostrar notificações
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        let bgColor, textColor, borderColor;

        switch (type) {
            case 'success':
                bgColor = '#1a4d1a';
                textColor = '#00ff88';
                borderColor = '#00ff88';
                break;
            case 'error':
                bgColor = '#4d1a1a';
                textColor = '#ff4444';
                borderColor = '#ff4444';
                break;
            default:
                bgColor = '#2a2a2a';
                textColor = '#ffffff';
                borderColor = '#555';
        }

        notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                background: ${bgColor};
                color: ${textColor};
                border: 1px solid ${borderColor};
                border-radius: 5px;
                z-index: 9999;
                font-size: 14px;
                box-shadow: 0 4px 6px rgba(0,0,0,0.3);
                transition: all 0.3s ease;
            `;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Adicionar listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Listener para o dropdown de sandbox
        const sandboxSelect = document.querySelector('select[name="sandbox"]');
        if (sandboxSelect) {
            sandboxSelect.addEventListener('change', updateApiUrl);
        }

        // Listener para o botão de gerar token
        const generateBtn = document.getElementById('generate_webhook_token');
        if (generateBtn) {
            generateBtn.addEventListener('click', generateWebhookToken);
        }
    });
    /* document.addEventListener('DOMContentLoaded', function() {
        const inputElement = document.querySelector('input[type="file"]');
        const pond = FilePond.create(inputElement, {
            labelIdle: `Arraste e solte aqui ou <span class="filepond--label-action py-5">clique aqui</span>`,
            allowImagePreview: false, // Desativa a pré-visualização
            allowMultiple: false,
            stylePanelAspectRatio: null, // Evita forçar proporção
        });
    }); */
    </script>
</x-app-layout>
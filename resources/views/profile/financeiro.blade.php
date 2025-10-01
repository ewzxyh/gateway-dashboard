@php
use App\Helpers\Helper;
Helper::calculaSaldoLiquido(auth()->user()->user_id);
$setting = Helper::getSetting();

auth()->user()->fresh();

@endphp
<x-app-layout :route="'Financeiro'">



    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="mb-4 row justify-content-between align-items-center">
                <div class="col-12 col-md-8">
                    <h1 class="mb-0 display-5">Dashboard Financeiro</h1>
                    <p class="text-muted mt-2">Gerencie seus saldos, depósitos e saques</p>
                </div>
            </div>

            <!-- Saldo Cards -->
            <div class="row">
                <div class="mb-4 col-xxl-6 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 140px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">R$ {{ number_format(auth()->user()->saldo + auth()->user()->valor_saque_pendente ?? 0, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Saldo Total</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color">
                                    <i class="fas fa-wallet text-white"></i>
                                </div>
                            </div>
                            <div class="adobe-text-muted">
                                <div class="d-inline-flex align-items-center">
                                    <i class="fa-solid fa-chart-line text-success"></i>&nbsp;
                                    <div class="caption text-success fw-500 me-2">Total:</div>
                                    <div class="caption text-success">Disponível + Pendente</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4 col-xxl-6 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 140px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-info adobe-text fw-bold">R$ {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Disponível para Saque</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color">
                                    <i class="fas fa-hand-holding-usd text-white"></i>
                                </div>
                            </div>
                            <div class="adobe-text-muted">
                                <div class="d-inline-flex align-items-center">
                                    <i class="fa-solid fa-money-bill-wave text-info"></i>&nbsp;
                                    <div class="caption text-info fw-500 me-2">Líquido:</div>
                                    <div class="caption text-info">Para Saque</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Action Cards -->
            <div class="row">
                <div class="mb-4 col-xxl-6 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 140px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-primary adobe-text fw-bold">Depósito</div>
                                    <div class="adobe-text-muted">Adicionar Saldo</div>
                                </div>
                                <div class="text-white icon-circle card-color">
                                    <i class="fas fa-plus text-white"></i>
                                </div>
                            </div>
                            <div class="adobe-text-muted">
                                <div class="d-inline-flex align-items-center">
                                    <i class="fa-solid fa-pix text-primary"></i>&nbsp;
                                    <div class="caption text-primary fw-500 me-2">PIX:</div>
                                    <div class="caption text-primary">Rápido e Seguro</div>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 mt-3"
                                data-bs-toggle="modal"
                                data-bs-target="#addsaldo">
                                <i class="fas fa-credit-card me-2"></i>
                                Depositar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-6 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 140px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">Saque</div>
                                    <div class="adobe-text-muted">Retirar Saldo</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color">
                                    <i class="fas fa-arrow-up text-white"></i>
                                </div>
                            </div>
                            <div class="adobe-text-muted">
                                <div class="d-inline-flex align-items-center">
                                    <i class="fa-solid fa-money-bill-wave text-success"></i>&nbsp;
                                    <div class="caption text-success fw-500 me-2">PIX/Crypto:</div>
                                    <div class="caption text-success">Seguro</div>
                                </div>
                            </div>
                            <button class="btn btn-primary w-100 mt-3"
                                data-bs-toggle="modal"
                                data-bs-target="{{ is_null($networks) ? '#saquepix' : '#modalSelMoeda' }}"
                                data-saldo="{{ $saldoliquido }}">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Sacar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Adicionar Saldo -->
            <div class="modal fade" id="addsaldo" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title" id="mail-ComposeLabel">Adicionar Saldo</h6>
                            <button id="btnDepositar" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                        </div>
                        <form id="depositForm" method="POST">
                            @csrf
                            <div class="px-4 modal-body">
                                <div class="row gy-2">
                                    <!-- Campo de valor -->
                                    <div class="col-xl-12">
                                        <label for="valor" class="form-label">Valor</label>
                                        <input type="number" step="0.01" class="form-control" id="valor_deposito" name="valor" placeholder="Valor" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                                <button id="btn-depositar" type="submit" class="btn btn-primary">Depositar</button>
                            </div>
                        </form>

                        <div id="data-qrcode" class="mt-5 text-center" style="width:100%;display: none;">
                            <img id="pix-qr-code" width="200" height="200" class="mb-3" />
                            <input id="pix-copia-e-cola" style="background: transparent; width: 80%;" class="mb-3" readonly />
                            <button class="mb-3 btn btn-primary" onclick="copiarTexto()">Copiar chave</button>
                        </div>
                    </div>
                </div>
            </div>


                                <!-- Modal -->
                                <div class="modal fade" id="modalSelMoeda" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="modalSelMoedaLabel">Selecione a modalidade</h6>
                                                <button id="btn-c-mod" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="px-4 modal-body">
                                                <div class="row gy-2 d-flex align-items-center justify-content-center">
                                                    <div class="col-6 d-flex align-items-center justify-content-end">
                                                        <button
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#saquepix"
                                                            class="btn btn-outline-primary"
                                                            onclick="document.getElementById('btn-c-mod').click()"
                                                            style="display:flex;align-items:center;justify-content:center;width: 120px;height:120px">
                                                            PIX
                                                        </button>
                                                    </div>
                                                    <div class="col-6 d-flex align-items-center justify-content-start">
                                                        <button
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#saquecrypto"
                                                            class="btn btn-outline-primary"
                                                            onclick="document.getElementById('btn-c-mod').click()"
                                                            class="btn btn-outline-primary"
                                                            style="display:flex;align-items:center;justify-content:center;width: 120px;height:120px">
                                                            CRYPTO
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <!-- Modal PIX-->
                                <div class="modal fade" id="saquepix" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content adobe-glass-card">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">SAQUE PIX</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                                            </div>
                                            <form id="saqueForm" method="POST">
                                                @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-2">

                                                        <!-- Verificação de saldo baixo -->
                                                        @if($saldoBaixo)
                                                        <div class="mt-4 alert alert-danger">
                                                            <strong>Saldo muito baixo para realizar um saque.</strong>
                                                        </div>
                                                        @endif

                                                        @if($retiradasPendentes)
                                                        <div class="mt-4 alert alert-warning">
                                                            <strong>Já existe um saque em processamento. Aguarde a conclusão.</strong>
                                                        </div>
                                                        @endif

                                                        <!-- Exibição do saldo disponível -->
                                                        <div class="mt-4 alert alert-info">
                                                            <ul>
                                                                <li><strong>DISPONÍVEL PARA SAQUE:</strong> R$: {{ number_format(auth()->user()->saldo, 2, ',', '.') }}</li>
                                                            </ul>
                                                        </div>

                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input type="number" step="0.01" class="form-control"
                                                                id="valor"
                                                                max="{{ auth()->user()->saldo }}"
                                                                name="valor"
                                                                placeholder="Valor"
                                                                required>
                                                            <!-- <div id="valorLiquido" class="mt-2 text-success"></div> -->
                                                            <div id="containerValorLiquido" style="display: none;" class="mt-4 alert alert-success">
                                                                <ul>
                                                                    <li><strong id="valorLiquido"></strong></li>
                                                                </ul>
                                                            </div>
                                                            <div id="valorError" class="mt-2 text-danger" style="display: none;">Saldo insuficiente para o valor solicitado.</div>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label class="form-label">Tipo de Chave</label>
                                                            <select id="tipo_chave" name="tipo_chave" type="text" class="form-control">
                                                                <option value="cpf">CPF</option>
                                                                <option value="cnpj">CNPJ</option>
                                                                <option value="email">EMAIL</option>
                                                                <option value="telefone">TELEFONE</option>
                                                                <option value="aleatoria">ALEATÓRIA</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-xl-12">
                                                            <label for="chave" class="form-label">Chave PIX:</label>
                                                            <input type="text" class="form-control" id="chave" name="chave" placeholder="Chave" required>
                                                        </div>

                                                        <!-- Campo de PIN (só aparece se o usuário tiver PIN ativo) -->
                                                        @if(auth()->user()->pin_active)
                                                        <div class="col-xl-12">
                                                            <label for="pin_saque" class="form-label">
                                                                <i class="fas fa-lock text-warning"></i> PIN de Segurança
                                                                <small class="text-muted">(Obrigatório para saques)</small>
                                                            </label>
                                                            <input type="password" 
                                                                   class="form-control" 
                                                                   id="pin_saque" 
                                                                   name="pin_saque" 
                                                                   placeholder="Digite seu PIN de 6 dígitos" 
                                                                   maxlength="6"
                                                                   pattern="[0-9]{6}"
                                                                   required>
                                                            <div class="form-text text-muted">
                                                                <i class="fas fa-info-circle"></i> Seu PIN é necessário para confirmar esta operação de saque.
                                                            </div>
                                                        </div>
                                                        @endif

                                                        <!-- Campo oculto para o ID do usuário -->
                                                        <input type="hidden" id="user_id" name="user_id" value="{{ $email }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button
                                                        type="button"
                                                        class="btn btn-light"
                                                        data-bs-dismiss="modal"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalSelMoeda">Voltar</button>
                                                    <button id="btnSolicitarSaque" type="submit" class="btn btn-primary" {{ $retiradasPendentes >= 1 ? 'disabled' : '' }}>Solicitar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>


                                <!-- Modal Crypto-->
                                <div class="modal fade" id="saquecrypto" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-md modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h6 class="modal-title" id="mail-ComposeLabel">SAQUE CRYPTO</h6>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                                            </div>
                                            <form id="saqueFormCrypto" method="POST">
                                                @csrf
                                                <div class="px-4 modal-body">
                                                    <div class="row gy-2">

                                                        <!-- Verificação de saldo baixo -->
                                                        @if($saldoBaixo)
                                                        <div class="mt-4 alert alert-danger">
                                                            <strong>Saldo muito baixo para realizar um saque.</strong>
                                                        </div>
                                                        @endif

                                                        @if($retiradasPendentes)
                                                        <div class="mt-4 alert alert-warning">
                                                            <strong>Já existe um saque em processamento. Aguarde a conclusão.</strong>
                                                        </div>
                                                        @endif

                                                        <!-- Exibição do saldo disponível -->
                                                        <div class="mt-4 alert alert-info">
                                                            <ul>
                                                                <li><strong>DISPONÍVEL PARA SAQUE:</strong> R$: {{ number_format(auth()->user()->saldo, 2, ',', '.') }}</li>
                                                            </ul>
                                                        </div>

                                                        <!-- Campo de valor -->
                                                        <div class="col-xl-12">
                                                            <label for="valor" class="form-label">Valor</label>
                                                            <input
                                                                class="form-control"
                                                                type="number"
                                                                step="0.01"
                                                                id="valor_saque"
                                                                max="{{ auth()->user()->saldo }}"
                                                                name="valor_saque"
                                                                placeholder="Valor"
                                                                required>
                                                            <!-- <div id="valorLiquido" class="mt-2 text-success"></div> -->
                                                            <div id="containerValorLiquido" style="display: none;" class="mt-4 alert alert-success">
                                                                <ul>
                                                                    <li><strong id="valorLiquido"></strong></li>
                                                                </ul>
                                                            </div>
                                                            <div id="valorError" class="mt-2 text-danger" style="display: none;">Saldo insuficiente para o valor solicitado.</div>
                                                        </div>
                                                        @if(!is_null($networks))
                                                        <div class="multistep">

                                                            {{-- STEP 1: Escolha da Network --}}
                                                            <div id="step-networks" class="row g-3">
                                                                @foreach($networks as $network)
                                                                <div class="col-md-3">
                                                                    <div class="card card-network text-center h-100"
                                                                        data-id="{{ $network['_id'] }}"
                                                                        data-data="{{ json_encode($network) }}">
                                                                        <div class="card-body">
                                                                            <h6 class="fw-bold">{{ $network['name'] }}</h6>
                                                                            <p class="text-muted m-0">{{ $network['chain'] }} • {{ $network['symbol'] }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>

                                                            {{-- STEP 2: Escolha da Moeda --}}
                                                            <div id="step-cryptos" class="d-none">
                                                                <h6 class="fw-semibold mb-3">Escolha a moeda:</h6>
                                                                <ul class="list-group" id="crypto-list"></ul>
                                                                <div class="mt-3">
                                                                    <button type="button" class="btn btn-outline-secondary" id="btn-back" sele>Voltar</button>
                                                                </div>
                                                            </div>

                                                            {{-- STEP 3: Endereço + Solicitar --}}
                                                            <div id="step-final" class="d-none">
                                                                <div class="mb-3">
                                                                    <label for="wallet" class="form-label">Endereço da carteira</label>
                                                                    <input type="text" class="form-control" id="wallet" placeholder="Cole seu endereço aqui">
                                                                </div>

                                                                <!-- Campo de PIN (só aparece se o usuário tiver PIN ativo) -->
                                                                @if(auth()->user()->pin_active)
                                                                <div class="mb-3">
                                                                    <label for="pin_saque_crypto" class="form-label">
                                                                        <i class="fas fa-lock text-warning"></i> PIN de Segurança
                                                                        <small class="text-muted">(Obrigatório para saques)</small>
                                                                    </label>
                                                                    <input type="password" 
                                                                           class="form-control" 
                                                                           id="pin_saque_crypto" 
                                                                           name="pin_saque_crypto" 
                                                                           placeholder="Digite seu PIN de 6 dígitos" 
                                                                           maxlength="6"
                                                                           pattern="[0-9]{6}"
                                                                           required>
                                                                    <div class="form-text text-muted">
                                                                        <i class="fas fa-info-circle"></i> Seu PIN é necessário para confirmar esta operação de saque.
                                                                    </div>
                                                                </div>
                                                                @endif

                                                                <input id="tipo_chave" name="tipo_chave" type="text" class="form-control" value="crypto" hidden />
                                                                <input type="hidden" id="user_id" name="user_id" value="{{ $email }}">

                                                                <div class="d-flex justify-content-between">
                                                                    <button type="button" class="btn btn-outline-secondary" id="btn-back-crypto">Voltar</button>
                                                                    <button type="submit" class="btn btn-success" onclick="this.disabled;">Solicitar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações sobre Saque -->
                            <div class="mt-4 p-3 rounded-3" style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.2);">
                                <h6 class="text-warning mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Informações de Saque</h6>
                                <div class="small text-muted">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Taxa de saque:</span>
                                        <span class="fw-bold">{{ $setting->taxa_cash_out_padrao."%" }} {{ $setting->taxa_fixa_padrao_cashout > 0 ? '+ R$ '.number_format($setting->taxa_fixa_padrao_cashout, '2', ',', '.') : '' }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Limite PF:</span>
                                        @if(isset($setting->limite_saque_mensal) && (float)$setting->limite_saque_mensal > 0)
                                        <span class="fw-bold">R$ {{ number_format($setting->limite_saque_mensal, '2', ',', '.') }} /mês</span>
                                        @else
                                        <span class="fw-bold text-success">Sem limite</span>
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Limite PJ:</span>
                                        <span class="fw-bold text-success">Sem limite</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->
        </div>
    </div>

    <script>
        window.selectedNetwork = null;
        window.selectedCrypto = null;
        document.addEventListener("DOMContentLoaded", function() {
            let selectedNetwork = null;
            let selectedCrypto = null;
            const stepNetworks = document.getElementById("step-networks");
            const stepCryptos = document.getElementById("step-cryptos");
            const stepFinal = document.getElementById("step-final");
            const cryptoList = document.getElementById("crypto-list");

            // Seleção de network
            document.querySelectorAll(".card-network").forEach(card => {
                card.addEventListener("click", function() {
                    // Resetar seleção
                    document.querySelectorAll(".card-network").forEach(c => c.classList.remove("active"));
                    this.classList.add("active");

                    selectedNetwork = this.dataset.id;
                
                    // Carregar as cryptos dinamicamente
                    cryptoList.innerHTML = "";
                    let networkData = @json($networks);
                    let selNet = networkData.find(n => n._id === selectedNetwork);
                    let cryptos = selNet.cryptocurrencies;
                    window.selectedNetwork = selNet;

                    cryptos.forEach(c => {
                        let li = document.createElement("li");
                        li.className = "list-group-item list-group-item-action crypto-item";
                        li.dataset.crypto = c.cryptocurrency._id;
                        li.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <span><strong>${c.cryptocurrency.name}</strong> <small>(${c.cryptocurrency.symbol})</small></span>
                            <span class="badge bg-light text-dark border">Min: ${c.minWithdraw}</span>
                        </div>`;
                        cryptoList.appendChild(li);

                        li.addEventListener("click", function() {
                            document.querySelectorAll(".crypto-item").forEach(el => el.classList.remove("active"));
                            this.classList.add("active");
                            selectedCrypto = this.dataset.crypto;
                            window.selectedCrypto = c;

                            // Avança para step final
                            stepCryptos.classList.add("d-none");
                            stepFinal.classList.remove("d-none");
                        });
                    });

                    // Mostrar Step 2
                    stepNetworks.classList.add("d-none");
                    stepCryptos.classList.remove("d-none");
                });
            });

            // Botão Voltar
            document.getElementById("btn-back").addEventListener("click", () => {
                stepCryptos.classList.add("d-none");
                stepNetworks.classList.remove("d-none");
                selectedNetwork = null;
            });

            document.getElementById("btn-back-crypto").addEventListener("click", () => {
                stepFinal.classList.add("d-none");
                stepCryptos.classList.remove("d-none");
                selectedCrypto = null;
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let selectedNetwork = null;
            let selectedCrypto = null;

            // Seleção de network
            document.querySelectorAll(".btn-select-network").forEach(btn => {
                btn.addEventListener("click", function() {
                    let id = this.dataset.id;

                    // Resetar todos os cards
                    document.querySelectorAll(".card-select").forEach(card => {
                        card.classList.remove("border-primary", "shadow");
                    });

                    // Marcar card selecionado
                    let selectedCard = this.closest(".card-select");
                    selectedCard.classList.add("border-primary", "shadow");

                    // Esconder todas as listas
                    document.querySelectorAll(".crypto-wrapper").forEach(list => {
                        list.classList.add("d-none");
                    });

                    // Mostrar apenas a lista da network escolhida
                    document.getElementById("crypto-list-" + id).classList.remove("d-none");

                    selectedNetwork = id;
                    selectedCrypto = null;
                    console.log("Network selecionada:", selectedNetwork);
                });
            });

            // Seleção de crypto
            document.querySelectorAll(".crypto-item").forEach(item => {
                item.addEventListener("click", function() {
                    let networkId = this.dataset.network;

                    // Resetar todos os itens da lista dessa network
                    document.querySelectorAll("#crypto-list-" + networkId + " .crypto-item")
                        .forEach(el => el.classList.remove("active"));

                    // Marcar item selecionado
                    this.classList.add("active");

                    selectedCrypto = this.dataset.crypto;
                    console.log("Crypto selecionada:", selectedCrypto);
                });
            });
        });
    </script>

    <script>
        function copiarTexto() {
            var input = document.getElementById("pix-copia-e-cola");
            input.select();
            document.execCommand("copy");
            showToast("success", "Chave Pix copiada!");
        }
    </script>

    <script>
        document.getElementById('depositForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let btnDepositar = document.getElementById('btn-depositar');
            btnDepositar.setAttribute('disabled', true);
            var paymentCode;
            var transactionId;
            generateQRCode();
            async function generateQRCode() {
                var name = "{{ auth()->user()->name }}";
                var cpf = "{{ auth()->user()->cpf_cnpj }}";
                var email = "{{ auth()->user()->email }}";
                var amount = document.getElementById('valor_deposito').value;
                
                // Verificar se o CPF está preenchido
                if (!cpf || cpf.trim() === '') {
                    alert('Erro: CPF/CNPJ não preenchido. Por favor, complete seu perfil antes de fazer depósitos.');
                    btnDepositar.removeAttribute('disabled');
                    return;
                }
                var apiUrl = "{{ env('APP_URL') }}/api/wallet/deposit/payment";
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var phone = "{{ auth()->user()->telefone }}";
                var payload = {
                    "token": token,
                    "secret": secret,
                    "amount": parseFloat(amount),
                    "debtor_name": name,
                    "email": email,
                    "debtor_document_number": cpf,
                    "phone": phone,
                    "method_pay": "pix",
                    "postback": "web"
                };
                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();
                    console.log('resposta completa:', data);
                    
                    // Verificar se a resposta tem a estrutura esperada
                    let chargeData = null;
                    if (data.charge && data.charge.qrCode) {
                        // Estrutura direta (compatibilidade com Woovi)
                        console.log('Usando estrutura direta (Woovi)');
                        chargeData = data.charge;
                    } else if (data.data && data.data.charge && data.data.charge.qrCode) {
                        // Estrutura aninhada (compatibilidade com Pixup)
                        console.log('Usando estrutura aninhada (Pixup)');
                        chargeData = data.data.charge;
                    } else if (data.data && data.data.qrcode) {
                        // Estrutura alternativa (fallback)
                        console.log('Usando estrutura alternativa (fallback)');
                        chargeData = {
                            id: data.data.idTransaction,
                            qrCode: data.data.qr_code_image_url,
                            brCode: data.data.qrcode
                        };
                    }
                    
                    console.log('chargeData processado:', chargeData);
                    
                    if (chargeData && chargeData.qrCode) {
                        console.log('Processando dados do PIX...');
                        paymentCode = chargeData.brCode; // Usar brCode (código copia e cola)
                        paymentCodeBase64 = chargeData.qrCode;
                        transactionId = chargeData.id; // Ajustado para pegar idTransaction

                        console.log('Dados do PIX:', {
                            paymentCode: paymentCode,
                            paymentCodeBase64: paymentCodeBase64,
                            transactionId: transactionId
                        });

                        // Adiciona o paymentCode ao texto da div
                        // Usar a URL da imagem do QR code, não o código PIX diretamente
                        let qrImageUrl = null;
                        if (data.qr_code_image_url) {
                            // Estrutura direta (nível raiz)
                            qrImageUrl = data.qr_code_image_url;
                            console.log('Usando URL da imagem do QR code (nível raiz):', qrImageUrl);
                        } else if (data.data && data.data.qr_code_image_url) {
                            // Estrutura aninhada
                            qrImageUrl = data.data.qr_code_image_url;
                            console.log('Usando URL da imagem do QR code (aninhada):', qrImageUrl);
                        } else {
                            // Fallback: gerar QR code usando biblioteca externa
                            qrImageUrl = 'https://quickchart.io/qr?text=' + encodeURIComponent(paymentCode);
                            console.log('Usando fallback para gerar QR code:', qrImageUrl);
                        }
                        
                        document.getElementById('pix-qr-code').src = qrImageUrl;
                        document.getElementById('pix-copia-e-cola').value = paymentCode;
                        
                        console.log('QR code configurado:', {
                            src: qrImageUrl,
                            paymentCode: paymentCode
                        });

                        console.log('Ocultando formulário e exibindo modal...');
                        document.getElementById('depositForm').style.display = 'none';
                        let pixcontainer = document.getElementById('data-qrcode');
                        pixcontainer.style.display = 'flex';
                        pixcontainer.style.flexDirection = "column";
                        pixcontainer.style.alignItems = "center";
                        pixcontainer.style.justifyContent = "center";
                        pixcontainer.style.gap = 5;

                        console.log('Modal PIX exibido com sucesso!');
                        // Inicia a verificação do pagamento a cada 2 segundos
                        setInterval(checkPaymentStatus, 5000);
                    } else {
                        btnDepositar.setAttribute('disabled', false);
                        console.error("Erro na solicitação:", data.message);
                    }
                } catch (error) {
                    btnDepositar.setAttribute('disabled', false);
                    console.error("Erro na solicitação:", error);
                }
            }

            async function checkPaymentStatus() {
                var apiUrl = "{{env('APP_URL')}}/api/status";
                var payload = {
                    "idTransaction": transactionId
                };

                try {
                    const response = await fetch(apiUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (data.status === "PAID_OUT") {
                        clearInterval(checkPaymentStatus); // Para a verificação quando o pagamento for confirmado

                        showToast('success', "Saldo adcionado com sucesso!")
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000)
                    } else if (data.status === "WAITING_FOR_APPROVAL") {
                        console.log("Aguardando aprovação...");
                    }
                } catch (error) {
                    console.error("Erro na verificação do pagamento:", error);
                }
            }
        })
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let btnSolicitarSaque = document.getElementById("btnSolicitarSaque");
            let inputValor = document.getElementById("valor");
            let inputChave = document.getElementById("chave");
            let inputPin = document.getElementById("pin_saque");
            let valorLiquidoInput = document.getElementById("valorLiquido");
            let containerValorLiquido = document.getElementById("containerValorLiquido");

            // Desabilita o botão inicialmente
            btnSolicitarSaque.setAttribute("disabled", true);
            btnSolicitarSaque.classList.add("btn-secondary");
            
            // Validação inicial
            validarCampos();

            function validarCampos() {
                let valorPreenchido = inputValor.value && parseFloat(inputValor.value) > 0;
                let chavePreenchida = inputChave.value.trim().length > 0;
                let pinPreenchido = true; // Assume que não precisa de PIN por padrão
                
                // Se o campo PIN existe (usuário tem PIN ativo), verifica se está preenchido
                if (inputPin) {
                    pinPreenchido = inputPin.value.trim().length === 6 && /^[0-9]{6}$/.test(inputPin.value);
                }

                // Habilitar botão se todos os campos obrigatórios estiverem preenchidos
                if (valorPreenchido && chavePreenchida && pinPreenchido) {
                    btnSolicitarSaque.removeAttribute("disabled");
                    btnSolicitarSaque.classList.remove("btn-secondary");
                    btnSolicitarSaque.classList.add("btn-primary");
                } else {
                    btnSolicitarSaque.setAttribute("disabled", true);
                    btnSolicitarSaque.classList.remove("btn-primary");
                    btnSolicitarSaque.classList.add("btn-secondary");
                }
            }

            function calcularValorLiquido() {
                let maxValue = parseFloat(inputValor.max) || 0;
                let currentValue = parseFloat(inputValor.value) || 0;

                if (currentValue > maxValue) {
                    inputValor.value = maxValue;
                    currentValue = maxValue;
                }

                if (currentValue <= 0 || isNaN(currentValue)) {
                    containerValorLiquido.style.display = "none";
                    return;
                }

                // NOVA LÓGICA: Cliente sempre recebe o valor solicitado
                let valorLiquido = currentValue; // Cliente recebe exatamente o que solicitou
                
                // Calcular taxa total para exibição
                let tx_cash_out = parseFloat("{{ $setting->taxa_cash_out_padrao }}") || 0;
                let taxa_fixa_padrao_cash_out = parseFloat("{{ $taxa_fixa_padrao_cash_out }}") || 0;
                let taxa_fixa_pix = parseFloat("{{ $setting->taxa_fixa_pix ?? 0.00 }}") || 0;
                let taxa_percentual = (currentValue * tx_cash_out / 100);
                let taxa_principal = Math.max(taxa_percentual, parseFloat("{{ $setting->baseline }}") || 0);
                let taxa_total = taxa_principal + taxa_fixa_pix;
                let valor_total_descontar = currentValue + taxa_total;

                // Verificar se há saldo suficiente
                if (valor_total_descontar > maxValue) {
                    containerValorLiquido.style.display = "block";
                    valorLiquidoInput.innerHTML = '<span style="color: red;">Saldo insuficiente! Necessário: ' + 
                        valor_total_descontar.toLocaleString("pt-BR", {
                            style: "currency",
                            currency: "BRL"
                        }) + '</span>';
                } else {
                    containerValorLiquido.style.display = "block";
                    let saldoRestante = maxValue - valor_total_descontar;
                    valorLiquidoInput.innerHTML = "Valor líquido a receber: " +
                        valorLiquido.toLocaleString("pt-BR", {
                            style: "currency",
                            currency: "BRL"
                        }) + " (Taxa: " + taxa_total.toLocaleString("pt-BR", {
                            style: "currency",
                            currency: "BRL"
                        }) + ") - Saldo restante: " + saldoRestante.toLocaleString("pt-BR", {
                            style: "currency",
                            currency: "BRL"
                        });
                }
            }

            inputValor.addEventListener("input", function() {
                calcularValorLiquido();
                validarCampos();
            });

            inputChave.addEventListener("input", validarCampos);
            
            // Adicionar listener para o campo PIN se existir
            if (inputPin) {
                inputPin.addEventListener("input", function() {
                    // Limitar a 6 dígitos
                    if (this.value.length > 6) {
                        this.value = this.value.slice(0, 6);
                    }
                    // Permitir apenas números
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    // Feedback visual
                    if (this.value.length === 6) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (this.value.length > 0) {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                    
                    validarCampos();
                });
                
                inputPin.addEventListener("paste", function(e) {
                    e.preventDefault();
                    let paste = (e.clipboardData || window.clipboardData).getData('text');
                    let numbers = paste.replace(/[^0-9]/g, '').slice(0, 6);
                    this.value = numbers;
                    
                    // Feedback visual
                    if (this.value.length === 6) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else if (this.value.length > 0) {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-valid', 'is-invalid');
                    }
                    
                    validarCampos();
                });
                
                inputPin.addEventListener("focus", function() {
                    this.classList.remove('is-valid', 'is-invalid');
                });
            }
        });
    </script>

    <script>
        document.getElementById('saqueForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var saldo = "{{ $saldoliquido }}"; // Corrigido para usar PHP para obter o saldo
            var valor = parseFloat(document.getElementById('valor').value);
            var valorError = document.getElementById('valorError');
            var pinAtivo = {{ auth()->user()->pin_active ? 'true' : 'false' }};
            var inputPin = document.getElementById('pin_saque');

            // Verifica se o usuário tem PIN ativo
            if (pinAtivo) {
                // Se tem PIN ativo, verifica se foi digitado
                if (!inputPin || !inputPin.value || inputPin.value.trim().length !== 6 || !/^[0-9]{6}$/.test(inputPin.value)) {
                    showToast('error', "Por favor, digite seu PIN de 6 dígitos no campo 'PIN de Segurança (Obrigatório para saques)'");
                    return;
                }
            }

            // Verifica se o saldo é zero ou se o valor solicitado é maior que o saldo
            if (saldo <= 0) {
                showToast('warning', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
                return;
            } else if (valor > saldo) {
                showToast('success', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
                return;
            }

            requestPayment();
            async function requestPayment() {
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var amount = document.getElementById('valor').value;
                var pixKey = document.getElementById('chave').value;
                var pixKeyType = document.getElementById('tipo_chave').value;
                var apiUrl = "{{env('APP_URL')}}/api/pixout";

                if (parseFloat(valor) > parseFloat(saldo)) {
                    valor = saldo;
                }

                var payload = {
                    token,
                    secret,
                    amount,
                    pixKey,
                    pixKeyType,
                    baasPostbackUrl: 'web'
                }

                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.id) {
                    showToast('success', "Saque solicitado com sucesso.")
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000)
                } else {
                    showToast('warning', data.message);
                }
            }



        });




        document.getElementById('saqueFormCrypto').addEventListener('submit', function(event) {
            event.preventDefault();
            var saldo = "{{ $saldoliquido }}"; // Corrigido para usar PHP para obter o saldo
            var valor = parseFloat(document.getElementById('valor').value);
            var valorError = document.getElementById('valorError');
            var pinAtivo = {{ auth()->user()->pin_active ? 'true' : 'false' }};

            // Verifica se o usuário tem PIN ativo
            if (!pinAtivo) {
                showToast('error', "PIN obrigatório para saques! Acesse Meu Perfil > Segurança para configurar.");
                event.preventDefault();
                return;
            }

            // Verifica se o saldo é zero ou se o valor solicitado é maior que o saldo
            if (saldo <= 0) {
                showToast('warning', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
                return;
            } else if (valor > saldo) {
                showToast('success', "Saldo insuficiente!")
                event.preventDefault(); // Evita o envio do formulário
                return;
            }

            requestPaymentCrypto();
            async function requestPaymentCrypto() {
                var token = "{{ auth()->user()->chaves->token }}";
                var secret = "{{ auth()->user()->chaves->secret }}";
                var amount = document.getElementById('valor_saque').value;
                var pixKey = document.getElementById('wallet').value;
                var pixKeyType = "crypto";
                var apiUrl = "{{env('APP_URL')}}/api/pixout";

                if (parseFloat(valor) > parseFloat(saldo)) {
                    valor = saldo;
                }

                let blockchainNetwork = window.selectedNetwork;
                blockchainNetwork['cryptocurrencies'] = [];
                let cryptocurrency = window.selectedCrypto.cryptocurrency;

                var payload = {
                    token,
                    secret,
                    amount,
                    pixKey,
                    pixKeyType,
                    baasPostbackUrl: 'web',
                    blockchainNetwork,
                    cryptocurrency,
                }

                console.log(payload)
                const response = await fetch(apiUrl, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (data.id) {
                    showToast('success', "Saque solicitado com sucesso.")
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000)
                } else {
                    showToast('warning', data.message);
                }
            }



        });
    </script>

    <style>
        .card-network {
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
        }

        .card-network:hover {
            border-color: #dee2e6;
        }

        .card-network.active {
            border-color: #198754;
            /* Verde Bootstrap */
        }
    </style>

</x-app-layout>
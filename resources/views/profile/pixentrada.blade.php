<x-app-layout :route="'Relatório de entradas'">
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Start::page-header -->
            <div class="mb-3 md-mb-0 row">
                <div class="mb-3 md-mb-0 col col-12 col-lg-8 text-start">
                    <h1 class="display-5">Entradas</h1>
                </div>

                <div class="col col-12 col-lg-4 text-end">
                    <form method="GET" action="{{ route('profile.relatorio.pixentrada') }}" id="filtroForm">
                        <div class="row g-2">
                            <div class="col col-6">
                                <input type="search" class="form-control" id="buscar" name="buscar" placeholder="Buscar"
                                    value="{{ request('buscar') }}" autofocus>
                            </div>
                            <div class="col col-6">
                                <select id="periodoSelect" class="bg-transparent form-select" name="periodo"
                                    onchange="submitPeriod()" required>
                                    <option value="hoje" {{ request('periodo') == 'hoje' ? 'selected' : '' }}>Hoje
                                    </option>
                                    <option value="ontem" {{ request('periodo') == 'ontem' ? 'selected' : '' }}>Ontem
                                    </option>
                                    <option value="7dias" {{ request('periodo') == '7dias' ? 'selected' : '' }}>Último 7
                                        dias</option>
                                    <option value="30dias" {{ request('periodo') == '30dias' ? 'selected' : '' }}>Último
                                        30 dias</option>
                                    <option value="tudo" {{ request('periodo') == 'tudo' ? 'selected' : '' }}>Sempre
                                    </option>
                                    <option value="personalizado">Personalizado</option>
                                    {{-- @if(isset(request('periodo')) && str_contains(':', request('periodo')))
                                            <option value="{{ request('periodo') }}">{{ explode(':', request('periodo'))[0] - explode(':', request('periodo'))[1] }}
                                    </option>
                                    @endif --}}
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Start:: row-1 -->
            <div class="row">

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 adobe-text fw-bold">{{ (clone $transactions)->count() }}</div>
                                    <div class="adobe-text-muted">Transações</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i class="fa-solid fa-sync"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 adobe-text fw-bold">R$
                                        {{ number_format((clone $transactions)->sum('amount'), '2',',','.') }}</div>
                                    <div class="adobe-text-muted">Faturamento</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i
                                        class="fa-solid fa-arrow-down-short-wide"></i></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 adobe-text fw-bold">R$
                                        {{ number_format((clone $transactions)->sum('deposito_liquido'), '2',',','.') }}
                                    </div>
                                    <div class="adobe-text-muted">Valor liquido</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i
                                        class="fa-solid fa-dollar-sign"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4" style="min-height: 114px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    @php
                                    $ticketMedio = 0;
                                    $totalTransacoesFiltradas = (clone $transactions)->count();

                                    if ($totalTransacoesFiltradas > 0) {
                                    $somaDepositoLiquido = (clone $transactions)->sum('deposito_liquido');
                                    $ticketMedio = $somaDepositoLiquido / $totalTransacoesFiltradas;
                                    }
                                    @endphp
                                    <div class="display-5 adobe-text fw-bold"> R$
                                        {{ number_format($ticketMedio, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Ticket médio</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color"><i
                                        class="fa-solid fa-receipt"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->



            <div class="row">
                <div class="col-xl-12">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="table-responsive">
                                <table id="table-pix-entradas" class="table text-nowrap ">
                                    <thead>
                                        <tr>
                                            @if($settings->relatorio_entradas_mostrar_meio ?? true)
                                            <th scope="col">Meio</th>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_transacao_id ?? true)
                                            <th scope="col">Transação ID</th>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_valor ?? true)
                                            <th scope="col">Valor</th>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_valor_liquido ?? true)
                                            <th scope="col">Valor Líquido</th>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_nome ?? true)
                                            <th scope="col">Nome</th>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_documento ?? true)
                                            <th scope="col">Documento</th>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_status ?? true)
                                            <th scope="col">Status</th>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_data ?? true)
                                            <th scope="col">Data</th>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_taxa ?? true)
                                            <th scope="col">Taxa</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($transactions->count() > 0)
                                        @foreach ($transactions as $transaction)
                                        <tr class="transaction-row" style="cursor: pointer;"
                                            data-transaction-id="{{ $transaction->id }}">
                                            @if($settings->relatorio_entradas_mostrar_meio ?? true)
                                            <td>
                                                @switch($transaction->method)
                                                @case('pix')
                                                <i class="fa-brands fa-pix" style="color:rgb(0, 167, 130)"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="PIX"></i>
                                                @break
                                                @case('billet')
                                                <i class="fa-solid fa-barcode" style="color:black"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Boleto"></i>
                                                @break
                                                @case('card')
                                                <i class="fa-solid fa-credit-card" style="color:rgb(255, 154, 2)"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Cartão"></i>
                                                @break
                                                @endswitch
                                            </td>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_transacao_id ?? true)
                                            <td>{{ $transaction->idTransaction }}</td>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_valor ?? true)
                                            <td>{{ "R$ ".number_format($transaction->amount, '2',',','.') }}</td>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_valor_liquido ?? true)
                                            <td>{{ "R$ ".number_format($transaction->deposito_liquido, '2',',','.') }}</td>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_nome ?? true)
                                            <td>{{ $transaction->client_name }}</td>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_documento ?? true)
                                            <td>{{ $transaction->client_document }}</td>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_status ?? true)
                                            <td>
                                                @switch($transaction->status)
                                                @case('PAID_OUT')
                                                <span
                                                    class="badge badge-sm bg-success gateway-badge-success">Aprovado</span>
                                                @break
                                                @case('WAITING_FOR_APPROVAL')
                                                <span class="badge badge-sm"
                                                    style="background-color: #ff8c00; color: white;">Pendente</span>
                                                @break
                                                @case('PENDING')
                                                <span
                                                    class="badge badge-sm bg-warning gateway-badge-warning">Pendente</span>
                                                @break
                                                @case('CANCELLED')
                                                <span
                                                    class="badge badge-sm bg-danger gateway-badge-danger">Cancelado</span>
                                                @break
                                                @case('RELEASE')
                                                <span class="badge badge-sm bg-info gateway-badge-info"
                                                    data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                    data-bs-content="Será liberado em {{ \Carbon\Carbon::parse($transaction->date)->addDays($transaction->days_availability ?? 21)->format('d/m/Y \à\s H:i:s') }}"
                                                    data-bs-placement="top">A Liberar</span>
                                                @break
                                                @default
                                                <span
                                                    class="badge badge-sm bg-secondary">{{ $transaction->status }}</span>
                                                @endswitch
                                            </td>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_data ?? true)
                                            <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            @endif
                                            @if($settings->relatorio_entradas_mostrar_taxa ?? true)
                                            <td>
                                                R$
                                                {{ number_format((float)$transaction->amount - (float)$transaction->deposito_liquido, '2', ',', '.') }}
                                            </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr class="no-data-row">
                                            <td colspan="9" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fa fa-inbox fa-2x mb-2"></i>
                                                    <p class="mb-0">Nenhuma transação encontrada para o período
                                                        selecionado.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="dateRangeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="border-0 modal-content rounded-4">
                <div class="justify-center p-4 pl-5 modal-body align-center">
                    <h5 class="mb-4 fw-semibold">Selecione o período</h5>

                    <div class="row">
                        <div class="mb-3 text-center col-md-6">
                            <strong class="mb-2 d-block">Data de Início</strong>
                            <div class="d-flex justify-content-center" id="calendarInicio"></div>
                        </div>
                        <div class="text-center col-md-6">
                            <strong class="mb-2 d-block">Data de Fim</strong>
                            <div class="d-flex justify-content-center" id="calendarFim"></div>
                        </div>
                    </div>
                </div>
                <div class="gap-2 mt-4 modal-footer d-flex justify-content-end">
                    <button class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-success" id="btnAplicarDatas">Aplicar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> -->


    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById("periodoSelect");
        const buscar = document.getElementById("buscar");
        const form = document.getElementById("filtroForm");
        const modalEl = document.getElementById('dateRangeModal');
        const btnAplicar = document.getElementById("btnAplicarDatas");

        let dataInicioSelecionada = null;
        let dataFimSelecionada = null;

        function formatarDataBr(data) {
            const meses = ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'];
            const dia = String(data.getDate()).padStart(2, '0');
            const mes = meses[data.getMonth()];
            return `${dia} ${mes}`;
        }

        // Flatpickrs
        flatpickr("#calendarInicio", {
            inline: true,
            locale: "pt",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates) {
                dataInicioSelecionada = selectedDates[0];
            }
        });

        flatpickr("#calendarFim", {
            inline: true,
            locale: "pt",
            dateFormat: "Y-m-d",
            onChange: function(selectedDates) {
                dataFimSelecionada = selectedDates[0];
            }
        });

        // Abrir modal
        select.addEventListener("change", function() {
            if (select.value === "personalizado") {
                new bootstrap.Modal(modalEl).show();
            } else {
                form.submit();
            }
        });

        buscar.addEventListener('input', function() {
            setTimeout(() => {
                form.submit();
            }, 500);
        })

        // Aplicar datas
        btnAplicar.addEventListener("click", function() {
            if (dataInicioSelecionada && dataFimSelecionada) {
                const inicioStr = dataInicioSelecionada.toISOString().split("T")[0];
                const fimStr = dataFimSelecionada.toISOString().split("T")[0];
                const texto =
                    `${formatarDataBr(dataInicioSelecionada)} – ${formatarDataBr(dataFimSelecionada)}`;
                const valor = `${inicioStr}:${fimStr}`;

                // Remover opção personalizada anterior, se existir
                let opExistente = select.querySelector('option[data-personalizado]');
                if (opExistente) select.removeChild(opExistente);

                // Criar nova opção personalizada
                let op = document.createElement("option");
                op.value = valor;
                op.textContent = texto;
                op.setAttribute("data-personalizado", "1");
                select.appendChild(op);
                select.value = valor; // Define como selecionado

                // Fechar modal e submeter
                bootstrap.Modal.getInstance(modalEl).hide();
                form.submit();
            } else {
                alert("Selecione ambas as datas.");
            }
        });

        // Restaurar valor da URL, se existir
        const urlParams = new URLSearchParams(window.location.search);
        const periodo = urlParams.get('periodo');

        if (periodo && select) {
            if (periodo.includes(':')) {
                const [inicioStr, fimStr] = periodo.split(':');
                const inicioDate = new Date(inicioStr);
                const fimDate = new Date(fimStr);
                dataInicioSelecionada = inicioDate;
                dataFimSelecionada = fimDate;

                const texto = `${formatarDataBr(inicioDate)} – ${formatarDataBr(fimDate)}`;
                let op = document.createElement("option");
                op.value = periodo;
                op.textContent = texto;
                op.setAttribute("data-personalizado", "1");

                select.appendChild(op);
                select.value = periodo; // Seleciona corretamente
            } else {
                const optionToSelect = Array.from(select.options).find(opt => opt.value === periodo);
                if (optionToSelect) {
                    optionToSelect.selected = true;
                }
            }
        }
    });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Verificar se a tabela existe e tem dados válidos
        var table = document.getElementById('table-pix-entradas');
        if (table) {
            // Verificar se há linhas de dados válidas (não apenas a linha de "nenhuma transação")
            var tbody = table.querySelector('tbody');
            var dataRows = tbody ? tbody.querySelectorAll('tr:not(.no-data-row)') : [];

            // Verificar se há pelo menos uma linha com 9 colunas (dados reais)
            var hasValidData = false;
            for (var i = 0; i < dataRows.length; i++) {
                var cells = dataRows[i].querySelectorAll('td');
                if (cells.length === 9) {
                    hasValidData = true;
                    break;
                }
            }

            if (hasValidData) {
                $("#table-pix-entradas").DataTable({
                    responsive: true,
                    info: false,
                    ordering: false,
                    searching: false,
                    lengthChange: false,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                    },
                    dom: '<"top"f>rt<"bottom"p><"clear">',
                    initComplete: function() {
                        // Muda o placeholder do input de busca
                        $('#table-pix-entradas_filter input[type="search"]').attr('placeholder',
                            'Pesquisar');
                    }
                });
            } else {
                console.log('Tabela sem dados válidos, DataTable não inicializado');
            }
        }

        // Adicionar evento de clique nas linhas da tabela
        $('#table-pix-entradas tbody').on('click', 'tr.transaction-row', function(e) {
            e.preventDefault();
            e.stopPropagation();

            var transactionId = $(this).data('transaction-id');
            if (transactionId) {
                carregarDetalhesTransacao(transactionId);
            }
        });
    });

    // Função para carregar detalhes da transação
    function carregarDetalhesTransacao(transactionId) {
        $.ajax({
            url: '/relatorio/entradas/detalhes/' + transactionId,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    preencherModal(response.data);
                    $('#detalhesModal').modal('show');
                } else {
                    alert('Erro ao carregar detalhes: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Erro ao carregar detalhes:', xhr);
                alert('Erro ao carregar detalhes da transação');
            }
        });
    }

    // Função para preencher o modal com os dados
    function preencherModal(dados) {
        // Barra de resumo
        $('#modal-valor').text('R$ ' + dados.valor);
        $('#modal-valor-liquido').text('R$ ' + dados.valor_liquido);
        $('#modal-data').text(dados.data);

        // Status
        var statusText = getStatusText(dados.status);
        var statusClass = getStatusClass(dados.status);
        $('#modal-status').removeClass().addClass('badge ' + statusClass).text(statusText);

        // Informações do cliente
        $('#modal-cliente').text(dados.cliente);
        $('#modal-email').text(dados.email);
        $('#modal-documento').text(dados.documento);
        $('#modal-id-transacao').text(dados.idTransaction);
        
        // Taxa
        $('#modal-taxa-valor').text('R$ ' + dados.taxa);

        // Dados do pagamento - Usando .text() para prevenir XSS
        if (dados.method === 'card') {
            var cardContainer = $('#modal-dados-pagamento');
            cardContainer.empty();

            // Bandeira
            var brandDiv = $('<div class="d-flex align-items-center mb-2"></div>');
            brandDiv.append('<i class="bi bi-credit-card me-2" style="color: #6c757d;"></i>');
            brandDiv.append('<span style="color: #6c757d; font-size: 0.9rem;">Bandeira:</span>');
            brandDiv.append('<span class="ms-2" style="color: #212529; font-weight: 500;"></span>');
            brandDiv.find('span:last').text(dados.method_details.brand || 'N/A');
            cardContainer.append(brandDiv);

            // Final do cartão
            var lastFourDiv = $('<div class="d-flex align-items-center mb-2"></div>');
            lastFourDiv.append('<i class="bi bi-hash me-2" style="color: #6c757d;"></i>');
            lastFourDiv.append('<span style="color: #6c757d; font-size: 0.9rem;">Final:</span>');
            lastFourDiv.append('<span class="ms-2" style="color: #212529; font-weight: 500;"></span>');
            lastFourDiv.find('span:last').text('****' + (dados.method_details.last_four || 'N/A'));
            cardContainer.append(lastFourDiv);

            // Expiração
            var expiryDiv = $('<div class="d-flex align-items-center mb-2"></div>');
            expiryDiv.append('<i class="bi bi-calendar me-2" style="color: #6c757d;"></i>');
            expiryDiv.append('<span style="color: #6c757d; font-size: 0.9rem;">Expira:</span>');
            expiryDiv.append('<span class="ms-2" style="color: #212529; font-weight: 500;"></span>');
            expiryDiv.find('span:last').text(dados.method_details.expiry || 'N/A');
            cardContainer.append(expiryDiv);
        } else if (dados.method === 'pix') {
            var pixContainer = $('#modal-dados-pagamento');
            pixContainer.empty();

            // Tipo PIX
            var typeDiv = $('<div class="d-flex align-items-center mb-2"></div>');
            typeDiv.append('<i class="bi bi-qr-code me-2" style="color: #6c757d;"></i>');
            typeDiv.append('<span style="color: #6c757d; font-size: 0.9rem;">Tipo:</span>');
            typeDiv.append('<span class="ms-2" style="color: #212529; font-weight: 500;"></span>');
            typeDiv.find('span:last').text(dados.method_details.description || 'PIX Instantâneo');
            pixContainer.append(typeDiv);

            // QR Code Image (se existir)
            if (dados.method_details.qr_code_image) {
                var qrDiv = $('<div class="text-center mt-3"></div>');
                var img = $('<img>').attr({
                    'src': dados.method_details.qr_code_image,
                    'alt': 'QR Code PIX',
                    'style': 'max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid #dee2e6;'
                });
                qrDiv.append(img);
                pixContainer.append(qrDiv);
            }
        } else if (dados.method === 'billet') {
            var billetContainer = $('#modal-dados-pagamento');
            billetContainer.empty();

            // Tipo Boleto
            var typeDiv = $('<div class="d-flex align-items-center mb-2"></div>');
            typeDiv.append('<i class="bi bi-receipt me-2" style="color: #6c757d;"></i>');
            typeDiv.append('<span style="color: #6c757d; font-size: 0.9rem;">Tipo:</span>');
            typeDiv.append('<span class="ms-2" style="color: #212529; font-weight: 500;"></span>');
            typeDiv.find('span:last').text(dados.method_details.description || 'Boleto Bancário');
            billetContainer.append(typeDiv);

            // Link do Boleto (se existir)
            if (dados.method_details.billet_url) {
                var linkDiv = $('<div class="d-flex align-items-center mb-2"></div>');
                linkDiv.append('<i class="bi bi-link-45deg me-2" style="color: #6c757d;"></i>');
                var link = $('<a href="#" target="_blank" class="btn btn-sm btn-outline-primary">Ver Boleto</a>');
                link.attr('href', dados.method_details.billet_url);
                linkDiv.append(link);
                billetContainer.append(linkDiv);
            }
        }

        // Configurar botões de ação
        $('#estornarBtn').data('transaction-id', dados.id);
        $('#cancelarBtn').data('transaction-id', dados.id);

        if (dados.status === 'PAID_OUT') {
            $('#estornarBtn').show();
        } else {
            $('#estornarBtn').hide();
        }

        if (['PENDING', 'PROCESSING'].includes(dados.status)) {
            $('#cancelarBtn').show();
        } else {
            $('#cancelarBtn').hide();
        }
    }

    // Funções auxiliares para status
    function getStatusText(status) {
        const statusMap = {
            'PENDING': 'PENDENTE',
            'PROCESSING': 'PROCESSANDO',
            'PAID_OUT': 'APROVADO',
            'WAITING_FOR_APPROVAL': 'PENDENTE',
            'REFUNDED': 'ESTORNADO',
            'CANCELLED': 'CANCELADO',
            'MEDIATION': 'EM MEDIAÇÃO'
        };
        return statusMap[status] || status;
    }

    function getStatusClass(status) {
        const classMap = {
            'PENDING': 'bg-warning',
            'PROCESSING': 'bg-info',
            'PAID_OUT': 'bg-success',
            'WAITING_FOR_APPROVAL': 'bg-orange-custom',
            'REFUNDED': 'bg-danger',
            'CANCELLED': 'bg-secondary',
            'MEDIATION': 'bg-warning'
        };
        return classMap[status] || 'bg-secondary';
    }

    // Eventos dos botões de ação
    $(document).on('click', '#estornarBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var transactionId = $(this).data('transaction-id');
        if (confirm('Tem certeza que deseja estornar esta transação? O valor será removido do seu saldo.')) {
            executarEstorno(transactionId);
        }
    });

    $(document).on('click', '#cancelarBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var transactionId = $(this).data('transaction-id');
        if (confirm('Tem certeza que deseja cancelar esta transação?')) {
            executarCancelamento(transactionId);
        }
    });

    // Função para executar estorno
    function executarEstorno(transactionId) {
        $.ajax({
            url: '/relatorio/entradas/estornar/' + transactionId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('Transação estornada com sucesso!');
                    $('#detalhesModal').modal('hide');
                    location.reload();
                } else {
                    alert('Erro: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Erro ao estornar:', xhr);
                alert('Erro ao estornar transação');
            }
        });
    }

    // Função para executar cancelamento
    function executarCancelamento(transactionId) {
        $.ajax({
            url: '/relatorio/entradas/cancelar/' + transactionId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert('Transação cancelada com sucesso!');
                    $('#detalhesModal').modal('hide');
                    location.reload();
                } else {
                    alert('Erro: ' + response.message);
                }
            },
            error: function(xhr) {
                console.error('Erro ao cancelar:', xhr);
                alert('Erro ao cancelar transação');
            }
        });
    }
    </script>

    <!-- Modal de Detalhes da Transação -->
    <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content"
                style="background: #ffffff; border: 1px solid #dee2e6; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
                <div class="modal-header" style="border-bottom: 1px solid #dee2e6; background: #f8f9fa;">
                    <h5 class="modal-title" id="detalhesModalLabel" style="color: #212529; font-weight: 600;">
                        <i class="bi bi-receipt me-2"></i>Detalhes da Transação
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body" style="color: #212529; background: #ffffff;">
                    <!-- Barra de Resumo -->
                    <div class="row mb-4">
                        @if($settings->relatorio_entradas_mostrar_valor ?? true)
                        <div class="col-md-3">
                            <div class="text-center p-3"
                                style="background: #f8f9fa; border-radius: 12px; border: 1px solid #dee2e6;">
                                <h6 class="mb-1" style="color: #6c757d; font-size: 0.8rem;">Valor</h6>
                                <h4 class="mb-0" id="modal-valor" style="color: #212529; font-weight: 600;">R$ 0,00</h4>
                            </div>
                        </div>
                        @endif
                        @if($settings->relatorio_entradas_mostrar_valor_liquido ?? true)
                        <div class="col-md-3">
                            <div class="text-center p-3"
                                style="background: #f8f9fa; border-radius: 12px; border: 1px solid #dee2e6;">
                                <h6 class="mb-1" style="color: #6c757d; font-size: 0.8rem;">Valor Líquido</h6>
                                <h4 class="mb-0" id="modal-valor-liquido" style="color: #212529; font-weight: 600;">R$
                                    0,00</h4>
                            </div>
                        </div>
                        @endif
                        @if($settings->relatorio_entradas_mostrar_status ?? true)
                        <div class="col-md-3">
                            <div class="text-center p-3"
                                style="background: #f8f9fa; border-radius: 12px; border: 1px solid #dee2e6;">
                                <h6 class="mb-1" style="color: #6c757d; font-size: 0.8rem;">Status</h6>
                                <span id="modal-status" class="badge bg-success">Aprovado</span>
                            </div>
                        </div>
                        @endif
                        @if($settings->relatorio_entradas_mostrar_data ?? true)
                        <div class="col-md-3">
                            <div class="text-center p-3"
                                style="background: #f8f9fa; border-radius: 12px; border: 1px solid #dee2e6;">
                                <h6 class="mb-1" style="color: #6c757d; font-size: 0.8rem;">Data</h6>
                                <h6 class="mb-0" id="modal-data" style="color: #212529; font-weight: 600;">01/01/2024
                                </h6>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Informações do Cliente -->
                    @if(($settings->relatorio_entradas_mostrar_nome ?? true) || ($settings->relatorio_entradas_mostrar_documento ?? true) || ($settings->relatorio_entradas_mostrar_transacao_id ?? true))
                    <div class="mb-4">
                        <h6 class="mb-3" style="color: #212529; font-weight: 600;">
                            <i class="bi bi-person me-2" style="color: #6c757d;"></i>Informações do Cliente
                        </h6>
                        <div class="row">
                            @if($settings->relatorio_entradas_mostrar_nome ?? true)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-person-circle me-2" style="color: #6c757d;"></i>
                                    <span style="color: #6c757d; font-size: 0.9rem;">Nome:</span>
                                    <span class="ms-2" id="modal-cliente"
                                        style="color: #212529; font-weight: 500;">N/A</span>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-envelope me-2" style="color: #6c757d;"></i>
                                    <span style="color: #6c757d; font-size: 0.9rem;">Email:</span>
                                    <span class="ms-2" id="modal-email"
                                        style="color: #212529; font-weight: 500;">N/A</span>
                                </div>
                            </div>
                            @if($settings->relatorio_entradas_mostrar_documento ?? true)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-card-text me-2" style="color: #6c757d;"></i>
                                    <span style="color: #6c757d; font-size: 0.9rem;">Documento:</span>
                                    <span class="ms-2" id="modal-documento"
                                        style="color: #212529; font-weight: 500;">N/A</span>
                                </div>
                            </div>
                            @endif
                            @if($settings->relatorio_entradas_mostrar_transacao_id ?? true)
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-hash me-2" style="color: #6c757d;"></i>
                                    <span style="color: #6c757d; font-size: 0.9rem;">ID Transação:</span>
                                    <span class="ms-2" id="modal-id-transacao"
                                        style="color: #212529; font-weight: 500;">N/A</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Dados do Pagamento -->
                    <div class="mb-4">
                        <h6 class="mb-3" style="color: #212529; font-weight: 600;">
                            <i class="bi bi-credit-card me-2" style="color: #6c757d;"></i>Dados do Pagamento
                        </h6>
                        <div id="modal-dados-pagamento"
                            style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6;">
                            <!-- Conteúdo será preenchido via JavaScript -->
                        </div>
                        @if($settings->relatorio_entradas_mostrar_taxa ?? true)
                        <div class="mt-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-percent me-2" style="color: #6c757d;"></i>
                                <span style="color: #6c757d; font-size: 0.9rem;">Taxa:</span>
                                <span class="ms-2" id="modal-taxa-valor"
                                    style="color: #212529; font-weight: 500;">R$ 0,00</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #dee2e6; background: #f8f9fa;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Fechar
                    </button>
                    <button type="button" class="btn btn-danger" id="estornarBtn" style="display: none;">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Estornar
                    </button>
                    <button type="button" class="btn btn-warning" id="cancelarBtn" style="display: none;">
                        <i class="bi bi-x-circle me-1"></i>Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
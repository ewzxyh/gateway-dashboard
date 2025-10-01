<x-app-layout :route="'[ADMIN] Entradas'">


    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-4 row justify-content-between align-items-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-chart-line me-3"
                            style="font-size: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        <h1 class="mb-0 display-5"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;">
                            Relatórios de Entradas</h1>
                    </div>
                    <p class="text-muted mt-2">Gerencie e monitore todas as transações de entrada do sistema</p>
                </div>
                <div class="col-12 col-md-4 d-flex justify-content-end align-items-center">
                </div>
            </div>

            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ $totalaprovadas }}</div>
                                    <div class="adobe-text-muted">Aprovadas (Total)</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i
                                        class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ $totalaprovadasHoje }}
                                    </div>
                                    <div class="adobe-text-muted">Aprovadas (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i
                                        class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ $totalaprovadasMes }}
                                    </div>
                                    <div class="adobe-text-muted">Aprovadas (Mês)</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i
                                        class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-warning adobe-text fw-bold">{{ $totalsolicitacoes }}
                                    </div>
                                    <div class="adobe-text-muted">Transações geral</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i
                                        class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->





            <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">
                                        {{ "R$ ".number_format($valorAprovadoTotal, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Aprovadas (Bruto)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i
                                        class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">
                                        {{ "R$ ".number_format($valorAprovadoHoje, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Aprovadas (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i
                                        class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">
                                        {{ "R$ ".number_format($valorAprovadoMes, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted">Aprovadas (Mês)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i
                                        class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-header px-4 py-3 border-bottom border-opacity-25">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-table me-3" style="color: #667eea; font-size: 1.2rem;"></i>
                                <h3 class="mb-0 adobe-text fw-bold">Relatório de Transações</h3>
                            </div>
                        </div>
                        <div class="adobe-card-body px-4 py-4">
                            <div class="table-responsive">
                                {{-- Only render the table if there are records to show --}}
                                @if ($cashOuts->isNotEmpty())
                                <table id="table-financeiro-entradas"
                                    class="table table-borderless adobe-table text-nowrap">
                                    <thead>
                                        <tr class="border-bottom border-opacity-25">
                                            <th scope="col" class="adobe-text fw-bold py-3">Meio</th>
                                            <th scope="col" class="adobe-text fw-bold py-3">User ID</th>
                                            <th scope="col" class="adobe-text fw-bold py-3">Transação ID</th>
                                            <th scope="col" class="adobe-text fw-bold py-3">Valor</th>
                                            <th scope="col" class="adobe-text fw-bold py-3">Valor Líquido</th>
                                            <th scope="col" class="adobe-text fw-bold py-3">Status</th>
                                            <th scope="col" class="adobe-text fw-bold py-3">Data</th>
                                            <th scope="col" class="adobe-text fw-bold py-3">Taxa</th>
                                            <th scope="col" class="adobe-text fw-bold py-3">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- Loop through the records --}}
                                        @foreach ($cashOuts as $cashOut)
                                        <tr class="transaction-row" style="cursor: pointer;"
                                            data-transaction-id="{{ $cashOut->id }}" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Clique para ver detalhes">
                                            <td class="py-3">
                                                @switch($cashOut->method)
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
                                            <td class="adobe-text-muted py-3">{{ $cashOut->user_id }}</td>
                                            <td class="adobe-text py-3">{{ $cashOut->idTransaction }}</td>
                                            <td class="adobe-text fw-bold py-3">
                                                {{ number_format($cashOut->amount, 2, ',', '.') }}</td>
                                            <td class="adobe-text fw-bold py-3">
                                                {{ number_format($cashOut->deposito_liquido, 2, ',', '.') }}</td>
                                            <td class="py-3">
                                                @switch($cashOut->status)
                                                @case('PAID_OUT')
                                                <span class="badge bg-success bg-opacity-75">Aprovado</span>
                                                @break
                                                @case('WAITING_FOR_APPROVAL')
                                                @case('PENDING')
                                                <span class="badge bg-warning bg-opacity-75">Pendente</span>
                                                @break
                                                @case('RELEASE')
                                                <span class="badge badge-sm bg-info bg-opacity-75 gateway-badge-info"
                                                    data-bs-toggle="popover" data-bs-trigger="hover focus"
                                                    data-bs-content="Será liberado em {{ \Carbon\Carbon::parse($cashOut->date)->addDays($cashOut->days_availability ?? 21)->format('d/m/Y \à\s H:i:s') }}"
                                                    data-bs-placement="top">A Liberar</span>
                                                @break
                                                @case('CANCELLED')
                                                <span class="badge bg-danger bg-opacity-75">Cancelado</span>
                                                @break
                                                @default
                                                <span
                                                    class="badge bg-secondary bg-opacity-75">{{ $cashOut->status }}</span>
                                                @endswitch
                                            </td>
                                            <td class="adobe-text-muted py-3">
                                                {{ \Carbon\Carbon::parse($cashOut->date)->format('d/m/Y \à\s H:i:s') }}
                                            </td>
                                            <td class="adobe-text fw-bold py-3">R$
                                                {{ number_format((float)$cashOut->amount - (float)$cashOut->deposito_liquido, '2', ',', '.') }}
                                            </td>
                                            <td class="py-3" onclick="event.stopPropagation();">
                                                <select
                                                    class="form-select form-select-sm status-select glassmorphism-toggle"
                                                    data-id="{{ $cashOut->id }}" data-type="entrada">
                                                    <option value="PAID_OUT"
                                                        {{ $cashOut->status == 'PAID_OUT' ? 'selected' : '' }}>Aprovado
                                                    </option>
                                                    <option value="WAITING_FOR_APPROVAL"
                                                        {{ $cashOut->status == 'WAITING_FOR_APPROVAL' ? 'selected' : '' }}>
                                                        Pendente</option>
                                                    <option value="RELEASE"
                                                        {{ $cashOut->status == 'RELEASE' ? 'selected' : '' }}>A Liberar
                                                    </option>
                                                    <option value="CANCELLED"
                                                        {{ $cashOut->status == 'CANCELLED' ? 'selected' : '' }}>
                                                        Cancelado</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @else
                                {{-- Show a message when there are no records --}}
                                <div class="adobe-text-muted text-center py-4">
                                    Nenhum registro encontrado
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="dateFilterModal" tabindex="-1" role="dialog"
                aria-labelledby="dateFilterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="shadow-lg modal-content">
                        <form method="GET" action="{{ route('admin.financeiro.entradas') }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dateFilterModalLabel">Filtrar por Data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="data_inicio">Data Início</label>
                                    <input type="date" class="form-control" name="data_inicio" id="data_inicio"
                                        value="{{ $dataInicio }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_fim">Data Fim</label>
                                    <input type="date" class="form-control" name="data_fim" id="data_fim"
                                        value="{{ $dataFim }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        $("#table-financeiro-entradas").DataTable({
            responsive: true,
            info: false,
            ordering: false,
            lengthChange: false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            dom: '<"top"f>rt<"bottom"p><"clear">',
            initComplete: function() {
                // Muda o placeholder do input de busca
                $('#table-financeiro-entradas_filter input[type="search"]').attr('placeholder',
                    'Pesquisar');
            }
        });

        // Handler para mudança de status
        $('.status-select').on('change', function() {
            const id = $(this).data('id');
            const type = $(this).data('type');
            const newStatus = $(this).val();
            const selectElement = $(this);

            // Confirmar mudança
            if (confirm('Tem certeza que deseja alterar o status desta transação?')) {
                $.ajax({
                    url: `/admin/financeiro/${type}/${id}/status`,
                    method: 'PUT',
                    data: {
                        status: newStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Atualizar o badge de status na linha
                            const statusCell = selectElement.closest('tr').find(
                                'td:nth-child(6)');
                            let badgeClass = '';
                            let statusText = '';

                            switch (newStatus) {
                                case 'PAID_OUT':
                                    badgeClass = 'bg-success';
                                    statusText = 'Aprovado';
                                    break;
                                case 'WAITING_FOR_APPROVAL':
                                    badgeClass = 'bg-warning';
                                    statusText = 'Pendente';
                                    break;
                                case 'PENDING':
                                    badgeClass = 'bg-warning';
                                    statusText = 'Pendente';
                                    break;
                                case 'RELEASE':
                                    badgeClass = 'badge-sm bg-info gateway-badge-info';
                                    statusText = 'A Liberar';
                                    break;
                                case 'CANCELLED':
                                    badgeClass = 'bg-danger-transparent';
                                    statusText = 'Cancelado';
                                    break;
                            }

                            statusCell.html(
                                `<span class="badge ${badgeClass}">${statusText}</span>`
                                );

                            // Mostrar mensagem de sucesso
                            alert('Status atualizado com sucesso!');
                        }
                    },
                    error: function(xhr) {
                        alert('Erro ao atualizar status: ' + xhr.responseJSON.message);
                        // Reverter o select para o valor anterior
                        location.reload();
                    }
                });
            } else {
                // Reverter o select para o valor anterior
                location.reload();
            }
        });
    });

    // Adicionar evento de clique nas linhas da tabela
    $('#table-financeiro-entradas tbody').on('click', 'tr.transaction-row', function(e) {
        // Evitar clique na coluna de ações (select)
        if ($(e.target).closest('select').length > 0) {
            return;
        }

        var transactionId = $(this).data('transaction-id');
        if (transactionId) {
            carregarDetalhesTransacao(transactionId);
        }
    });

    // Função para carregar detalhes da transação
    function carregarDetalhesTransacao(transactionId) {
        $.ajax({
            url: '/admin/financeiro/entradas/detalhes/' + transactionId,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    preencherModal(response.transaction);
                    $('#detalhesModal').modal('show');
                } else {
                    alert('Erro ao carregar detalhes da transação: ' + (response.message ||
                        'Erro desconhecido'));
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
        $('#modal-valor').text('R$ ' + parseFloat(dados.amount).toLocaleString('pt-BR', {
            minimumFractionDigits: 2
        }));
        $('#modal-produto').text('Depósito');
        $('#modal-cliente').text(dados.client_name || 'N/A');
        $('#modal-metodo').text(dados.method === 'pix' ? 'PIX' : dados.method === 'card' ? 'Cartão' : 'Boleto');

        // Ícone do método
        if (dados.method === 'pix') {
            $('#modal-icone-metodo').attr('class', 'fa fa-brands fa-pix me-2').css('color', '#00a782');
        } else if (dados.method === 'card') {
            $('#modal-icone-metodo').attr('class', 'fa fa-credit-card me-2').css('color', '#ff9a02');
        } else if (dados.method === 'billet') {
            $('#modal-icone-metodo').attr('class', 'fa fa-barcode me-2').css('color', '#17a2b8');
        }

        // Status
        var statusText = getStatusText(dados.status);
        var statusClass = getStatusClass(dados.status);
        $('#modal-status').text(statusText).attr('class', 'badge ' + statusClass);
        $('#modal-status-historico').text(statusText).attr('class', 'badge me-2 ' + statusClass);

        // Dados da transação
        $('#modal-id').text(dados.idTransaction);
        $('#modal-valor-detalhes').text('R$ ' + parseFloat(dados.amount).toLocaleString('pt-BR', {
            minimumFractionDigits: 2
        }));
        $('#modal-taxa').text('R$ ' + (parseFloat(dados.amount) - parseFloat(dados.deposito_liquido)).toLocaleString(
            'pt-BR', {
                minimumFractionDigits: 2
            }));
        $('#modal-valor-liquido').text('R$ ' + parseFloat(dados.deposito_liquido).toLocaleString('pt-BR', {
            minimumFractionDigits: 2
        }));
        $('#modal-data-cadastro').text(new Date(dados.date).toLocaleString('pt-BR'));
        $('#modal-empresa').text('HKPAY');

        // Histórico
        $('#modal-data-historico').text(new Date(dados.date).toLocaleString('pt-BR'));

        // Adquirente
        $('#modal-tid').text(dados.idTransaction);

        // Assinatura
        $('#modal-assinatura-status').text('ATIVA');
        $('#modal-assinatura-data').text(new Date(dados.date).toLocaleString('pt-BR'));

        // Dados do pagamento - seção específica do modal
        if (dados.method === 'card') {
            var cardNumber = dados.card_number || '**** **** **** ****';
            var cardBrand = getCardBrand(cardNumber);

            $('#modal-dados-pagamento .d-flex.align-items-center.mb-2').html(`
                <div style="width: 40px; height: 25px; background: ${cardBrand.color}; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                    <span style="color: white; font-size: 10px; font-weight: bold;">${cardBrand.name}</span>
                </div>
                <span id="modal-cartao-numero" style="color: #ccc;">${cardNumber}</span>
            `);
            $('#modal-cartao-nome').text(dados.client_name || 'Nome do Portador');
            $('#modal-cartao-validade').text(dados.card_expiry || 'válido até 12/25');
        } else if (dados.method === 'pix') {
            $('#modal-dados-pagamento .d-flex.align-items-center.mb-2').html(`
                <div style="width: 40px; height: 25px; background: #00a782; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-brands fa-pix" style="color: white; font-size: 12px;"></i>
                </div>
                <span style="color: #ccc;">PIX Instantâneo</span>
            `);
            $('#modal-cartao-nome').text('Pagamento via PIX');
            $('#modal-cartao-validade').text('Processamento instantâneo');
        } else if (dados.method === 'billet') {
            $('#modal-dados-pagamento .d-flex.align-items-center.mb-2').html(`
                <div style="width: 40px; height: 25px; background: #17a2b8; border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa fa-barcode" style="color: white; font-size: 12px;"></i>
                </div>
                <span style="color: #ccc;">Boleto Bancário</span>
            `);
            $('#modal-cartao-nome').text('Pagamento via Boleto');
            $('#modal-cartao-validade').text('Vencimento em 3 dias');
        }

        // Armazenar ID da transação para ações
        $('#estornarBtn').data('transaction-id', dados.id);
        $('#mediacaoBtn').data('transaction-id', dados.id);

        // Mostrar botões apenas se o status permitir
        if (dados.status === 'PAID_OUT') {
            $('#estornarBtn').show();
            $('#mediacaoBtn').show();
        } else {
            $('#estornarBtn').hide();
            $('#mediacaoBtn').hide();
        }
    }

    // Eventos de ação usando delegação
    $(document).on('click', '#mediacaoBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();

        var transactionId = $(this).data('transaction-id');
        console.log('Botão mediação clicado, ID:', transactionId);

        if (!transactionId) {
            alert('Erro: ID da transação não encontrado');
            return;
        }

        if (confirm(
                'Tem certeza que deseja enviar esta transação para mediação? O valor será bloqueado e ficará pendente para liberação manual.'
                )) {
            executarMediacao(transactionId);
        }
    });

    $(document).on('click', '#estornarBtn', function() {
        var transactionId = $(this).data('transaction-id');
        console.log('Botão estornar clicado, ID:', transactionId);
        if (confirm('Tem certeza que deseja estornar esta transação?')) {
            executarEstorno(transactionId);
        }
    });

    // Função para executar mediação
    function executarMediacao(transactionId) {
        console.log('Executando mediação para ID:', transactionId);
        console.log('URL:', '/admin/financeiro/entradas/mediacao/' + transactionId);
        console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: '/admin/financeiro/entradas/mediacao/' + transactionId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                console.log('Enviando requisição AJAX...');
            },
            success: function(response) {
                console.log('Resposta recebida:', response);
                alert('Transação enviada para mediação com sucesso!');
                $('#detalhesModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Erro AJAX:', xhr);
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                alert('Erro ao enviar transação para mediação: ' + error);
            }
        });
    }

    // Função para executar estorno
    function executarEstorno(transactionId) {
        $.ajax({
            url: '/admin/financeiro/entradas/estornar/' + transactionId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Transação estornada com sucesso!');
                $('#detalhesModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                console.error('Erro ao estornar transação:', xhr);
                alert('Erro ao estornar transação');
            }
        });
    }

    // Evento de ação (ex: estornar)
    $('#estornarBtn').on('click', function() {
        var transactionId = $(this).data('transaction-id');
        if (confirm('Tem certeza que deseja estornar esta transação?')) {
            executarEstorno(transactionId);
        }
    });

    // Função para executar estorno
    function executarEstorno(transactionId) {
        $.ajax({
            url: '/admin/financeiro/entradas/estornar/' + transactionId,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Transação estornada com sucesso!');
                $('#detalhesModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                console.error('Erro ao estornar transação:', xhr);
                alert('Erro ao estornar transação');
            }
        });
    }

    // Funções auxiliares
    function getStatusText(status) {
        const statusMap = {
            'PAID_OUT': 'APROVADO',
            'WAITING_FOR_APPROVAL': 'PENDENTE',
            'PENDING': 'PENDENTE',
            'RELEASE': 'A LIBERAR',
            'MEDIATION': 'EM MEDIAÇÃO',
            'CANCELLED': 'CANCELADO'
        };
        return statusMap[status] || status;
    }

    function getStatusClass(status) {
        const classMap = {
            'PAID_OUT': 'bg-success',
            'WAITING_FOR_APPROVAL': 'bg-warning',
            'PENDING': 'bg-warning',
            'RELEASE': 'bg-info',
            'MEDIATION': 'bg-warning',
            'CANCELLED': 'bg-danger'
        };
        return classMap[status] || 'bg-secondary';
    }

    // Função para determinar a bandeira do cartão
    function getCardBrand(cardNumber) {
        // Remover espaços e caracteres não numéricos
        const cleanNumber = cardNumber.replace(/\D/g, '');

        // VISA: começa com 4
        if (cleanNumber.startsWith('4')) {
            return {
                name: 'VISA',
                icon: 'fab fa-cc-visa',
                color: '#1A1F71'
            };
        }
        // Mastercard: começa com 5 ou 2
        else if (cleanNumber.startsWith('5') || cleanNumber.startsWith('2')) {
            return {
                name: 'Mastercard',
                icon: 'fab fa-cc-mastercard',
                color: '#EB001B'
            };
        }
        // American Express: começa com 3
        else if (cleanNumber.startsWith('3')) {
            return {
                name: 'American Express',
                icon: 'fab fa-cc-amex',
                color: '#006FCF'
            };
        }
        // Elo: começa com 6
        else if (cleanNumber.startsWith('6')) {
            return {
                name: 'ELO',
                icon: 'fas fa-credit-card',
                color: '#FFD700'
            };
        }
        // Hipercard: começa com 3
        else if (cleanNumber.startsWith('3')) {
            return {
                name: 'Hipercard',
                icon: 'fas fa-credit-card',
                color: '#FF6B35'
            };
        }
        // Padrão
        else {
            return {
                name: 'Cartão',
                icon: 'fas fa-credit-card',
                color: '#6c757d'
            };
        }
    }
    </script>

    <!-- Modal Detalhes da Transação -->
    <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background-color: #1a1a1a; color: white; border-radius: 15px;">
                <div class="modal-header" style="border-bottom: 1px solid #333; padding: 20px;">
                    <h5 class="modal-title" id="detalhesModalLabel" style="color: white; font-weight: bold;">
                        <i class="fa fa-times me-2" style="cursor: pointer;" data-bs-dismiss="modal"></i>
                        Detalhes da transação
                    </h5>
                    <button type="button" class="btn btn-warning me-2" id="mediacaoBtn"
                        style="background-color: #ffc107; border: none; border-radius: 8px;">
                        <i class="fa fa-gavel me-1"></i> Mediação
                    </button>
                    <button type="button" class="btn btn-danger" id="estornarBtn"
                        style="background-color: #dc3545; border: none; border-radius: 8px;">
                        <i class="fa fa-undo me-1"></i> Estornar
                    </button>
                </div>
                <div class="modal-body" style="padding: 20px;">
                    <!-- Barra de Resumo -->
                    <div class="row mb-4" style="background-color: #2a2a2a; padding: 15px; border-radius: 10px;">
                        <div class="col-md-3 text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fa fa-dollar-sign me-2" style="color: #28a745;"></i>
                                <span id="modal-valor" style="font-size: 18px; font-weight: bold;">R$ 0,00</span>
                            </div>
                            <small style="color: #ccc;">Valor</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fa fa-check-circle me-2" style="color: #28a745;"></i>
                                <span id="modal-produto" style="font-size: 16px;">Produto</span>
                            </div>
                            <small style="color: #ccc;">Produto</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="fa fa-user me-2" style="color: #28a745;"></i>
                                <span id="modal-cliente" style="font-size: 16px;">Cliente</span>
                            </div>
                            <small style="color: #ccc;">Cliente</small>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i id="modal-icone-metodo" class="fa me-2"></i>
                                <span id="modal-metodo" style="font-size: 16px;">Método</span>
                            </div>
                            <small style="color: #ccc;">Forma de pagamento</small>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="text-center mb-4">
                        <span id="modal-status" class="badge"
                            style="font-size: 16px; padding: 10px 20px; border-radius: 20px;">APROVADA</span>
                    </div>

                    <div class="row">
                        <!-- Coluna Esquerda - Histórico -->
                        <div class="col-md-6">
                            <h6 style="color: #fff; margin-bottom: 15px;">Histórico</h6>
                            <div style="background-color: #2a2a2a; padding: 15px; border-radius: 10px;">
                                <div class="d-flex align-items-center mb-2">
                                    <span id="modal-status-historico" class="badge me-2"
                                        style="background-color: #28a745;">APROVADA</span>
                                </div>
                                <div style="color: #ccc; font-size: 14px;">
                                    <div id="modal-data-historico">06/09/2025 20:05</div>
                                    <div>00 APROVADA</div>
                                    <div>SISTEMA</div>
                                </div>
                            </div>
                        </div>

                        <!-- Coluna Direita - Dados da Transação -->
                        <div class="col-md-6">
                            <h6 style="color: #fff; margin-bottom: 15px;">Dados da Transação</h6>
                            <div style="background-color: #2a2a2a; padding: 15px; border-radius: 10px;">
                                <div class="mb-2">
                                    <strong>ID:</strong> <span id="modal-id" style="color: #ccc;">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Valor:</strong> <span id="modal-valor-detalhes"
                                        style="color: #ccc;">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Taxa:</strong> <span id="modal-taxa" style="color: #ccc;">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Valor Líquido:</strong> <span id="modal-valor-liquido"
                                        style="color: #ccc;">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Cadastro:</strong> <span id="modal-data-cadastro"
                                        style="color: #ccc;">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Empresa:</strong> <span id="modal-empresa" style="color: #ccc;">-</span>
                                </div>
                            </div>

                            <h6 style="color: #fff; margin: 20px 0 15px 0;">Adquirente</h6>
                            <div style="background-color: #2a2a2a; padding: 15px; border-radius: 10px;">
                                <div class="mb-2">
                                    <strong>ID TID:</strong> <span id="modal-tid" style="color: #ccc;">-</span>
                                </div>
                            </div>

                            <h6 style="color: #fff; margin: 20px 0 15px 0;">Assinatura</h6>
                            <div style="background-color: #2a2a2a; padding: 15px; border-radius: 10px;">
                                <div class="mb-2">
                                    <strong>Status:</strong> <span id="modal-assinatura-status" class="badge"
                                        style="background-color: #28a745;">ATIVA</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Cadastro:</strong> <span id="modal-assinatura-data"
                                        style="color: #ccc;">-</span>
                                </div>
                            </div>

                            <h6 style="color: #fff; margin: 20px 0 15px 0;">Dados do pagamento</h6>
                            <div id="modal-dados-pagamento"
                                style="background-color: #2a2a2a; padding: 15px; border-radius: 10px;">
                                <div class="d-flex align-items-center mb-2">
                                    <div
                                        style="width: 40px; height: 25px; background: linear-gradient(45deg, #1e3c72, #2a5298); border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
                                        <span style="color: white; font-size: 10px; font-weight: bold;">VISA</span>
                                    </div>
                                    <span id="modal-cartao-numero" style="color: #ccc;">**** **** **** ****</span>
                                </div>
                                <div style="color: #ccc; font-size: 14px;">
                                    <div id="modal-cartao-nome">Nome do Portador</div>
                                    <div id="modal-cartao-validade">válido até 12/25</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
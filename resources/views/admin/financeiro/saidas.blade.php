<x-app-layout :route="'[ADMIN] Saídas'">

    <div class="main-content app-content">
        <div class="container-fluid">

            {{-- ... (toda a parte dos cards de resumo continua igual) ... --}}
            <div class="mb-3 row justify-content-between align-items-center">
                <div class="mb-0 md-mb-5 col-12 col-md-8 mb-md-0 d-flex justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Relatórios de Saídas</h1>
                </div>
                <div class="col-12 col-md-4 d-flex justify-content-end align-items-center">
                </div>
            </div>
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ $totalaprovadas }}</div>
                                    <div class="adobe-text-muted">Aprovadas (Total)</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ $totalaprovadasHoje }}</div>
                                    <div class="adobe-text-muted">Aprovadas (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body px-4 py-4">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success adobe-text fw-bold">{{ $totalaprovadasMes }}</div>
                                    <div class="adobe-text-muted">Aprovadas (Mês)</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ $totalsolicitacoes }}</div>
                                    <div class="card-text">Transações geral</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-sync"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoTotal, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Bruto)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoHoje, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-4 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($valorAprovadoMes, 2, ',', '.') }}</div>
                                    <div class="card-text">Aprovadas (Mês)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table-financeiro-saidas" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Transação ID</th>
                                            <th>Valor</th>
                                            <th>Valor Líquido</th>
                                            <th>Status</th>
                                            <th>Nome</th>
                                            <th>Chave PIX</th>
                                            <th>Documento</th>
                                            <th>Data</th>
                                            <th>Taxa</th>
                                            <th>Resposta da adquirência</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- ======================================================= --}}
                                        {{-- CORREÇÃO: O @empty FOI REMOVIDO DAQUI --}}
                                        {{-- ======================================================= --}}
                                        @foreach ($cashOuts as $cashOut)
                                        <tr>
                                            <td>{{ $cashOut->user_id }}</td>
                                            <td>{{ $cashOut->externalreference }}</td>
                                            <td>{{ number_format($cashOut->amount, 2, ',', '.') }}</td>
                                            <td>{{ number_format($cashOut->cash_out_liquido, 2, ',', '.') }}</td>
                                            <td>
                                                @switch($cashOut->status)
                                                    @case('COMPLETED')
                                                    @case('PAID_OUT')
                                                        <span class="badge bg-success">Aprovado</span>
                                                        @break
                                                    @case('PENDING')
                                                        <span class="badge bg-warning">Pendente</span>
                                                        @break
                                                    @case('REJECTED')
                                                        <span class="badge bg-danger">Rejeitado</span>
                                                        @break
                                                    @case('CANCELLED')
                                                    @case('CANCELED')
                                                        <span class="badge bg-danger">Cancelado</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ $cashOut->status }}</span>
                                                @endswitch
                                            </td>
                                            <td>{{ $cashOut->beneficiaryname }}</td>
                                            <td>{{ $cashOut->pixkey }}</td>
                                            <td>{{ $cashOut->beneficiarydocument }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cashOut->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <td>R$ {{ number_format((float)$cashOut->amount - (float)$cashOut->cash_out_liquido, '2', ',', '.') }}</td>
                                            <td style="white-space: pre-wrap; word-wrap: break-word;">
                                                {!! nl2br(e($cashOut->descricao_externa)) !!}
                                            </td>
                                            <td>
                                                <select class="form-select form-select-sm status-select" data-id="{{ $cashOut->id }}" data-type="saida">
                                                    <option value="COMPLETED" {{ $cashOut->status == 'COMPLETED' ? 'selected' : '' }}>Aprovado</option>
                                                    <option value="PAID_OUT" {{ $cashOut->status == 'PAID_OUT' ? 'selected' : '' }}>Pago</option>
                                                    <option value="PENDING" {{ $cashOut->status == 'PENDING' ? 'selected' : '' }}>Pendente</option>
                                                    <option value="REJECTED" {{ $cashOut->status == 'REJECTED' ? 'selected' : '' }}>Rejeitado</option>
                                                    <option value="CANCELLED" {{ $cashOut->status == 'CANCELLED' || $cashOut->status == 'CANCELED' ? 'selected' : '' }}>Cancelado</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ... (O modal de filtro continua o mesmo) ... --}}
            <div class="modal fade" id="dateFilterModal" tabindex="-1" role="dialog" aria-labelledby="dateFilterModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="shadow-lg modal-content">
                        <form method="GET" action="{{ route('admin.financeiro.saidas') }}">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dateFilterModalLabel">Filtrar por Data</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="data_inicio">Data Início</label>
                                    <input type="date" class="form-control" name="data_inicio" id="data_inicio" value="{{ $dataInicio }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_fim">Data Fim</label>
                                    <input type="date" class="form-control" name="data_fim" id="data_fim" value="{{ $dataFim }}" required>
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
            $("#table-financeiro-saidas").DataTable({
                responsive: true,
                info: false,
                ordering: false,
                lengthChange: false,
                autoWidth: false,
                columnDefs: [
                    { targets: 0, width: "8%" },
                    { targets: 1, width: "10%" },
                    { targets: 2, width: "8%" },
                    { targets: 3, width: "8%" },
                    { targets: 4, width: "8%" },
                    { targets: 5, width: "10%" },
                    { targets: 6, width: "12%" },
                    { targets: 7, width: "8%" },
                    { targets: 8, width: "10%" },
                    { targets: 9, width: "8%" },
                    { targets: 10, width: "15%" },
                    { targets: 11, width: "10%" }
                ],
                language: {
                    // Mantenha a sua tradução pt-BR
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
                    
                    // ======================================================= //
                    // CORREÇÃO: ADICIONE A LINHA ABAIXO                       //
                    // ======================================================= //
                    emptyTable: "Nenhum registro encontrado"
                },
                dom: '<"top"f>rt<"bottom"p><"clear">',
                initComplete: function() {
                    $('#table-financeiro-saidas_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                    console.log('DataTables inicializado com sucesso');
                }
            });

            // Handler para mudança de status (continua igual)
            $('.status-select').on('change', function() {
                const id = $(this).data('id');
                const type = $(this).data('type');
                const newStatus = $(this).val();
                const selectElement = $(this);

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
                                const statusCell = selectElement.closest('tr').find('td:nth-child(5)');
                                let badgeClass = '';
                                let statusText = '';

                                switch(newStatus) {
                                    case 'COMPLETED':
                                    case 'PAID_OUT':
                                        badgeClass = 'bg-success';
                                        statusText = 'Aprovado';
                                        break;
                                    case 'PENDING':
                                        badgeClass = 'bg-warning';
                                        statusText = 'Pendente';
                                        break;
                                    case 'CANCELLED':
                                    case 'CANCELED':
                                        badgeClass = 'bg-danger';
                                        statusText = 'Cancelado';
                                        break;
                                    case 'REJECTED':
                                        badgeClass = 'bg-danger';
                                        statusText = 'Rejeitado';
                                        break;
                                }

                                statusCell.html(`<span class="badge ${badgeClass}">${statusText}</span>`);
                                alert('Status atualizado com sucesso!');
                            }
                        },
                        error: function(xhr) {
                            alert('Erro ao atualizar status: ' + xhr.responseJSON.message);
                            location.reload();
                        }
                    });
                } else {
                    location.reload();
                }
            });
        });
    </script>
</x-app-layout>
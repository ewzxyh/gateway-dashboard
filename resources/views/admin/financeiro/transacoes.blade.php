<x-app-layout :route="'[ADMIN] Transações'">    
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-4 row justify-content-between align-items-center">
                <div class="col-12 col-md-6 d-flex align-items-center">
                    <i class="fas fa-exchange-alt me-3" style="font-size: 2rem; color: #667eea;"></i>
                    <h1 class="mb-0 display-5 glassmorphism-title">Transações Financeiras</h1>
                </div>
            </div>

            <!-- Start:: row-1 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="glassmorphism-card">
                        <div class="px-4 py-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 value-display">{{ $transacoes_aprovadas }}</div>
                                    <div class="card-text-glassmorphism">Transações Aprovadas</div>
                                </div>
                                <div class="icon-circle">
                                    <i class="fa-solid fa-sync" style="color: #667eea; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="glassmorphism-card">
                        <div class="px-4 py-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 value-display">{{ "R$ ".number_format($lucro_liquido_hoje ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text-glassmorphism">Lucro Líquido (Hoje)</div>
                                </div>
                                <div class="icon-circle">
                                    <i class="fa-solid fa-dollar-sign" style="color: #28a745; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="glassmorphism-card">
                        <div class="px-4 py-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 value-display">{{ "R$ ".number_format($lucro_liquido_mes ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text-glassmorphism">Lucro Líquido (Mês)</div>
                                </div>
                                <div class="icon-circle">
                                    <i class="fa-solid fa-dollar-sign" style="color: #20c997; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="glassmorphism-card">
                        <div class="px-4 py-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 value-display">{{ "R$ ".number_format($lucro_liquido_total ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text-glassmorphism">Lucro Líquido (Total)</div>
                                </div>
                                <div class="icon-circle">
                                    <i class="fa-solid fa-chart-line" style="color: #764ba2; font-size: 1.5rem;"></i>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->





         {{--    <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-4 col-xxl-3 col-md-6">
                    <div class="border-4 card card-raised card-border-color ">
                        <div class="px-4 card-body">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success">{{ "R$ ".number_format($lucro_liquido_hoje ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text">Lucro liquido (Hoje)</div>
                                </div>
                                <div class="text-white icon-circle bg-warning card-color"><i class="fa-solid fa-arrow-down-short-wide"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card card-raised">
                        <div class="p-4 card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div>
                                        <span class="mb-2 d-block">Transações aprovadas</span>
                                        <h5 class="mb-4 fs-4">{{ $transacoes_aprovadas ?? 0 }}</h5>
                                    </div>
                                    <span class="text-success me-2 fw-medium d-inline-block"></span><span class="text-muted">Total </span>
                                </div>
                                <div>
                                    <div class="main-card-icon success">
                                        <div class="avatar avatar-lg avatar-rounded bg-success-transparent svg-success">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
                                                <path d="M200,168a48.05,48.05,0,0,1-48,48H136v16a8,8,0,0,1-16,0V216H104a48.05,48.05,0,0,1-48-48,8,8,0,0,1,16,0,32,32,0,0,0,32,32h48a32,32,0,0,0,0-64H112a48,48,0,0,1,0-96h8V24a8,8,0,0,1,16,0V40h8a48.05,48.05,0,0,1,48,48,8,8,0,0,1-16,0,32,32,0,0,0-32-32H112a32,32,0,0,0,0,64h40A48.05,48.05,0,0,1,200,168Z"></path>
                                                </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card main-card">
                        <div class="p-4 card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div>
                                        <span class="mb-2 d-block">Valor aprovado</span>
                                        <h5 class="mb-4 fs-4">{{ number_format($valor_aprovado_hoje ?? 0, 2, ',', '.') }}</h5>
                                    </div>
                                    <span class="text-success me-2 fw-medium d-inline-block"></span><span class="text-muted">Hoje</span>
                                </div>
                                <div>
                                    <div class="main-card-icon success">
                                        <div class="avatar avatar-lg avatar-rounded bg-success-transparent svg-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
                                                    <path d="M200,168a48.05,48.05,0,0,1-48,48H136v16a8,8,0,0,1-16,0V216H104a48.05,48.05,0,0,1-48-48,8,8,0,0,1,16,0,32,32,0,0,0,32,32h48a32,32,0,0,0,0-64H112a48,48,0,0,1,0-96h8V24a8,8,0,0,1,16,0V40h8a48.05,48.05,0,0,1,48,48,8,8,0,0,1-16,0,32,32,0,0,0-32-32H112a32,32,0,0,0,0,64h40A48.05,48.05,0,0,1,200,168Z"></path>
                                                </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card main-card">
                        <div class="p-4 card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div>
                                        <span class="mb-2 d-block">Valor aprovado</span>
                                        <h5 class="mb-4 fs-4">{{ number_format($valor_aprovado_mes ?? 0, 2, ',', '.') }}</h5>
                                    </div>
                                    <span class="text-success me-2 fw-medium d-inline-block"></span><span class="text-muted">Mês</span>
                                </div>
                                <div>
                                    <div class="main-card-icon primary">
                                        <div class="border avatar avatar-lg bg-primary-transparent border-primary border-opacity-10">
                                            <div class="avatar avatar-sm svg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256">
                                                    <path d="M216,72H56a8,8,0,0,1,0-16H192a8,8,0,0,0,0-16H56A24,24,0,0,0,32,64V192a24,24,0,0,0,24,24H216a16,16,0,0,0,16-16V88A16,16,0,0,0,216,72Zm0,128H56a8,8,0,0,1-8-8V86.63A23.84,23.84,0,0,0,56,88H216Zm-48-60a12,12,0,1,1,12,12A12,12,0,0,1,168,140Z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card main-card">
                        <div class="p-4 card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <div>
                                        <span class="mb-2 d-block">Valor aprovado</span>
                                        <h5 class="mb-4 fs-4">{{ number_format($valor_aprovado_total ?? 0, 2, ',', '.') }}</h5>
                                    </div>
                                    <span class="text-danger me-2 fw-medium d-inline-block"></span><span class="text-muted">TOTAL</span>
                                </div>
                                <div>
                                    <div class="main-card-icon orange">
                                        <div class="border avatar avatar-lg bg-orange-transparent border-orange border-opacity-10">
                                            <div class="avatar avatar-sm svg-white">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 256 256">
                                                <path d="M224,200h-8V40a8,8,0,0,0-8-8H152a8,8,0,0,0-8,8V80H96a8,8,0,0,0-8,8v40H48a8,8,0,0,0-8,8v64H32a8,8,0,0,0,0,16H224a8,8,0,0,0,0-16ZM160,48h40V200H160ZM104,96h40V200H104ZM56,144H88v56H56Z"></path>
                                            </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-1 -->
 --}}
            <!-- Start::row-2 -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="glassmorphism-table">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <i class="fas fa-table me-3" style="color: #667eea; font-size: 1.5rem;"></i>
                                <h4 class="mb-0 glassmorphism-title">Lista de Transações</h4>
                            </div>
                            <div class="table-responsive">
                                <table id="table-transacoes" class="table text-nowrap table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Meio</th>
                                            <th scope="col">Cliente ID</th>
                                            <th scope="col">Transação ID</th>
                                            <th scope="col">Valor Total</th>
                                            <th scope="col">Valor Liquido</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Data</th>
                                            <!--  <th scope="col">Ações</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($deposits)
                                        @foreach($deposits as $row)
                                        @php
                                        // Ajustar a exibição do status
                                        switch ($row->status) {
                                        case 'PAID_OUT':
                                        $statusBadge = 'badge bg-success bg-opacity-75';
                                        $statusText = 'Aprovado';
                                        break;
                                        case 'WAITING_FOR_APPROVAL':
                                        $statusBadge = 'badge bg-warning bg-opacity-75';
                                        $statusText = 'Pendente';
                                        break;
                                        case 'PENDING':
                                        $statusBadge = 'badge bg-warning bg-opacity-75';
                                        $statusText = 'Pendente';
                                        break;
                                        case 'RELEASE':
                                        $statusBadge = 'badge bg-info bg-opacity-75';
                                        $statusText = 'A Liberar';
                                        break;
                                        case 'CANCELLED':
                                        $statusBadge = 'badge bg-danger bg-opacity-75';
                                        $statusText = 'Cancelado';
                                        break;
                                        default:
                                        $statusBadge = 'badge bg-secondary bg-opacity-75';
                                        $statusText = $row->status;
                                        }
                                        @endphp
                                        <tr class="transaction-row" style="cursor: pointer;" data-transaction-id="{{ $row->id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="Clique para ver detalhes">
                                            <td>
                                                @if(isset($row->method))
                                                    @switch($row->method)
                                                        @case('pix')
                                                            <i class="fa-brands fa-pix" style="color:rgb(0, 167, 130)"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="PIX"></i>
                                                        @break
                                                        @case('billet')
                                                            <i class="fa-solid fa-barcode" style="color:black"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Boleto"></i>
                                                        @break
                                                        @case('card')
                                                            <i class="fa-solid fa-credit-card" style="color:rgb(255, 154, 2)"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Cartão"></i>
                                                        @break
                                                    @endswitch
                                                @else
                                                {{'--'}}
                                                @endif
                                            </td>
                                            <td>{{ $row->user_id }}</td>
                                            <td>{{ $row->idTransaction }}</td>
                                            <td>{{ $row->amount }}</td>
                                            <td>{{ $row->deposito_liquido }}</td>
                                            <td>
                                                @if($statusText == "A Liberar")    
                                                    <span class="badge {{ $statusBadge }}" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Será liberado em {{ \Carbon\Carbon::parse($row->date)->addDays($row->days_availability ?? 21)->format('d/m/Y \à\s H:i:s') }}" data-bs-placement="top" >A Liberar</span>
                                                @else
                                                    <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <!--  <td>
                                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal"
                                                    data-client-id="{{ $row->user_id }}"
                                                    data-externalreference="{{ $row->idTransaction }}"
                                                    data-valor="{{ $row->amount }}"
                                                    data-status="{{ $row->status }}"
                                                    data-data="{{ $row->date }}">Editar</button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-client-id="{{ $row->user_id }}">Excluir</button>
                                            </td> -->
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="6">Nenhum registro encontrado</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-2 -->

            <!-- Modal Editar -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px;">
                        <div class="modal-header border-0 pb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-edit me-3" style="color: #667eea; font-size: 1.5rem;"></i>
                                <h5 class="modal-title glassmorphism-title" id="editModalLabel">Editar Transação</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 0.5rem;"></button>
                        </div>
                        <div class="modal-body pt-3">
                            <form id="editForm">
                                <input type="hidden" id="editEmail" name="email">
                                <div class="mb-3">
                                    <label for="editExternalReference" class="form-label card-text-glassmorphism">Referência Externa</label>
                                    <input type="text" class="form-control" id="editExternalReference" name="externalreference" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white;">
                                </div>
                                <div class="mb-3">
                                    <label for="editValor" class="form-label card-text-glassmorphism">Valor</label>
                                    <input type="text" class="form-control" id="editValor" name="valor" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white;">
                                </div>
                                <div class="mb-3">
                                    <label for="editStatus" class="form-label card-text-glassmorphism">Status</label>
                                    <select class="form-select" id="editStatus" name="status" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white;">
                                        <option value="WAITING_FOR_APPROVAL">Pendente</option>
                                        <option value="PAID_OUT">Aprovado</option>
                                        <option value="RELEASE">A Liberar</option>
                                        <option value="CANCELLED">Cancelado</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="editData" class="form-label card-text-glassmorphism">Data</label>
                                    <input type="text" class="form-control" id="editData" name="data" style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); color: white;">
                                </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn" data-bs-dismiss="modal" style="background: rgba(108, 117, 125, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px;">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                            <button type="submit" class="btn" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(40, 167, 69, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fas fa-save me-2"></i>Salvar Alterações
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Confirmar Exclusão -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px;">
                        <div class="modal-header border-0 pb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-3" style="color: #dc3545; font-size: 1.5rem;"></i>
                                <h5 class="modal-title glassmorphism-title" id="deleteModalLabel">Confirmar Exclusão</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 0.5rem;"></button>
                        </div>
                        <div class="modal-body pt-3">
                            <p class="card-text-glassmorphism mb-0">Você tem certeza que deseja excluir esta transação? Esta ação não pode ser desfeita.</p>
                        </div>
                        <div class="modal-footer border-0 pt-3">
                            <button type="button" class="btn" data-bs-dismiss="modal" style="background: rgba(108, 117, 125, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px;">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </button>
                            <button type="button" class="btn" id="confirmDeleteBtn" style="background: linear-gradient(135deg, #dc3545, #c82333); color: white; border: none; border-radius: 8px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(220, 53, 69, 0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fas fa-trash me-2"></i>Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                $("#table-transacoes").DataTable({
                    responsive: true,
                    info:false,
                    ordering: false,
                    lengthChange: false,
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                    },
                    dom: '<"top"f>rt<"bottom"p><"clear">',
                        initComplete: function() {
                            // Muda o placeholder do input de busca
                            $('#table-aprovar-saques_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                        }
                });
            });
            </script>

            <!-- JavaScript para Preencher o Modal e Enviar Alterações -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var editModal = document.getElementById('editModal');
                    var deleteModal = document.getElementById('deleteModal');
                    var emailToDelete = null;

                    // Preencher o modal de edição
                    editModal.addEventListener('show.bs.modal', function(event) {
                        var button = event.relatedTarget;
                        document.getElementById('editEmail').value = button.getAttribute('data-client-id');
                        document.getElementById('editExternalReference').value = button.getAttribute('data-externalreference');
                        document.getElementById('editValor').value = button.getAttribute('data-valor');
                        document.getElementById('editStatus').value = button.getAttribute('data-status');
                        document.getElementById('editData').value = button.getAttribute('data-data');
                    });

                    // Enviar o formulário de edição
                    document.getElementById('editForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        var formData = new FormData(this);
                        fetch('update_confirmar_deposito.php', {
                                method: 'POST',
                                body: formData
                            }).then(response => response.text())
                            .then(result => {
                                console.log(result); // Para depuração
                                if (result === 'success') {
                                    window.location.reload();
                                } else {
                                    alert('Erro ao atualizar depósito.');
                                }
                            });
                    });

                    // Definir o email do depósito para exclusão
                    deleteModal.addEventListener('show.bs.modal', function(event) {
                        var button = event.relatedTarget;
                        emailToDelete = button.getAttribute('data-client-id');
                    });

                    // Confirmar a exclusão
                    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                        fetch('delete_confirmar_deposito.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                },
                                body: new URLSearchParams({
                                    'email': emailToDelete
                                })
                            }).then(response => response.text())
                            .then(result => {
                                console.log(result); // Para depuração
                                if (result === 'success') {
                                    window.location.reload();
                                } else {
                                    alert('Erro ao excluir depósito.');
                                }
                            });
                    });
                });
            </script>

            <script>
                $(document).ready(function() {
                    // Adicionar evento de clique nas linhas da tabela
                    $('#table-transacoes tbody').on('click', 'tr.transaction-row', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        var transactionId = $(this).data('transaction-id');
                        if (transactionId) {
                            carregarDetalhesTransacao(transactionId);
                        }
                    });

                    // Função para carregar detalhes da transação
                    window.carregarDetalhesTransacao = function(transactionId) {
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
                                    alert('Erro ao carregar detalhes da transação: ' + (response.message || 'Erro desconhecido'));
                                }
                            },
                            error: function(xhr) {
                                console.error('Erro ao carregar detalhes:', xhr);
                                alert('Erro ao carregar detalhes da transação');
                            }
                        });
                    }

                    // Função para preencher o modal com os dados
                    window.preencherModal = function(dados) {
                        console.log('Dados recebidos:', dados);
                        
                        // Barra de resumo - dados corretos
                        $('#modal-valor').text('R$ ' + parseFloat(dados.amount).toLocaleString('pt-BR', {minimumFractionDigits: 2}));
                        $('#modal-produto').text('Depósito');
                        $('#modal-cliente').text(dados.client_name || 'N/A');
                        $('#modal-metodo').text(dados.method === 'pix' ? 'PIX' : dados.method === 'card' ? 'Cartão' : 'Boleto');
                        
                        // Ícone do método - apenas o ícone, não o texto
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
                        $('#modal-valor-detalhes').text('R$ ' + parseFloat(dados.amount).toLocaleString('pt-BR', {minimumFractionDigits: 2}));
                        $('#modal-taxa').text('R$ ' + (parseFloat(dados.amount) - parseFloat(dados.deposito_liquido)).toLocaleString('pt-BR', {minimumFractionDigits: 2}));
                        $('#modal-valor-liquido').text('R$ ' + parseFloat(dados.deposito_liquido).toLocaleString('pt-BR', {minimumFractionDigits: 2}));
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
                        
                        if (confirm('Tem certeza que deseja enviar esta transação para mediação? O valor será bloqueado e ficará pendente para liberação manual.')) {
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

                    // Funções auxiliares
                    window.getStatusText = function(status) {
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

                    window.getStatusClass = function(status) {
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

                    window.getCardBrand = function(cardNumber) {
                        const cleanNumber = cardNumber.replace(/\D/g, '');
                        
                        if (cleanNumber.startsWith('4')) {
                            return { name: 'VISA', color: '#1A1F71' };
                        } else if (cleanNumber.startsWith('5') || cleanNumber.startsWith('2')) {
                            return { name: 'Mastercard', color: '#EB001B' };
                        } else if (cleanNumber.startsWith('3')) {
                            return { name: 'American Express', color: '#006FCF' };
                        } else if (cleanNumber.startsWith('6')) {
                            return { name: 'ELO', color: '#FFD700' };
                        } else {
                            return { name: 'Cartão', color: '#6c757d' };
                        }
                    }
                });
            </script>

        </div>
    </div>

    <!-- Modal Detalhes da Transação -->
    <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background-color: #1a1a1a; color: white; border-radius: 15px;">
                <div class="modal-header" style="border-bottom: 1px solid #333; padding: 20px;">
                    <h5 class="modal-title" id="detalhesModalLabel" style="color: white; font-weight: bold;">
                        <i class="fa fa-times me-2" style="cursor: pointer;" data-bs-dismiss="modal"></i>
                        Detalhes da transação
                    </h5>
                    <button type="button" class="btn btn-warning me-2" id="mediacaoBtn" style="background-color: #ffc107; border: none; border-radius: 8px;">
                        <i class="fa fa-gavel me-1"></i> Mediação
                    </button>
                    <button type="button" class="btn btn-danger" id="estornarBtn" style="background-color: #dc3545; border: none; border-radius: 8px;">
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
                        <span id="modal-status" class="badge" style="font-size: 16px; padding: 10px 20px; border-radius: 20px;">APROVADA</span>
                    </div>

                    <div class="row">
                        <!-- Coluna Esquerda - Histórico -->
                        <div class="col-md-6">
                            <h6 style="color: #fff; margin-bottom: 15px;">Histórico</h6>
                            <div style="background-color: #2a2a2a; padding: 15px; border-radius: 10px;">
                                <div class="d-flex align-items-center mb-2">
                                    <span id="modal-status-historico" class="badge me-2" style="background-color: #28a745;">APROVADA</span>
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
                                    <strong>Valor:</strong> <span id="modal-valor-detalhes" style="color: #ccc;">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Taxa:</strong> <span id="modal-taxa" style="color: #ccc;">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Valor Líquido:</strong> <span id="modal-valor-liquido" style="color: #ccc;">-</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Cadastro:</strong> <span id="modal-data-cadastro" style="color: #ccc;">-</span>
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
                                    <strong>Status:</strong> <span id="modal-assinatura-status" class="badge" style="background-color: #28a745;">ATIVA</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Cadastro:</strong> <span id="modal-assinatura-data" style="color: #ccc;">-</span>
                                </div>
                            </div>

                            <h6 style="color: #fff; margin: 20px 0 15px 0;">Dados do pagamento</h6>
                            <div id="modal-dados-pagamento" style="background-color: #2a2a2a; padding: 15px; border-radius: 10px;">
                                <div class="d-flex align-items-center mb-2">
                                    <div style="width: 40px; height: 25px; background: linear-gradient(45deg, #1e3c72, #2a5298); border-radius: 4px; margin-right: 10px; display: flex; align-items: center; justify-content: center;">
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

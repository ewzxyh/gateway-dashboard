<x-app-layout :route="'[ADMIN] Transações'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="glassmorphism-title display-5">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Saques pendentes
                    </h1>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="glassmorphism-card">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table id="table-aprovar-saques" class="table text-nowrap glassmorphism-table">
                                    <thead>
                                        <tr>
                                            <th scope="col"><i class="fas fa-user me-1"></i>Cliente</th>
                                            <th scope="col"><i class="fas fa-tag me-1"></i>Tipo</th>
                                            <th scope="col"><i class="fas fa-key me-1"></i>Chave / Endereço</th>
                                            <th scope="col"><i class="fas fa-dollar-sign me-1"></i>Valor Total</th>
                                            <th scope="col"><i class="fas fa-coins me-1"></i>Valor Liquido</th>
                                            <th scope="col"><i class="fas fa-info-circle me-1"></i>Status</th>
                                            <th scope="col"><i class="fas fa-calendar me-1"></i>Data</th>
                                            <th scope="col"><i class="fas fa-cogs me-1"></i>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($saques)
                                        @foreach($saques as $row)
                                        @php
                                        // Ajustar a exibição do status
                                        switch ($row->status) {
                                        case 'COMPLETED':
                                        $statusBadge = 'bg-success';
                                        $statusText = 'Aprovado';
                                        break;
                                        case 'PENDING':
                                        $statusBadge = 'bg-warning';
                                        $statusText = 'Pendente';
                                        break;
                                        default:
                                        $statusBadge = 'bg-danger';
                                        $statusText = 'Cancelado';
                                        }
                                        @endphp
                                        <tr>
                                            <td>{{ $row->user_id }}</td>
                                            <td>{{ $row->type }}</td>
                                            <td>{{ $row->pix }}</td>
                                            <td>R$ {{ number_format($row->amount, '2', ',', '.') }}</td>
                                            <td>R$ {{ number_format($row->cash_out_liquido, '2', ',', '.') }}</td>
                                            <td><span class="badge glassmorphism-badge {{ $statusBadge }}">{{ $statusText }}</span></td>
                                            <td>{{ \Carbon\Carbon::parse($row->date)->format('d/m/Y \à\s H:i:s') }}</td>
                                            <td>
                                                <button class="glassmorphism-btn glassmorphism-btn-success btn-sm me-1" data-bs-toggle="modal" data-bs-target="#aprovarModal-{{ $row->id }}">
                                                    <i class="fas fa-check me-1"></i>Aprovar
                                                </button>
                                                <button class="glassmorphism-btn glassmorphism-btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejeitarModal-{{ $row->id }}">
                                                    <i class="fas fa-times me-1"></i>Rejeitar
                                                </button>                                               
                                            </td>
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
        </div>
    </div>

 @if($saques)
   @foreach($saques as $row)
  
  		<!-- Modal Aprovar Saque -->
        <form method="POST" action="{{ route('admin.saques.aprovar', ['id' => $row->id ]) }}">
        	@csrf
            @method('PUT')
            <div class="modal fade" id="aprovarModal-{{ $row->id }}" tabindex="-1" aria-labelledby="-{{ $row->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
                        <div class="modal-header" style="background: rgba(40, 167, 69, 0.1); border: none; border-radius: 20px 20px 0 0;">
                            <h5 class="modal-title fw-bold" id="aprovarModalLabel-{{ $row->id }}" style="color: #28a745; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                <i class="fas fa-check-circle me-2"></i>Aprovar Saque
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 8px; backdrop-filter: blur(10px);"></button>
                        </div>
                        <div class="modal-body text-center py-4" style="background: rgba(255, 255, 255, 0.05);">
                            <div class="mb-3">
                                <i class="fas fa-question-circle" style="font-size: 3rem; color: #28a745; opacity: 0.8;"></i>
                            </div>
                            <p class="mb-3" style="font-size: 1.1rem; color: #333;">Você tem certeza que deseja <strong style="color: #28a745;">aprovar</strong> este saque?</p>
                            <div class="alert" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2); border-radius: 10px; color: #155724;">
                                <small><i class="fas fa-info-circle me-1"></i>Esta ação não poderá ser desfeita.</small>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center" style="background: rgba(255, 255, 255, 0.05); border: none; border-radius: 0 0 20px 20px; padding: 20px;">
                            <button type="button" class="glassmorphism-btn me-2" data-bs-dismiss="modal" style="background: rgba(108, 117, 125, 0.2); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.3);">
                                <i class="fas fa-arrow-left me-1"></i>Voltar
                            </button>
                            <button type="submit" class="glassmorphism-btn glassmorphism-btn-success">
                                <i class="fas fa-check me-1"></i>Confirmar Aprovação
                            </button>
                        </div>
                     </div>
                  </div>
                </div>
            </form>

            <!-- Modal Rejeitar Saque -->
            <form method="POST" action="{{ route('admin.saques.rejeitar', ['id' => $row->id ]) }}">
                @csrf
                @method('PUT')
                <div class="modal fade" id="rejeitarModal-{{ $row->id }}" tabindex="-1" aria-labelledby="-{{ $row->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
                            <div class="modal-header" style="background: rgba(220, 53, 69, 0.1); border: none; border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title fw-bold" id="rejeitarModalLabel-{{ $row->id }}" style="color: #dc3545; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                    <i class="fas fa-times-circle me-2"></i>Rejeitar Saque
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 8px; backdrop-filter: blur(10px);"></button>
                            </div>
                            <div class="modal-body text-center py-4" style="background: rgba(255, 255, 255, 0.05);">
                                <div class="mb-3">
                                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #dc3545; opacity: 0.8;"></i>
                                </div>
                                <p class="mb-3" style="font-size: 1.1rem; color: #333;">Você tem certeza que deseja <strong style="color: #dc3545;">rejeitar</strong> este saque?</p>
                                <div class="alert" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); border-radius: 10px; color: #721c24;">
                                    <small><i class="fas fa-exclamation-circle me-1"></i>Esta ação não poderá ser desfeita.</small>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center" style="background: rgba(255, 255, 255, 0.05); border: none; border-radius: 0 0 20px 20px; padding: 20px;">
                                <button type="button" class="glassmorphism-btn me-2" data-bs-dismiss="modal" style="background: rgba(108, 117, 125, 0.2); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.3);">
                                    <i class="fas fa-arrow-left me-1"></i>Voltar
                                </button>
                                <button type="submit" class="glassmorphism-btn glassmorphism-btn-danger">
                                    <i class="fas fa-times me-1"></i>Confirmar Rejeição
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
  @endforeach
  @endif
  
  
    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-aprovar-saques").DataTable({
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
</x-app-layout>

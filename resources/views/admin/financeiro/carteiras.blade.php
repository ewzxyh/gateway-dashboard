<x-app-layout :route="'[ADMIN] Carteiras'">
    
    <div class="main-content app-content">
        <div class="container-fluid">
             <!-- Start::page-header -->
             <div class="mb-4 row justify-content-between align-items-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-wallet me-3" style="font-size: 2rem; color: #667eea;"></i>
                        <h1 class="mb-0 display-5 page-title-glassmorphism">Carteiras</h1>
                    </div>
                    <p class="text-muted mt-2">Gerencie saldos em carteiras e relatórios de usuários</p>
                </div>
            </div>

            <!-- Start:: row-2 -->
            <div class="row">
                <div class="mb-4 col-md-6">
                    <div class="glassmorphism-card">
                        <div class="px-4 py-4" style="min-height: 140px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-success fw-bold">{{ "R$ ".number_format($total_em_carteiras ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text-glassmorphism">Total em carteiras</div>
                                </div>
                                <div class="text-white icon-circle bg-success card-color">
                                    <i class="fas fa-wallet" style="font-size: 1.5rem; color: #28a745;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-4 col-md-6">
                    <div class="glassmorphism-card">
                        <div class="px-4 py-4" style="min-height: 140px">
                            <div class="mb-2 d-flex justify-content-between align-items-center">
                                <div class="me-2">
                                    <div class="display-5 text-info fw-bold">{{ "R$ ".number_format($totalBrutoGateway ?? 0, 2, ',', '.') }}</div>
                                    <div class="card-text-glassmorphism">Total no gateway</div>
                                </div>
                                <div class="text-white icon-circle bg-info card-color">
                                    <i class="fas fa-server" style="font-size: 1.5rem; color: #17a2b8;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End:: row-2 -->

            <!-- Start::row-2 -->
            <div class="mb-4 row">
                <div class="col-xl-12">
                    <div class="glassmorphism-table">
                        <div class="px-4 py-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-trophy me-2" style="color: #ffc107; font-size: 1.2rem;"></i>
                                <h5 class="mb-0 fw-bold" style="color: #667eea;">TOP 3 com mais vendas</h5>
                            </div>
                        </div>
                        <div class="px-4 pb-4">
                            <div class="alert alert-success" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2); border-radius: 12px;">
                                <ul class="mb-0">
                                    @forelse ($top3Users as $topUser)
                                        <li class="mb-2">
                                            <strong>User:</strong> {{ $topUser->user->name }} |
                                            <strong>Saldo:</strong> R$ {{ number_format($topUser->total_amount, 2, ',', '.') }} |
                                        </li>
                                    @empty
                                        <li>Nenhum usuário encontrado</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4 row">
                <div class="col-xl-12">
                    <div class="glassmorphism-table">
                        <div class="px-4 py-3">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-users me-2" style="color: #667eea; font-size: 1.2rem;"></i>
                                <h5 class="mb-0 fw-bold" style="color: #667eea;">Relatório de Usuários</h5>
                            </div>
                        </div>
                        <div class="px-4 pb-4">
                            <div class="table-responsive">
                                <table id="table-carteiras" class="table text-nowrap" style="background: transparent;">
                                    <thead>
                                        <tr style="border-bottom: 2px solid rgba(102, 126, 234, 0.2);">
                                            <th scope="col" style="color: #667eea; font-weight: 600;">User ID</th>
                                            <th scope="col" style="color: #667eea; font-weight: 600;">Faturamento</th>
                                            <th scope="col" style="color: #667eea; font-weight: 600;">Email</th>
                                            <th scope="col" style="color: #667eea; font-weight: 600;">Telefone</th>
                                            <th scope="col" style="color: #667eea; font-weight: 600;">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($usuarios as $usuario)
                                            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
                                                <td style="color: #333; font-weight: 500;">{{ $usuario->user_id }}</td>
                                                <td style="color: #28a745; font-weight: 600;">R$ {{ number_format($usuario->depositos()->where('status','PAID_OUT')->sum('amount'), 2, ',', '.') }}</td>
                                                <td style="color: #333;">{{ $usuario->email }}</td>
                                                <td style="color: #333;">{{ $usuario->telefone }}</td>
                                                <td>
                                                    <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $usuario->telefone) }}"
                                                    target="_blank"
                                                    class="btn btn-sm" 
                                                    style="background: linear-gradient(135deg, #25d366 0%, #128c7e 100%); color: white; border: none; border-radius: 8px; padding: 6px 12px; font-weight: 500; transition: all 0.3s ease;"
                                                    onmouseover="this.style.transform='scale(1.05)'"
                                                    onmouseout="this.style.transform='scale(1)'">WhatsApp</a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" style="text-align: center; color: #666; font-style: italic;">Nenhum registro encontrado</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        $("#table-carteiras").DataTable({
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
                    $('#table-carteiras_filter input[type="search"]').attr('placeholder', 'Pesquisar');
                }
        });
    });
    </script>
</x-app-layout>

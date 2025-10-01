<x-app-layout :route="'Pedidos'">

    <div class="px-0 container-fluid">
        <!-- Título e Filtro -->
        <div class="px-3 mb-5 row align-items-center gy-2 gx-3">
            <div class="col-12 col-lg-8">
                <h5 class="mb-0 display-5 card-title">Pedidos</h5>
            </div>

            <div class="col-12 col-lg-4">
                <form method="GET" action="{{ route('profile.orders') }}" id="filtroCompleto">
                    <div class="row gx-2 gy-2">
                        <div class="col-12 col-md-6">
                            <input type="search"
                                class="form-control form-control-sm"
                                id="buscar"
                                name="buscar"
                                placeholder="Buscar"
                                value="{{ request('buscar') }}"
                                autofocus>
                        </div>

                        <div class="col-12 col-md-6">
                            <select class="form-control form-control-sm" id="status" name="status">
                                <option value="pagos" {{ request('status') == 'pagos' ? 'selected' : '' }}>Pagos</option>
                                <option value="pendentes" {{ request('status') == 'pendentes' ? 'selected' : '' }}>Pendentes</option>
                                <option value="med" {{ request('status') == 'med' ? 'selected' : '' }}>MED</option>
                                <option value="chargeback" {{ request('status') == 'chargeback' ? 'selected' : '' }}>Chargeback</option>
                                <option value="reembolso" {{ request('status') == 'reembolso' ? 'selected' : '' }}>Reembolso</option>
                                <option value="todos" {{ request('status') == 'todos' ? 'selected' : '' }}>Todos</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Cards Resumo -->
<div class="px-3 mb-5 container-fluid">
    <div class="row g-3">
        <!-- Card 1 - Pedidos Pagos -->
        <div class="col-12 col-md-6 col-xxl-3">
            <div class="border-4 card h-100 card-raised card-border-color">
                <div class="p-3 card-body" style="min-height: 114px">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <div class="me-2">
                            <div class="display-5">{{ (clone $orders)->where('status', 'pago')->count() }}</div>
                            <div class="card-text">Pedidos Pagos</div>
                        </div>
                        <div class="text-white icon-circle bg-info card-color">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2 - Pedidos Pendentes -->
        <div class="col-12 col-md-6 col-xxl-3">
            <div class="border-4 card h-100 card-raised card-border-color">
                <div class="p-3 card-body" style="min-height: 114px">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <div class="me-2">
                            <div class="display-5">{{ (clone $orders)->where('status', 'gerado')->count() }}</div>
                            <div class="card-text">Pedidos Pendentes</div>
                        </div>
                        <div class="text-white icon-circle bg-warning card-color">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3 - Valor Pago -->
        <div class="col-12 col-md-6 col-xxl-3">
            <div class="border-4 card h-100 card-raised card-border-color">
                <div class="p-3 card-body" style="min-height: 114px">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <div class="me-2">
                            <div class="display-5">
                                {{ 'R$ ' . number_format((clone $orders)->where('status', 'pago')->sum('valor_total'), 2, ',', '.') }}
                            </div>
                            <div class="card-text">Valor Pago</div>
                        </div>
                        <div class="text-white icon-circle bg-success card-color">
                            <i class="fa-solid fa-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4 - Valor Pendente -->
        <div class="col-12 col-md-6 col-xxl-3">
            <div class="border-4 card h-100 card-raised card-border-color">
                <div class="p-3 card-body" style="min-height: 114px">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <div class="me-2">
                            <div class="display-5">
                                {{ 'R$ ' . number_format((clone $orders)->where('status', 'gerado')->sum('valor_total'), 2, ',', '.') }}
                            </div>
                            <div class="card-text">Valor Pendente</div>
                        </div>
                        <div class="text-white icon-circle bg-warning card-color">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- Tabela Pedidos -->
        <div class="px-3 row">
            <div class="col-12">
                <div class="card card-raised">
                    <div class="card-body table-responsive">
                        <table class="table text-nowrap w-100" id="table-pedidos">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Telefone</th>
                                    <th>Email</th>
                                    <th>Endereço</th>
                                    <th>Valor da compra</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->checkout->produto_name }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->cpf }}</td>
                                        <td>{{ $order->telefone }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>
                                            @if ($order->checkout->produto_tipo == 'fisico')
                                                {{ $order->endereco . ', Nº' . $order->numero . ' ' . $order->bairro . ', ' . $order->cidade . '-' . $order->estado . ' CEP: ' . $order->cep }}
                                            @else
                                                ---
                                            @endif
                                        </td>
                                        <td>{{ 'R$ ' . number_format($order->valor_total, 2, ',', '.') }}</td>
                                        <td>
                                            @if ($order->status == 'pago')
                                                <span class="badge gateway-badge-success">Pago</span>
                                            @else
                                                <span class="badge gateway-badge-warning">Pendente</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y \à\s H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $("#table-pedidos").DataTable({
                responsive: true,
                ordering: false,
                lengthChange: false,
                pageLength: 25,
                searching: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                }
            });

            const input = document.getElementById('buscar');
            const status = document.getElementById('status');

            let timeout = null;
            input.addEventListener('input', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    document.getElementById('filtroCompleto').submit();
                }, 500);
            });

            status.addEventListener('change', function () {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    document.getElementById('filtroCompleto').submit();
                }, 500);
            });
        });
    </script>

</x-app-layout>

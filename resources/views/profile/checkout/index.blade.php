<x-app-layout :route="'Check-out'">

    <div class="main-content app-content">
        <div class="container-fluid px-4 py-6">

            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Produtos</h1>
                        <p class="text-gray-600">Gerencie seus produtos e acompanhe as vendas</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 lg:w-auto w-full">
                        <div class="relative flex-1 lg:w-80">
                            <form method="GET" action="{{route('profile.checkout')}}" id="filtroCompleto">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                                    </div>
                                    <input type="search" 
                                           class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                           id="buscar" 
                                           name="buscar" 
                                           placeholder="Buscar produtos..." 
                                           value="{{ request('buscar') }}" 
                                           autofocus>
                                </div>
                            </form>
                        </div>
                        
                        <button type="button" 
                                class="btn btn-primary w-100 mt-3 mdc-ripple-upgraded flex items-center justify-center gap-2 whitespace-nowrap"
                                data-bs-toggle="modal" 
                                data-bs-target="#addproduto">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            <span>Adicionar produto</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full" id="table-produtos">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Produto
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Preço
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($checkouts as $checkout)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            <img src="{{$checkout->produto_image}}" 
                                                 alt="{{$checkout->produto_name}}"
                                                 class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-gray-900 truncate">
                                                {{ $checkout->produto_name }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 align-middle text-center">
                                    <span class="text-sm font-semibold text-gray-900">
                                        R$ {{ number_format($checkout->produto_valor, 2, ',', '.') }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 align-middle text-center">
                                    @if ($checkout->status)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <div class="w-1.5 h-1.5 bg-green-400 rounded-full mr-1.5"></div>
                                            Ativo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <div class="w-1.5 h-1.5 bg-yellow-400 rounded-full mr-1.5"></div>
                                            Inativo
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 align-middle">
                                    <div class="flex items-center justify-center gap-2">
                                        
                                        <a href="/produtos/visualizar/{{ $checkout->id_unico }}#links"
                                           class="p-2 text-gray-500 rounded-md hover:bg-blue-100 hover:text-blue-600 transition-colors"
                                           title="Ver links"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top">
                                            <i data-lucide="link" class="w-4 h-4"></i>
                                        </a>

                                        <a href="/produtos/visualizar/{{ $checkout->id_unico }}#orders"
                                           class="p-2 text-gray-500 rounded-md hover:bg-green-100 hover:text-green-600 transition-colors"
                                           title="Pedidos"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top">
                                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                        </a>

                                        <a href="{{ route('profile.checkout.produto', ['id' => $checkout->id_unico]) }}"
                                           class="p-2 text-gray-500 rounded-md hover:bg-amber-100 hover:text-amber-600 transition-colors"
                                           title="Editar"
                                           data-bs-toggle="tooltip"
                                           data-bs-placement="top">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </a>
                                        
                                        <button type="button"
                                                class="p-2 text-gray-500 rounded-md hover:bg-red-100 hover:text-red-600 transition-colors"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal-{{$checkout->id}}"
                                                title="Excluir"
                                                data-bs-placement="top">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>

                                    </div>

                                    <div class="modal fade" 
                                         id="editModal-{{$checkout->id}}" 
                                         data-bs-backdrop="static" 
                                         data-bs-keyboard="false" 
                                         tabindex="-1" 
                                         aria-labelledby="editModal-{{$checkout->id}}Label" 
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-xl border-0 shadow-xl">
                                                <div class="modal-header border-b border-gray-200 px-6 py-4">
                                                    <h1 class="modal-title text-lg font-semibold text-gray-900" 
                                                        id="editModal-{{$checkout->id}}Label">
                                                        Excluir produto
                                                    </h1>
                                                    <button type="button" 
                                                            class="btn-close" 
                                                            data-bs-dismiss="modal" 
                                                            aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body px-6 py-4">
                                                    <div class="flex items-center gap-4 mb-4">
                                                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                                             <i data-lucide="alert-triangle" class="text-red-600"></i>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-sm font-medium text-gray-900 mb-1">
                                                                Tem certeza que deseja excluir este produto?
                                                            </h3>
                                                            <p class="text-sm text-gray-600">
                                                                Esta ação não pode ser desfeita.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 rounded-lg p-3">
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $checkout->produto_name }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-t border-gray-200 px-6 py-4 flex gap-3">
                                                    <button type="button" 
                                                            class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-50 transition-colors" 
                                                            data-bs-dismiss="modal">
                                                        Cancelar
                                                    </button>
                                                    <form method="POST" 
                                                          action="{{ route('profile.checkout.delete', ['id'=> $checkout->id]) }}" 
                                                          class="flex-1">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                            Excluir
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addproduto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-xl border-0 shadow-xl">
                <div class="modal-header border-b border-gray-200 px-6 py-4">
                    <h6 class="modal-title text-lg font-semibold text-gray-900">Novo Produto</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                
                <form method="POST" action="{{ route('profile.checkout.create') }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-body px-6 py-6">
                        <div class="space-y-6">
                           </div>
                    </div>
                    
                    <div class="modal-footer border-t border-gray-200 px-6 py-4 flex gap-3">
                        <button type="button" 
                                class="flex-1 bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg font-medium hover:bg-gray-50 transition-colors" 
                                data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg font-medium transition-colors">
                            Cadastrar Produto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Lucide Icons
        lucide.createIcons();

        // Initialize Bootstrap Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // DataTable Initialization
        $("#table-produtos").DataTable({
            responsive: true,
            info: false,
            ordering: false,
            searching: false,
            pageLength: 10,
            lengthChange: true,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
            },
            // MODIFICATION: Changed DOM structure for new layout
            dom: 'rt<"datatable-footer"pl>',
            // MODIFICATION: initComplete callback to style the new controls
            initComplete: function() {
                // Style the footer container
                var footer = document.querySelector('.datatable-footer');
                if (footer) {
                    footer.classList.add('flex', 'items-center', 'justify-between', 'pt-4');
                }

                // Style the length menu label and select input
                var lengthLabel = document.querySelector('.dataTables_length label');
                if (lengthLabel) {
                    lengthLabel.classList.add('flex', 'items-center', 'gap-2', 'text-sm', 'text-gray-600');
                }
                
                var lengthSelect = document.querySelector('select[name="table-produtos_length"]');
                if (lengthSelect) {
                    lengthSelect.classList.add('bg-white', 'border', 'border-gray-300', 'text-gray-700', 'text-sm', 'rounded-lg', 'focus:ring-blue-500', 'focus:border-blue-500', 'w-auto', 'py-1.5', 'pl-2', 'pr-8');
                }
            }
        });
    });
    </script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('buscar');
        input.focus();
        input.select();

        let timeout = null;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                document.getElementById('filtroCompleto').submit();
            }, 500);
        });
    });
    </script>

</x-app-layout>
@php
$setting = \App\Helpers\Helper::getSetting();

function formatarCpfOuCnpj($valor)
{
// Remove tudo que não for número
$numeros = preg_replace('/\D/', '', $valor);

if (strlen($numeros) === 11) {
// Formato CPF
return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $numeros);
} elseif (strlen($numeros) > 11) {
// Formato CNPJ
return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2}).*/", "$1.$2.$3/$4-$5", $numeros);
}

// Retorna apenas os números se não for CPF nem CNPJ esperado
return $numeros;
}


@endphp
<x-app-layout :route="'[ADMIN] Usuários'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="mb-3 md-mb-0 row">
                <div class="mb-3 md-mb-0 col col-12 col-lg-8 text-start">
                    <h1 class="mb-0 display-5">Usuários</h1>
                </div>

                <div class="col col-12 col-lg-4 text-end">
                    <div class="row">
                        <div class="col col-12">
                            <form method="GET" action="{{ route('admin.usuarios') }}" id="filtroCompleto">
                                <div class="row g-2">
                                    <div class="col col-6">
                                        <input type="search" class="form-control" id="buscar" name="buscar"
                                            placeholder="Buscar" value="{{ request('buscar') }}" autofocus>
                                    </div>
                                    <div class="col col-6">
                                        <select class="form-control" id="status" name="status"
                                            onchange="document.getElementById('filtroCompleto').submit()">
                                            <option value="ativos"
                                                {{ request('status') == "ativos" ? 'selected' : '' }}>Ativos</option>
                                            <option value="banidos"
                                                {{ request('status') == "banidos" ? 'selected' : '' }}>Banidos</option>
                                            <option value="pendentes"
                                                {{ request('status') == "pendentes" ? 'selected' : '' }}>Pendentes
                                            </option>
                                            <option value="todos" {{ request('status') == "todos" ? 'selected' : '' }}>
                                                Todos</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start:: row-1 -->
            <div class="row">
                @include('admin.usuarios.partials.card-count', [
                'label' => 'Cadastros totais',
                'info' => (clone $users)->count(),
                'icon' => 'fa-people-group',
                'color' => 'success'
                ])

                @include('admin.usuarios.partials.card-count', [
                'label' => 'Cadastros Mês',
                'info' => (clone $users)->whereBetween('data_cadastro', [now()->startOfMonth(),
                now()->endOfMonth()])->count(),
                'icon' => 'fa-people-group',
                'color' => 'info'
                ])

                @include('admin.usuarios.partials.card-count', [
                'label' => 'Cadastros Pendentes',
                'info' => (clone $users)->where('status', 0)->count(),
                'icon' => 'fa-clock',
                'color' => 'warning'
                ])

                @include('admin.usuarios.partials.card-count', [
                'label' => 'Usuários banidos',
                'info' => (clone $users)->where('banido', 1)->count(),
                'icon' => 'fa-ban',
                'color' => 'danger'
                ])
                {{-- End:: row-1 --}}


                <div class="row">
                    <!-- Adicionado para agrupar os cards corretamente -->
                    @foreach ($users as $user)
                    <div class="mb-3 col-12 col-md-6 col-xl-4">
                        <div class="relative card card-raised glassmorphism-user-card"
                            style="position: relative; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                            <div class="p-3 card-body" style="background: transparent;">
                                <div class="justify-between px-3 mb-3 bg-transparent row align-center">
                                    <div class="col-auto text-lg font-bold text-white d-flex align-items-center justify-content-center"
                                        style="background: url('{{ $user->avatar }}'); background-size: cover; background-position: center; width: 50px; height: 50px; border-radius: 10px;">
                                    </div>
                                    <div class="col ps-3" style="line-height: 15px">
                                        <h4 class="mb-0 font-bold">{{ $user->name }}</h4>
                                        <p class="mb-0">{{ formatarCpfOuCnpj($user->cpf_cnpj) }}</p>
                                        <p class="flex items-center gap-1 text-sm">
                                            @switch($user->permission)
                                            @case(5)
                                            {{-- <div style="width: 8px; height: 8px; background: rgb(255, 123, 0); border-radius:50%; display:inline-block;"></div> --}}
                                            <span style="color: gray;">Gerente</span>
                                            @break

                                            @case(3)
                                            {{-- <div style="width: 8px; height: 8px; background: rgb(255, 0, 0); border-radius:50%; display:inline-block;"></div> --}}
                                            <span style="color: gray;">Administrador</span>
                                            @break

                                            @default
                                            {{-- <div style="width: 8px; height: 8px; background: gray; border-radius:50%; display:inline-block;"></div> --}}
                                            <span style="color: gray;">Cliente</span>
                                            @endswitch
                                        </p>

                                        @if($user->preferred_adquirente && $user->adquirente_override)
                                        @php
                                        $adquirente = $adquirentes->where('referencia',
                                        $user->preferred_adquirente)->first();
                                        @endphp
                                        <p class="text-sm text-muted mb-0">
                                            <i class="fa-solid fa-credit-card me-1"></i>
                                            Adquirente:
                                            <strong>{{ $adquirente->adquirente ?? $user->preferred_adquirente }}</strong>
                                        </p>
                                        @else
                                        <p class="text-sm text-muted mb-0">
                                            <i class="fa-solid fa-cog me-1"></i>
                                            Adquirente: <strong>Padrão do Sistema</strong>
                                        </p>
                                        @endif

                                    </div>
                                </div>

                                <p class="card-text">Vendas últimos 7 dias</p>
                                @php
                                $ultimos7Dias = $user->depositos
                                ->where('user_id')
                                ->where('created_at', '>=', \Carbon\Carbon::now()->subDays(7))
                                ->sum('deposito_liquido');
                                @endphp
                                <h2 class="font-bold card-title">R$ {{ number_format($ultimos7Dias, 2, ',', '.') }}
                                </h2>
                            </div>
                            <!-- Ícones de ação no canto superior direito -->
                            <div class="action-icons"
                                style="position: absolute; top: 15px; right: 15px; display: flex; gap: 8px; flex-wrap: wrap; max-width: 120px; justify-content: flex-end;">
                                <!-- Visualizar -->
                                <button class="action-icon btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#visModal-{{ $user->id }}" title="Visualizar"
                                    style="background: rgba(52, 152, 219, 0.1); border: 1px solid rgba(52, 152, 219, 0.3); border-radius: 6px; padding: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-eye" style="color: #3498db; font-size: 12px;"></i>
                                </button>

                                <!-- Editar -->
                                <button class="action-icon btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal-{{ $user->id }}" title="Editar"
                                    style="background: rgba(46, 204, 113, 0.1); border: 1px solid rgba(46, 204, 113, 0.3); border-radius: 6px; padding: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-edit" style="color: #2ecc71; font-size: 12px;"></i>
                                </button>

                                <!-- Senha -->
                                <button class="action-icon btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#trocarSenhaModal-{{ $user->id }}" title="Alterar Senha"
                                    style="background: rgba(155, 89, 182, 0.1); border: 1px solid rgba(155, 89, 182, 0.3); border-radius: 6px; padding: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-user-lock" style="color: #9b59b6; font-size: 12px;"></i>
                                </button>

                                <!-- Aprovar/Reprovar -->
                                <button class="action-icon btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#aprovarModal-{{ $user->id }}"
                                    title="{{ $user->status == 1 ? 'Reprovar' : 'Aprovar' }}"
                                    style="background: rgba({{ $user->status == 1 ? '243, 156, 18' : '46, 204, 113' }}, 0.1); border: 1px solid rgba({{ $user->status == 1 ? '243, 156, 18' : '46, 204, 113' }}, 0.3); border-radius: 6px; padding: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid {{ $user->status == 1 ? 'fa-ban' : 'fa-check' }}"
                                        style="color: {{ $user->status == 1 ? '#f39c12' : '#2ecc71' }}; font-size: 12px;"></i>
                                </button>

                                <!-- Banir/Desbanir -->
                                <button class="action-icon btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#banModal-{{ $user->id }}"
                                    title="{{ $user->banido == 0 ? 'Banir' : 'Desbanir' }}"
                                    style="background: rgba({{ $user->banido == 0 ? '231, 76, 60' : '46, 204, 113' }}, 0.1); border: 1px solid rgba({{ $user->banido == 0 ? '231, 76, 60' : '46, 204, 113' }}, 0.3); border-radius: 6px; padding: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid {{ $user->banido == 0 ? 'fa-user-slash' : 'fa-user-shield' }}"
                                        style="color: {{ $user->banido == 0 ? '#e74c3c' : '#2ecc71' }}; font-size: 12px;"></i>
                                </button>

                                <!-- Excluir -->
                                <button class="action-icon btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal-{{ $user->id }}" title="Excluir"
                                    style="background: rgba(231, 76, 60, 0.1); border: 1px solid rgba(231, 76, 60, 0.3); border-radius: 6px; padding: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-trash" style="color: #e74c3c; font-size: 12px;"></i>
                                </button>

                                <!-- Taxas -->
                                <button class="action-icon btn btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#taxasModal-{{ $user->id }}" title="Taxas"
                                    style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.3); border-radius: 6px; padding: 6px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-dollar-sign" style="color: #ffc107; font-size: 12px;"></i>
                                </button>
                            </div>

                            <div class="p-2 card-footer"
                                style="background: rgba(255, 255, 255, 0.05); border-top: 1px solid rgba(255, 255, 255, 0.1); border-radius: 0 0 16px 16px;">
                                <table class="table mb-0 table-sm">
                                    <thead>
                                        <tr>
                                            <th
                                                style="font-weight: 700; color: #2c3e50; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                Status</th>
                                            <th
                                                style="font-weight: 700; color: #2c3e50; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                Documento</th>
                                            <th
                                                style="font-weight: 700; color: #2c3e50; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; border-bottom: 2px solid rgba(255, 255, 255, 0.2);">
                                                Criado em</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                @if($user->banido == 0)
                                                <span class="badge gateway-badge-success" style="background-color: green; !important">Ativo</span>
                                                @else
                                                <span
                                                    class="w-10 text-white  badge bg-danger gateway-badge-danger">Bloqueado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->status == 1)
                                                <span class="badge gateway-badge-success" style="background-color: green; !important">Verificado</span>
                                                @else
                                                <span
                                                    class="w-10 text-white badge bg-warning gateway-badge-warning">Análise</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at->format('d/m/Y \à\s H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modal Visualizar -->
                        @include('admin.usuarios.partials.modal-visualizar', ['user' => $user])

                        <!-- Modal Editar -->
                        @include('admin.usuarios.partials.modal-edit', ['user' => $user, 'gerentes' => $gerentes])

                        <!-- Modal Deletar -->
                        @include('admin.usuarios.partials.modal-delete', ['user' => $user])

                        <!-- Modal Frente RG -->
                        @include('admin.usuarios.partials.modal-frente-rg', ['user' => $user])

                        <!-- Modal Verso RG -->
                        @include('admin.usuarios.partials.modal-verso-rg', ['user' => $user])

                        <!-- Modal Selfie RG -->
                        @include('admin.usuarios.partials.modal-selfie-rg', ['user' => $user])

                        <!-- Modal Aprovar -->
                        @include('admin.usuarios.partials.modal-aprovar', ['user' => $user])

                        <!-- Modal Banir -->
                        @include('admin.usuarios.partials.modal-banir', ['user' => $user])

                        <!-- Modal Senha -->
                        @include('admin.usuarios.partials.modal-trocar-senha', ['user' => $user])

                        <!-- Modal Taxas -->
                        @include('admin.usuarios.partials.modal-taxas', ['user' => $user])


                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            $("#table-listar-usuarios").DataTable({
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
                    $('#table-listar-usuarios_filter input[type="search"]').attr('placeholder',
                        'Pesquisar');
                }
            });
        });
        </script>
        <script>
        function gerarChaveSecret(id) {
            let chave = generateUUIDv4();
            document.getElementById(`e-secret-${id}`).value = chave;
        }

        function gerarChaveToken(id) {
            let chave = generateUUIDv4();
            document.getElementById(`e-token-${id}`).value = chave;
        }

        function generateUUIDv4() {
            return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
                (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
            );
        }
        </script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('buscar');
            input.focus();

            let timeout = null;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                if (this.value.length >= 3) {
                    timeout = setTimeout(() => {
                        document.getElementById('filtroCompleto').submit();
                    }, 500);
                }
            });
        });
        </script>

        <style>
        /* Estilos para os ícones de ação */
        .action-icons {
            z-index: 10;
        }

        .action-icon {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            border: none !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            opacity: 0.9;
        }

        .action-icon:active {
            transform: translateY(0);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Responsividade para telas menores */
        @media (max-width: 768px) {
            .action-icons {
                max-width: 100px;
                gap: 6px !important;
            }

            .action-icon {
                width: 28px !important;
                height: 28px !important;
                padding: 4px !important;
            }

            .action-icon i {
                font-size: 10px !important;
            }
        }

        @media (max-width: 576px) {
            .action-icons {
                max-width: 80px;
                gap: 4px !important;
            }

            .action-icon {
                width: 24px !important;
                height: 24px !important;
                padding: 3px !important;
            }

            .action-icon i {
                font-size: 9px !important;
            }
        }

        /* Tooltip personalizado */
        .action-icon[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            white-space: nowrap;
            z-index: 1000;
            pointer-events: none;
        }

        .action-icon[title]:hover::before {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-bottom-color: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            pointer-events: none;
        }
        </style>

</x-app-layout>
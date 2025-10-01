@php
$setting = \App\Helpers\Helper::getSetting();
\App\Helpers\Helper::calculaSaldoLiquido(auth()->user()->user_id);
@endphp

<x-app-layout :route="'Dashboard'">

    <!-- Modal Status -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Atenção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    Você precisa concluir o cadastro para ativar sua conta.
                </div>
                <div class="modal-footer">
                    <a href="{{ url('/enviar-doc') }}" class="btn btn-success">Enviar Documentos</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content app-content">
        <div class="container-fluid">
            @if($status == 0)
            <div class="row mb-4">
                <div class="col-12">
                    {{-- Modern alert card with better visual hierarchy --}}
                    <div class="adobe-glass-card border-start border-warning border-4">
                        <div class="adobe-card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div
                                        class="icon-circle bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                        <i data-lucide="alert-triangle" class="text-warning"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="adobe-title h4 mb-3">Ativação de Conta</h5>
                                    <p class="adobe-text mb-4">Para ativar sua conta, é necessário o envio de
                                        documentos. Por favor, envie os documentos para análise.</p>
                                    <a href="{{ url('/enviar-doc') }}"
                                        class="btn btn-success d-inline-flex align-items-center gap-2">
                                        <i data-lucide="upload" class="icon-sm"></i>
                                        Enviar Documentos
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($status == 5)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="adobe-glass-card border-start border-info border-4">
                        <div class="adobe-card-body p-4">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div class="icon-circle bg-info bg-opacity-10 border border-info border-opacity-25">
                                        <i data-lucide="clock" class="text-info"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="adobe-title h4 mb-3">Sua conta está em Análise</h5>
                                    <p class="adobe-text mb-0">Nossa equipe está analisando seus documentos e logo vai
                                        entrar em contato.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($status == 1)

            <!-- Filtros -->
            <div class="row mb-5 mt-4">
                <div class="col-12">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body p-4">
                            <form method="GET" action="{{ route('dashboard') }}" id="filtroForm">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label adobe-text fw-medium mb-2">
                                            <i data-lucide="package" class="icon-sm me-2"></i>
                                            Produto
                                        </label>
                                        <div class="form-outlined-select position-relative">
                                            <select id="produtoSelect" class="form-select" name="produto"
                                                onchange="document.getElementById('filtroForm').submit()" required>
                                                <option value="todos"
                                                    {{ request('periodo') == 'todos' ? 'selected' : '' }}>Todos</option>
                                                @foreach(auth()->user()->produtos as $produto)
                                                <option value="{{ $produto->id }}"
                                                    {{ request('produto') == $produto->id ? 'selected' : '' }}>
                                                    {{ $produto->produto_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label adobe-text fw-medium mb-2">
                                            <i data-lucide="calendar" class="icon-sm me-2"></i>
                                            Período
                                        </label>
                                        <div class="form-outlined-select position-relative">
                                            <select id="periodoSelect" class="form-select" name="periodo"
                                                onchange="submitPeriod()" required>
                                                <option value="hoje"
                                                    {{ request('periodo') == 'hoje' ? 'selected' : '' }}>Hoje</option>
                                                <option value="ontem"
                                                    {{ request('periodo') == 'ontem' ? 'selected' : '' }}>Ontem</option>
                                                <option value="7dias"
                                                    {{ request('periodo') == '7dias' ? 'selected' : '' }}>Último 7 dias
                                                </option>
                                                <option value="30dias"
                                                    {{ request('periodo') == '30dias' ? 'selected' : '' }}>Último 30
                                                    dias</option>
                                                <option value="tudo"
                                                    {{ request('periodo') == 'tudo' ? 'selected' : '' }}>Sempre</option>
                                                <option value="personalizado">Personalizado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards de Métricas Principais -->
            <div class="row mb-5">
                <div class="col-xl-3 col-md-6 mb-4">
                    {{-- Modern metric card with Lucide wallet icon --}}
                    <div class="adobe-glass-card h-100 hover-lift">
                        <div class="adobe-card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="me-2">
                                    <div class="display-6 text-success adobe-text fw-bold">R$
                                        {{ number_format(auth()->user()->saldo ?? 0, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted fw-medium">Saldo disponível</div>
                                </div>
                                <div class="icon-circle border border-success border-opacity-25">
                                    <i data-lucide="wallet" class="text-success"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 adobe-text-muted small">
                                <i data-lucide="clock" class="icon-xs text-warning"></i>
                                <span class="text-warning fw-medium">Pendente:</span>
                                <span class="text-warning">R$
                                    {{ number_format(auth()->user()->valor_saque_pendente, '2',',','.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    {{-- Updated with trending-up icon --}}
                    <div class="adobe-glass-card h-100 hover-lift">
                        <div class="adobe-card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="me-2">
                                    <div class="display-6 adobe-text fw-bold">R$
                                        {{ number_format((clone $solicitacoes)->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}
                                    </div>
                                    <div class="adobe-text-muted fw-medium">Vendas Realizadas</div>
                                </div>
                                <div class="icon-circle border border-info border-opacity-25">
                                    <i data-lucide="trending-up" class="text-info"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 adobe-text-muted small">
                                <i data-lucide="check-circle" class="icon-xs text-info"></i>
                                <span class="text-info fw-medium">Status:</span>
                                <span class="text-info">Aprovadas</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    {{-- Updated with shopping-cart icon --}}
                    <div class="adobe-glass-card h-100 hover-lift">
                        <div class="adobe-card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="me-2">
                                    <div class="display-6 adobe-text fw-bold">
                                        {{ (clone $solicitacoes)->where('status', 'PAID_OUT')->count() }}</div>
                                    <div class="adobe-text-muted fw-medium">Quantidade de vendas</div>
                                </div>
                                <div class="icon-circle border border-secondary border-opacity-25">
                                    <i data-lucide="shopping-basket" class="text-info"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 adobe-text-muted small">
                                <i data-lucide="hash" class="icon-xs text-secondary"></i>
                                <span class="text-secondary fw-medium">Total:</span>
                                <span class="text-secondary">Transações</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    {{-- Updated with calculator icon --}}
                    <div class="adobe-glass-card h-100 hover-lift">
                        <div class="adobe-card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="me-2">
                                    @php
                                    $paidOutSolicitacoes = (clone $solicitacoes)->where('status', 'PAID_OUT');
                                    $countPaidOut = $paidOutSolicitacoes->count();
                                    $ticketMedio = $countPaidOut > 0 ? $paidOutSolicitacoes->sum('amount') /
                                    $countPaidOut : 0;
                                    @endphp
                                    <div class="display-6 adobe-text fw-bold">R$
                                        {{ number_format($ticketMedio, 2, ',', '.') }}</div>
                                    <div class="adobe-text-muted fw-medium">Ticket médio</div>
                                </div>
                                <div class="icon-circle border border-warning border-opacity-25">
                                    <i data-lucide="calculator" class="text-warning"></i>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 adobe-text-muted small">
                                <i data-lucide="target" class="icon-xs text-warning"></i>
                                <span class="text-warning fw-medium">Média:</span>
                                <span class="text-warning">Por venda</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção Métodos de Pagamento e Estatísticas -->
            <div class="row mb-5">
                <div class="col-lg-8 mb-4">
                    <div class="adobe-glass-card h-100">
                        <div class="adobe-card-body p-4">
                            {{-- Modern section header with icon --}}
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div class="icon-circle bg-opacity-10 border border-primary border-opacity-25">
                                    <i data-lucide="credit-card" class="text-primary"></i>
                                </div>
                                <h5 class="adobe-title mb-0 fw-semibold">Métodos de Pagamento</h5>
                            </div>
                            <div class="table-responsive">
                                <table id="datatablesDash" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="adobe-text fw-bold border-bottom">Meios de Pagamento
                                            </th>
                                            <th scope="col" class="adobe-text fw-bold border-bottom">Aprovação</th>
                                            <th scope="col" class="adobe-text fw-bold border-bottom">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="py-3">
                                                {{-- Modern payment method row with Lucide zap icon for Pix --}}
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="icon-circle border border-success border-opacity-25 icon-sm">
                                                        <i data-lucide="zap" class="text-success icon-xs"></i>
                                                    </div>
                                                    <span class="adobe-text fw-medium">Pix</span>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                @php
                                                $totalFiltradasPix = (clone $solicitacoes)->where('method',
                                                'pix')->count();
                                                $totalAprovadasPix = (clone $solicitacoes)->where('method',
                                                'pix')->where('status', 'PAID_OUT')->count();
                                                $porcentagemAprovadasPix = $totalFiltradasPix > 0 ? ($totalAprovadasPix
                                                / $totalFiltradasPix) * 100 : 0;
                                                @endphp
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                    {{ number_format($porcentagemAprovadasPix, 2, ',', '.') }}%
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <p class="adobe-text fw-semibold mb-0">R$
                                                    {{ number_format((clone $solicitacoes)->where('method', 'pix')->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3">
                                                {{-- Updated with file-text icon for Boleto --}}
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="icon-circle border border-info border-opacity-25 icon-sm">
                                                        <i data-lucide="file-text" class="text-info icon-xs"></i>
                                                    </div>
                                                    <span class="adobe-text fw-medium">Boleto</span>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                @php
                                                $totalFiltradasBoleto = (clone $solicitacoes)->where('method',
                                                'billet')->count();
                                                $totalAprovadasBoleto = (clone $solicitacoes)->where('method',
                                                'billet')->where('status', 'PAID_OUT')->count();
                                                $porcentagemAprovadasBoleto = $totalFiltradasBoleto > 0 ?
                                                ($totalAprovadasBoleto / $totalFiltradasBoleto) * 100 : 0;
                                                @endphp
                                                <span
                                                    class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">
                                                    {{ number_format($porcentagemAprovadasBoleto, 2, ',', '.') }}%
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <p class="adobe-text fw-semibold mb-0">R$
                                                    {{ number_format((clone $solicitacoes)->where('method', 'billet')->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="py-3">
                                                {{-- Updated with credit-card icon --}}
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="icon-circle border border-primary border-opacity-25 icon-sm">
                                                        <i data-lucide="credit-card" class="text-primary icon-xs"></i>
                                                    </div>
                                                    <span class="adobe-text fw-medium">Cartão</span>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                @php
                                                $totalFiltradasCartao = (clone $solicitacoes)->where('method',
                                                'card')->count();
                                                $totalAprovadasCartao = (clone $solicitacoes)->where('method',
                                                'card')->where('status', 'PAID_OUT')->count();
                                                $porcentagemAprovadasCartao = $totalFiltradasCartao > 0 ?
                                                ($totalAprovadasCartao / $totalFiltradasCartao) * 100 : 0;
                                                @endphp
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                                    {{ number_format($porcentagemAprovadasCartao, 2, ',', '.') }}%
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                <p class="adobe-text fw-semibold mb-0">R$
                                                    {{ number_format((clone $solicitacoes)->where('method', 'card')->where('status', 'PAID_OUT')->sum('amount') ?? 0, 2, ',', '.') }}
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="adobe-glass-card h-100">
                        <div class="adobe-card-body p-4">
                            {{-- Modern statistics header --}}
                            <div class="d-flex align-items-center gap-3 mb-4">
                                <div
                                    class="icon-circle bg-secondary bg-opacity-10 border border-secondary border-opacity-25">
                                    <i data-lucide="chart-no-axes-column" class="text-primary icon-xs"></i>
                                </div>
                                <h5 class="adobe-title mb-0 fw-semibold">Estatísticas</h5>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    {{-- Modern statistic cards with better icons --}}
                                    <div class="adobe-glass-card bg-light bg-opacity-50">
                                        <div class="adobe-card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="icon-circle border border-danger border-opacity-25 icon-sm">
                                                        <i data-lucide="user-x" class="text-danger icon-xs"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="adobe-text mb-1 fw-medium">Abandono C.</h6>
                                                        <p id="label-abandono" class="h6 fw-bold adobe-text mb-0">0%</p>
                                                    </div>
                                                </div>
                                                <button onclick="setHidden('vis-abandono', 'label-abandono','0%')"
                                                    id="vis-abandono" class="btn btn-sm btn-outline-secondary border-0">
                                                    <i data-lucide="eye" class="icon-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="adobe-glass-card bg-light bg-opacity-50">
                                        <div class="adobe-card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="icon-circle border border-warning border-opacity-25 icon-sm">
                                                        <i data-lucide="rotate-ccw" class="text-warning icon-xs"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="adobe-text mb-1 fw-medium">Reembolso</h6>
                                                        <p id="label-reembolso" class="h6 fw-bold adobe-text mb-0">0%
                                                        </p>
                                                    </div>
                                                </div>
                                                <button onclick="setHidden('vis-reembolso', 'label-reembolso','0%')"
                                                    id="vis-reembolso"
                                                    class="btn btn-sm btn-outline-secondary border-0">
                                                    <i data-lucide="eye" class="icon-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="adobe-glass-card bg-light bg-opacity-50">
                                        <div class="adobe-card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="icon-circle border border-danger border-opacity-25 icon-sm">
                                                        <i data-lucide="alert-circle" class="text-danger icon-xs"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="adobe-text mb-1 fw-medium">Charge Back</h6>
                                                        <p id="label-chargeback" class="h6 fw-bold adobe-text mb-0">0%
                                                        </p>
                                                    </div>
                                                </div>
                                                <button onclick="setHidden('vis-chargeback', 'label-chargeback','0%')"
                                                    id="vis-chargeback"
                                                    class="btn btn-sm btn-outline-secondary border-0">
                                                    <i data-lucide="eye" class="icon-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="adobe-glass-card bg-light bg-opacity-50">
                                        <div class="adobe-card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div
                                                        class="icon-circle border border-info border-opacity-25 icon-sm">
                                                        <i data-lucide="activity" class="text-info icon-xs"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="adobe-text mb-1 fw-medium">MED</h6>
                                                        <p id="label-med" class="h6 fw-bold adobe-text mb-0">0%</p>
                                                    </div>
                                                </div>
                                                <button onclick="setHidden('vis-med', 'label-med','0%')" id="vis-med"
                                                    class="btn btn-sm btn-outline-secondary border-0">
                                                    <i data-lucide="eye" class="icon-xs"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção Gráfico e Cards Laterais -->
            <div class="row mb-5">
                <div class="col-lg-8 mb-4">
                    <div class="adobe-glass-card h-100">
                        <div class="adobe-card-body p-4">
                            {{-- Modern chart header with growth indicator --}}
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle border border-success border-opacity-25">
                                        <i data-lucide="trending-up" class="text-success"></i>
                                    </div>
                                    <h5 class="adobe-title mb-0 fw-semibold">Estatísticas de Vendas</h5>
                                </div>
                                <div
                                    class="d-flex align-items-center gap-2 px-3 py-1 bg-success bg-opacity-10 border border-success border-opacity-25 rounded">
                                    <i data-lucide="arrow-up-right" class="text-success icon-xs"></i>
                                    <span class="text-success fw-semibold small">+12.5%</span>
                                </div>
                            </div>
                            <div id="areaChart"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="row g-3">
                        <div class="col-12">
                            {{-- Modern metric cards with better icons --}}
                            <div class="adobe-glass-card">
                                <div class="adobe-card-body p-3 text-center">
                                    <div class="icon-circle mx-auto mb-3 border border-primary border-opacity-25">
                                        <i data-lucide="trending-up" class="text-primary"></i>
                                    </div>
                                    <h6 class="adobe-title mb-1 fw-semibold">Crescimento</h6>
                                    <p class="adobe-text-muted mb-0 small">Este mês</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="adobe-glass-card">
                                <div class="adobe-card-body p-3 text-center">
                                    <div class="icon-circle mx-auto mb-3 border border-success border-opacity-25">
                                        <i data-lucide="users" class="text-success"></i>
                                    </div>
                                    <h6 class="adobe-title mb-1 fw-semibold">Clientes Ativos</h6>
                                    <p class="adobe-text-muted mb-0 small">{{ number_format(rand(150, 300)) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="adobe-glass-card">
                                <div class="adobe-card-body p-3 text-center">
                                    <div class="icon-circle mx-auto mb-3 border border-warning border-opacity-25">
                                        <i data-lucide="star" class="text-warning"></i>
                                    </div>
                                    <h6 class="adobe-title mb-1 fw-semibold">Avaliação</h6>
                                    <p class="adobe-text-muted mb-0 small">4.8/5.0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seção Timeline -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body p-4">
                            {{-- Modern timeline header --}}
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="icon-circle bg-opacity-10 border border-info border-opacity-25">
                                        <i data-lucide="clock" class="text-info"></i>
                                    </div>
                                    <h5 class="adobe-title mb-0 fw-semibold">Últimas Transações</h5>
                                </div>
                                <div
                                    class="d-flex align-items-center gap-2 px-3 py-1 bg-opacity-10 border border-info border-opacity-25 rounded">
                                    <i data-lucide="hash" class="text-info icon-xs"></i>
                                    <span class="text-info fw-semibold small">{{ count($ultimasTransacoes) }}
                                        transações</span>
                                </div>
                            </div>
                            <div class="timeline">
                                @foreach($ultimasTransacoes as $row)
                                @php
                                $isPayment = isset($row->beneficiaryname);
                                $data = isset($row->date) ? \Carbon\Carbon::parse($row->date) :
                                \Carbon\Carbon::parse($row->date);
                                $valor = isset($row->amount) ? $row->amount : $row->cash_out_liquido;
                                @endphp
                                {{-- Modern timeline item with better visual hierarchy --}}
                                <div class="timeline-item adobe-glass-card mb-3 bg-light bg-opacity-25 hover-lift">
                                    <div class="adobe-card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center gap-3">
                                                @if($isPayment)
                                                <div class="icon-circle border border-warning border-opacity-25">
                                                    <i data-lucide="arrow-up-right" class="text-warning"></i>
                                                </div>
                                                @else
                                                <div class="icon-circle border border-success border-opacity-25">
                                                    <i data-lucide="arrow-down-left" class="text-success"></i>
                                                </div>
                                                @endif
                                                <div>
                                                    @if($isPayment)
                                                    <div class="fw-semibold text-warning adobe-text">Pagamento realizado
                                                    </div>
                                                    <div class="timeline-date adobe-text-muted small">
                                                        {{ $data->format('d/m/Y \à\s H:i:s') }}</div>
                                                    @else
                                                    <div class="fw-semibold text-success adobe-text">Pagamento recebido
                                                    </div>
                                                    <div class="timeline-date adobe-text-muted small">
                                                        {{ $data->format('d/m/Y \à\s H:i:s') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                @if($isPayment)
                                                <div class="amount-credit text-warning adobe-text fw-bold">- R$
                                                    {{ number_format($valor, 2, ',', '.') }}</div>
                                                <span
                                                    class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">Débito</span>
                                                @else
                                                <div class="amount-credit text-success adobe-text fw-bold">+ R$
                                                    {{ number_format($valor, 2, ',', '.') }}</div>
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Crédito</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards Adicionais -->
            <div class="row mb-5">
                <div class="col-lg-3 col-md-6 mb-4">
                    {{-- Modern service cards with better icons and layout --}}
                    <div class="adobe-glass-card h-100 hover-lift">
                        <div class="adobe-card-body p-4 text-center">
                            <div class="icon-circle mx-auto mb-3 border border-danger border-opacity-25 icon-lg">
                                <i data-lucide="shield-check" class="text-danger"></i>
                            </div>
                            <h6 class="adobe-title mb-2 fw-semibold">Segurança</h6>
                            <p class="adobe-text-muted mb-3 small">Sistema protegido</p>
                            <span
                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Ativo</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="adobe-glass-card h-100 hover-lift">
                        <div class="adobe-card-body p-4 text-center">
                            <div class="icon-circle mx-auto mb-3 border border-secondary border-opacity-25 icon-lg">
                                <i data-lucide="settings" class="text-primary"></i>
                            </div>
                            <h6 class="adobe-title mb-2 fw-semibold">Configurações</h6>
                            <p class="adobe-text-muted mb-3 small">Personalize sua conta</p>
                            <span
                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Disponível</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="adobe-glass-card h-100 hover-lift">
                        <div class="adobe-card-body p-4 text-center">
                            <div class="icon-circle mx-auto mb-3 border border-info border-opacity-25 icon-lg">
                                <i data-lucide="bell" class="text-info"></i>
                            </div>
                            <h6 class="adobe-title mb-2 fw-semibold">Notificações</h6>
                            <p class="adobe-text-muted mb-3 small">{{ rand(3, 12) }} novas</p>
                            <span
                                class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">{{ rand(3, 12) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="adobe-glass-card h-100 hover-lift">
                        <div class="adobe-card-body p-4 text-center">
                            <div class="icon-circle mx-auto mb-3 border border-warning border-opacity-25 icon-lg">
                                <i data-lucide="headphones" class="text-warning"></i>
                            </div>
                            <h6 class="adobe-title mb-2 fw-semibold">Suporte</h6>
                            <p class="adobe-text-muted mb-3 small">24/7 disponível</p>
                            <span
                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">Online</span>
                        </div>
                    </div>
                </div>
            </div>

            @endif
        </div>
    </div>

    <!-- Modal Date Range -->
    <div class="modal fade" id="dateRangeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="adobe-glass-card border-0 rounded-4">
                <div class="adobe-card-body p-4">
                    <h5 class="adobe-title mb-4 fw-semibold">Selecione o período</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3 text-center">
                            <strong class="adobe-text mb-2 d-block">Data de Início</strong>
                            <div class="d-flex justify-content-center" id="calendarInicio"></div>
                        </div>
                        <div class="col-md-6 text-center">
                            <strong class="adobe-text mb-2 d-block">Data de Fim</strong>
                            <div class="d-flex justify-content-center" id="calendarFim"></div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-success" id="btnAplicarDatas">Aplicar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Updated JavaScript to initialize Lucide icons and handle visibility toggles --}}
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Lucide icons
        lucide.createIcons();

        const select = document.getElementById("periodoSelect");
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
                select.value = valor;

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
                select.value = periodo;
            } else {
                const optionToSelect = Array.from(select.options).find(opt => opt.value === periodo);
                if (optionToSelect) {
                    optionToSelect.selected = true;
                }
            }
        }
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // ApexCharts for Area Chart
        var options = {
            chart: {
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            series: [{
                name: "Vendas",
                data: [31, 40, 28, 51, 42, 109, 100]
            }],
            xaxis: {
                type: 'datetime',
                categories: ["2024-01-01T00:00:00Z", "2024-01-02T00:00:00Z", "2024-01-03T00:00:00Z",
                    "2024-01-04T00:00:00Z", "2024-01-05T00:00:00Z", "2024-01-06T00:00:00Z",
                    "2024-01-07T00:00:00Z"
                ],
            },
            colors: ['#206bc4'], // Primary color
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#f1f1f1',
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                },
            },
            tooltip: {
                x: {
                    format: 'dd MMM yyyy'
                },
            },
        };

        var chart = new ApexCharts(document.querySelector("#areaChart"), options);
        chart.render();
    });
    </script>
    <script>
    function setHidden(buttonId, labelId, value) {
        const btn = document.getElementById(buttonId);
        if (!btn) return; // Parar se o botão não existir na página

        const lbl = document.getElementById(labelId);
        const hiddenKey = `hidden-${buttonId}`;

        // Em vez de ler o ícone, lemos o estado salvo para decidir a ação
        const isCurrentlyHidden = localStorage.getItem(hiddenKey) === 'true';

        // Remove o ícone antigo (que agora é um SVG ou o <i> inicial)
        const currentIcon = btn.querySelector('svg') || btn.querySelector('i');
        if (currentIcon) {
            currentIcon.remove();
        }

        // Cria o novo elemento <i> com base no estado desejado
        const newIcon = document.createElement('i');
        newIcon.classList.add('icon-xs'); // Mantém a classe de tamanho

        if (!isCurrentlyHidden) { // Se não estava escondido, vamos esconder
            newIcon.setAttribute('data-lucide', 'eye-off');
            lbl.innerText = '---';
            localStorage.setItem(hiddenKey, 'true');
        } else { // Se estava escondido, vamos mostrar
            newIcon.setAttribute('data-lucide', 'eye');
            lbl.innerText = value;
            localStorage.setItem(hiddenKey, 'false');
        }

        // Adiciona o novo <i> ao botão
        btn.appendChild(newIcon);

        // Reinitialize Lucide icons para renderizar o novo ícone
        lucide.createIcons();
    }

    function restoreVisibility(buttonId, labelId, value) {
        const btn = document.getElementById(buttonId);
        // Adiciona uma verificação para só executar se os elementos existirem na página
        if (!btn) {
            return;
        }

        const lbl = document.getElementById(labelId);
        const hiddenKey = `hidden-${buttonId}`;
        const isHidden = localStorage.getItem(hiddenKey) === 'true';

        // Remove qualquer ícone que já exista dentro do botão
        const currentIcon = btn.querySelector('svg') || btn.querySelector('i');
        if (currentIcon) {
            currentIcon.remove();
        }

        // Cria o novo elemento <i> com base no estado salvo
        const newIcon = document.createElement('i');
        newIcon.classList.add('icon-xs');

        if (isHidden) {
            newIcon.setAttribute('data-lucide', 'eye-off');
            lbl.innerText = '---';
        } else {
            newIcon.setAttribute('data-lucide', 'eye');
            lbl.innerText = value;
        }

        // Adiciona o novo <i> ao botão
        btn.appendChild(newIcon);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Restaura o estado de visibilidade de cada elemento
        restoreVisibility('vis-abandono', 'label-abandono', '0%');
        restoreVisibility('vis-reembolso', 'label-reembolso', '0%');
        restoreVisibility('vis-chargeback', 'label-chargeback', '0%');
        restoreVisibility('vis-med', 'label-med', '0%');

        // Chama o Lucide UMA VEZ no final, para renderizar todos os ícones restaurados
        lucide.createIcons();
    });
    </script>
</x-app-layout>
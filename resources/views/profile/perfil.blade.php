<x-app-layout :route="'Configurações'">
    <style>
        .config-sidebar {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .config-nav-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            margin: 4px 0;
            border-radius: 8px;
            color: #9ca3af;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .config-nav-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }
        
        .config-nav-item.active {
            background: #10b981;
            color: #ffffff;
        }
        
        .config-nav-item i {
            margin-right: 12px;
            width: 20px;
        }
        
        .config-content {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 2rem;
        }
        
        .profile-banner {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .profile-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(16,185,129,0.3)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.6;
        }
        
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            border: 3px solid #10b981;
            background: #000000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #ffffff;
            position: relative;
            z-index: 2;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            background: #10b981;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-item i {
            margin-right: 12px;
            width: 20px;
            color: #10b981;
        }
        
        .form-section {
            display: none;
        }
        
        .form-section.active {
            display: block;
        }

        /* Password Requirements */
        .password-requirements {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #333;
            border-radius: 8px;
            padding: 0.75rem;
            margin-top: 0.5rem;
        }

        .password-requirements h6 {
            margin: 0 0 0.5rem 0;
            font-size: 0.8rem;
            font-weight: 600;
            color: #ffffff;
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 0;
            list-style: none;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }

        .password-requirements li {
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            line-height: 1.4;
            color: #9ca3af;
        }

        .password-requirements li::before {
            content: "✗";
            color: #ef4444;
            font-weight: bold;
            width: 1rem;
            text-align: center;
        }

        .password-requirements li.valid::before {
            content: "✓";
            color: #10b981;
        }

        .password-requirements li.valid {
            color: #10b981;
        }

        .password-toggle {
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .password-toggle:hover {
            color: #ffffff !important;
        }
        
        /* Melhorar contraste dos textos */
        .text-black {
            color: rgba(0, 0, 0, 0.8) !important;
        }
        
        /* Estilos específicos para modo light - TODOS OS TEXTOS PRETOS */
        [data-theme="light"] .text-black {
            color: #000000 !important;
        }
        
        [data-theme="light"] .text-white {
            color: #000000 !important;
        }
        
        [data-theme="light"] .info-card {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        [data-theme="light"] .info-item strong {
            color: #000000 !important;
        }
        
        [data-theme="light"] .info-item span {
            color: #000000 !important;
        }
        
        [data-theme="light"] .info-item {
            color: #000000 !important;
        }
        
        [data-theme="light"] p {
            color: #000000 !important;
        }
        
        [data-theme="light"] small {
            color: #000000 !important;
        }
        
        [data-theme="light"] .config-content {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        [data-theme="light"] .config-sidebar {
            background: rgba(255, 255, 255, 0.9) !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        [data-theme="light"] .config-nav-item {
            color: #4a5568 !important;
        }
        
        [data-theme="light"] .config-nav-item:hover {
            background: rgba(16, 185, 129, 0.1) !important;
            color: #10b981 !important;
        }
        
        [data-theme="light"] .config-nav-item.active {
            background: #10b981 !important;
            color: #ffffff !important;
        }
        
        /* Melhorar contraste dos títulos das seções */
        [data-theme="light"] h6 {
            color: #000000 !important;
        }
        
        [data-theme="light"] h4 {
            color: #000000 !important;
        }
        
        [data-theme="light"] h5 {
            color: #000000 !important;
        }
        
        [data-theme="light"] .info-item {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
        }
        
        /* Garantir que todos os textos sejam pretos no modo light */
        [data-theme="light"] * {
            color: #000000 !important;
        }
        
        /* Forçar contraste específico para elementos problemáticos */
        [data-theme="light"] .info-item strong,
        [data-theme="light"] .info-item span,
        [data-theme="light"] .text-black,
        [data-theme="light"] .text-white,
        [data-theme="light"] p,
        [data-theme="light"] div,
        [data-theme="light"] span {
            color: #000000 !important;
            font-weight: 500 !important;
        }
        
        /* Específico para os valores dos dados */
        [data-theme="light"] .info-item span {
            color: #000000 !important;
            font-weight: 600 !important;
        }
        
        /* Forçar visibilidade máxima para textos das seções */
        [data-theme="light"] .info-card .info-item,
        [data-theme="light"] .info-card .info-item *,
        [data-theme="light"] .info-card strong,
        [data-theme="light"] .info-card span,
        [data-theme="light"] .info-card p,
        [data-theme="light"] .info-card div {
            color: #000000 !important;
            font-weight: 600 !important;
            opacity: 1 !important;
        }
        
        /* Títulos das seções com contraste máximo */
        [data-theme="light"] .info-card h6 {
            color: #000000 !important;
            font-weight: 700 !important;
            font-size: 1.1rem !important;
        }
        
        /* Garantir que não há herança de cores claras */
        [data-theme="light"] .info-card * {
            color: #000000 !important;
        }
        
        /* Exceções para elementos que devem manter cores específicas */
        [data-theme="light"] .config-nav-item.active {
            color: #ffffff !important;
        }
        
        [data-theme="light"] .btn-primary-custom {
            color: #ffffff !important;
        }
        
        [data-theme="light"] .status-badge {
            color: #10b981 !important;
        }
        
        [data-theme="light"] .info-item i {
            color: #10b981 !important;
        }
        
        .btn-primary-custom {
            background: #10b981;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary-custom:hover {
            background: #059669;
            transform: translateY(-1px);
        }
        
        .balance-display {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(16, 185, 129, 0.5);
            border-radius: 8px;
            padding: 12px 16px;
            color: #ffffff;
            font-weight: 600;
        }
        
        /* CORREÇÃO ESPECÍFICA PARA TEXTOS FADED - MAIOR PRIORIDADE */
        [data-theme="light"] .info-card .info-item span.text-black {
            color: #000000 !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }
        
        [data-theme="light"] .info-card .info-item strong.text-black {
            color: #000000 !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }
        
        /* Forçar visibilidade máxima para todos os textos dentro de info-card */
        [data-theme="light"] .info-card .col-md-6 span,
        [data-theme="light"] .info-card .col-md-6 strong {
            color: #000000 !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }
        
        /* Específico para os valores das taxas e informações pessoais */
        [data-theme="light"] .info-card .info-item span,
        [data-theme="light"] .info-card .info-item strong {
            color: #000000 !important;
            font-weight: 700 !important;
            opacity: 1 !important;
        }
        
        /* Garantir que todos os textos sejam pretos e visíveis */
        [data-theme="light"] .info-card * {
            color: #000000 !important;
            font-weight: 600 !important;
            opacity: 1 !important;
        }
        
        /* Exceções para ícones e elementos especiais */
        [data-theme="light"] .info-item i {
            color: #10b981 !important;
        }
        
        [data-theme="light"] .config-nav-item.active {
            color: #ffffff !important;
        }
        
        [data-theme="light"] .btn-primary-custom {
            color: #ffffff !important;
        }
        
        [data-theme="light"] .status-badge {
            color: #10b981 !important;
            background: rgba(16, 185, 129, 0.3) !important;
            font-weight: 700 !important;
        }
        
        /* Melhorar visibilidade do balance-display no modo light */
        [data-theme="light"] .balance-display {
            background: rgba(16, 185, 129, 0.2) !important;
            border: 2px solid #10b981 !important;
            color: #059669 !important;
            font-weight: 700 !important;
        }
        
        [data-theme="light"] .balance-display i {
            color: #059669 !important;
        }
    </style>

    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-lg-3">
                    <div class="config-sidebar">
                        <h5 class="text-black mb-4">Configurações</h5>
                        <div class="config-nav-item active" onclick="showSection('pessoal')">
                            <i class="fas fa-user"></i>
                            Pessoal
                        </div>
                        <div class="config-nav-item" onclick="showSection('seguranca')">
                            <i class="fas fa-lock"></i>
                            Segurança
                        </div>
                        <div class="config-nav-item" onclick="showSection('credenciais')">
                            <i class="fas fa-code"></i>
                            Credenciais
                        </div>
                        <div class="config-nav-item" onclick="showSection('limites')">
                            <i class="fas fa-list"></i>
                            Limites
                        </div>
                    </div>
                </div>

                <!-- Conteúdo Principal -->
                <div class="col-lg-9">
                    <div class="config-content">
                        <!-- Banner do Perfil -->
                        <div class="profile-banner">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="profile-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h4 class="text-white mb-1">{{ strtoupper(auth()->user()->name) }}</h4>
                                        <p class="text-white mb-2">Usuário: {{ auth()->user()->username }}</p>
                                        <span class="status-badge">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Conta ativa
                                        </span>
                                    </div>
                                </div>
                                <div class="balance-display">
                                    <i class="fas fa-wallet me-2"></i>
                                    R$ {{ number_format(auth()->user()->saldo, 2, ',', '.') }} / R$ 100.0K
                                </div>
                            </div>
                        </div>

                        <!-- Seção Pessoal -->
                        <div id="pessoal" class="form-section active">
                            <!-- Informações Pessoais -->
                            <div class="info-card">
                                <h6 class="text-black mb-4">Informações pessoais</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-user"></i>
                                            <div>
                                                <strong class="text-black">Usuário:</strong>
                                                <span class="text-black ms-2">{{ auth()->user()->username }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-id-card"></i>
                                            <div>
                                                <strong class="text-black">Nome:</strong>
                                                <span class="text-black ms-2">{{ strtoupper(auth()->user()->name) }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-file-alt"></i>
                                            <div>
                                                <strong class="text-black">Documento:</strong>
                                                <span class="text-black ms-2">{{ auth()->user()->cpf_cnpj }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-envelope"></i>
                                            <div>
                                                <strong class="text-black">Email:</strong>
                                                <span class="text-black ms-2">{{ auth()->user()->email }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-phone"></i>
                                            <div>
                                                <strong class="text-black">Celular:</strong>
                                                <span class="text-black ms-2">{{ auth()->user()->telefone }}</span>
                                            </div>
                                        </div>
                                    </div>
                    </div>
                </div>

                            <!-- Taxas -->
                            <div class="info-card">
                                <h6 class="text-black mb-4">
                                    Taxas
                                    @if($taxas['sistema_flexivel_ativo'])
                                        <span class="badge bg-info ms-1">Sistema Flexível Ativo</span>
                                        @if($taxas['fonte'] === 'usuario')
                                            <span class="badge bg-warning ms-1">Configuração Personalizada</span>
                                        @else
                                            <span class="badge bg-info ms-1">Configuração Global</span>
                                        @endif
                                    @endif
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        @if($taxas['entrada']['tipo'] === 'flexivel')
                                            <!-- Sistema de Taxas Flexível -->
                                            <div class="info-item">
                                                <i class="fas fa-arrow-down text-success"></i>
                                                <div>
                                                    <strong class="text-black">Entrada (Valores baixos):</strong>
                                                    <span class="text-black ms-2">R$ {{ number_format($taxas['entrada']['valor_baixo'], 2, ',', '.') }} fixo</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-arrow-down text-success"></i>
                                                <div>
                                                    <strong class="text-black">Entrada (Valores altos):</strong>
                                                    <span class="text-black ms-2">{{ number_format($taxas['entrada']['percentual_alto'], 2, ',', '.') }}%</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-arrow-up text-primary"></i>
                                                <div>
                                                    <strong class="text-black">Valor Mínimo:</strong>
                                                    <span class="text-black ms-2">R$ {{ number_format($taxas['entrada']['valor_minimo'], 2, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Sistema Padrão -->
                                            <div class="info-item">
                                                <i class="fas fa-arrow-down text-success"></i>
                                                <div>
                                                    <strong class="text-black">Entrada:</strong>
                                                    <span class="text-black ms-2">{{ number_format($taxas['entrada']['percentual'], 2, ',', '.') }}%</span>
                                                </div>
                                            </div>
                                            <div class="info-item">
                                                <i class="fas fa-cog"></i>
                                                <div>
                                                    <strong class="text-black">Taxa Mínima:</strong>
                                                    <span class="text-black ms-2">R$ {{ number_format($taxas['entrada']['taxa_minima'], 2, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="info-item">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <div>
                                                <strong class="text-black">Saque via Dashboard:</strong>
                                                <span class="text-black ms-2">R$ {{ number_format($taxas['saida']['dashboard'], 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-code"></i>
                                            <div>
                                                <strong class="text-black">Saque via Api:</strong>
                                                <span class="text-black ms-2">{{ number_format($taxas['saida']['api'], 2, ',', '.') }}%</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="fab fa-bitcoin"></i>
                                            <div>
                                                <strong class="text-black">Saque Cripto:</strong>
                                                <span class="text-black ms-2">{{ number_format($taxas['saida']['cripto'], 2, ',', '.') }}%</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-info-circle text-info"></i>
                                            <div>
                                                <strong class="text-black">Taxa Fixa do Usuário:</strong>
                                                <span class="text-black ms-2">R$ {{ number_format($taxas['entrada']['taxa_fixa_adicional'], 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($taxas['sistema_flexivel_ativo'])
                                    <div class="alert alert-info mt-3" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.2); color: #0dcaf0;">
                                        <strong>Como funciona o sistema flexível:</strong><br>
                                        • <strong>Depósitos abaixo de R$ {{ number_format($taxas['entrada']['valor_minimo'], 2, ',', '.') }}:</strong> Taxa fixa de R$ {{ number_format($taxas['entrada']['valor_baixo'], 2, ',', '.') }}<br>
                                        • <strong>Depósitos acima de R$ {{ number_format($taxas['entrada']['valor_minimo'], 2, ',', '.') }}:</strong> Taxa percentual de {{ number_format($taxas['entrada']['percentual_alto'], 2, ',', '.') }}%<br>
                                        • <strong>Taxa fixa adicional:</strong> R$ {{ number_format($taxas['entrada']['taxa_fixa_adicional'], 2, ',', '.') }} (sempre aplicada)
                                        @if($taxas['fonte'] === 'usuario')
                                            <br><br><strong>ℹ️ Você está usando configurações personalizadas de taxa!</strong>
                                        @endif
                                    </div>
                                @elseif($taxas['fonte'] === 'usuario')
                                    <div class="alert alert-warning mt-3" style="background: rgba(255, 193, 7, 0.1); border: 1px solid rgba(255, 193, 7, 0.2); color: #856404;">
                                        <strong>ℹ️ Você está usando configurações personalizadas de taxa!</strong><br>
                                        Suas taxas personalizadas têm prioridade sobre as configurações globais do sistema.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Seção Segurança -->
                        <div id="seguranca" class="form-section">
                            <h5 class="text-black mb-4">Segurança</h5>
                            
                            <!-- 2FA -->
                            <div class="info-card">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-shield-alt text-warning me-3"></i>
                                    <div>
                                        <h6 class="text-black mb-1">Autenticação 2FA</h6>
                                        <span id="2fa-status-badge" class="badge bg-warning text-dark">Verificando...</span>
                                    </div>
                                </div>
                                <p class="text-black mb-3">Com a autenticação de dois fatores, além da sua senha, você precisará de um código gerado por um aplicativo de autenticação, garantindo uma proteção adicional.</p>
                                <div id="2fa-actions">
                                    <button id="btn-2fa-setup" class="btn btn-primary-custom" style="display: none;">Configurar 2FA →</button>
                                    <button id="btn-2fa-disable" class="btn btn-danger" style="display: none;">Desativar 2FA</button>
                                </div>
                            </div>

                            <!-- PIN -->
                            <div class="info-card">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-key text-success me-3"></i>
                                    <div>
                                        <h6 class="text-black mb-1">PIN</h6>
                                        @php
                                            $pinInfo = \App\Traits\PinManagementTrait::getPinInfo(auth()->user());
                                        @endphp
                                        @if($pinInfo['is_active'])
                                            <span class="badge bg-success">Ativo</span>
                                        @elseif($pinInfo['has_pin'])
                                            <span class="badge bg-warning">Inativo</span>
                                        @else
                                            <span class="badge bg-secondary">Não configurado</span>
                                        @endif
                                    </div>
                                </div>
                                <p class="text-black mb-3">Adicione uma camada extra de segurança à sua conta com um PIN. O PIN será necessário para realizar transferências e fazer alterações na conta, garantindo uma proteção adicional.</p>
                                
                                <div class="d-flex gap-2 mb-3">
                                    @if($pinInfo['has_pin'])
                                        @if($pinInfo['is_active'])
                                            <button class="btn btn-outline-warning btn-sm" onclick="togglePin(false)">Desativar</button>
                                        @else
                                            <button class="btn btn-outline-success btn-sm" onclick="togglePin(true)">Ativar</button>
                                        @endif
                                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePinModal">Alterar</button>
                                        <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#removePinModal">Remover</button>
                                    @else
                                        <button class="btn btn-primary-custom" data-bs-toggle="modal" data-bs-target="#createPinModal">Criar PIN</button>
                                    @endif
                                </div>
                                
                                @if($pinInfo['has_pin'])
                                    <small class="text-black">
                                        @if($pinInfo['created_at'])
                                            Criado em: {{ $pinInfo['created_at'] }}
                                        @endif
                                        @if($pinInfo['days_since_creation'])
                                            ({{ $pinInfo['days_since_creation'] }} dias atrás)
                                        @endif
                                    </small>
                                @endif
                            </div>

                            <!-- Senha -->
                            <div class="info-card">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-lock text-white me-3"></i>
                                    <div>
                                        <h6 class="text-black mb-1">Senha de acesso</h6>
                                    </div>
                                </div>
                                <p class="text-black mb-3">Mantenha a segurança da sua conta mantendo sua senha atualizada.</p>
                                <button class="btn btn-primary-custom" onclick="openPasswordModal()">Alterar senha →</button>
                            </div>
                        </div>

                        <!-- Seção Credenciais -->
                        <div id="credenciais" class="form-section">
                            <h5 class="text-black mb-4">Gerenciar Credenciais</h5>
                            <div class="d-flex justify-content-between mb-4">
                                <div></div>
                                <div>
                                    <a href="{{ route('documentacao') }}" class="btn btn-outline-secondary me-2" target="_blank">Documentação API</a>
                                </div>
                                    </div>

                            <div class="info-card">
                                <div class="info-item">
                                    <i class="fas fa-user-lock"></i>
                                    <div>
                                        <strong class="text-black">Client ID:</strong>
                                        <span class="text-black ms-2">{{ auth()->user()->cliente_id ?? 'hkscripts_2737188679518767' }}</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-globe"></i>
                                    <div>
                                        <strong class="text-black">Projeto:</strong>
                                        <span class="text-black ms-2">{{ auth()->user()->username }}</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <strong class="text-black">Gerado Às:</strong>
                                        <span class="text-black ms-2">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-list"></i>
                                    <div>
                                        <strong class="text-black">Descrição:</strong>
                                        <span class="text-black ms-2">{{ auth()->user()->username }} sistema</span>
                                    </div>
                                </div>
                            </div>

                            <h6 class="mb-3 text-black">IP's Permitidos</h6>
                            <p class="text-black mb-3">Endereços liberados para controle do seu financeiro via API.</p>
                            
                            <!-- Lista de IPs -->
                            <div class="info-card text-black" id="ips-list">
                                @php
                                    $allowedIPs = \App\Traits\IPManagementTrait::getAllowedIPs(auth()->user());
                                @endphp
                                
                                @if(count($allowedIPs) > 0)
                                    @foreach($allowedIPs as $ip)
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="">{{ $ip }}</span>
                                            <button class="btn btn-outline-danger btn-sm" onclick="removeIP('{{ $ip }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-black py-3">
                                        <i class="fas fa-info-circle mb-2"></i>
                                        <p>Nenhum IP configurado</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Formulário para adicionar IP -->
                            <form action="{{ route('profile.add-ip') }}" method="POST" class="mt-3">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" name="ip" class="form-control bg-dark text-white border-secondary" 
                                               placeholder="Ex: 192.168.1.1, 192.168.1.0/24, 192.168.1.*" required>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary-custom w-100">+ Adicionar IP</button>
                                    </div>
                                </div>
                                <small class="text-black">Suporte: IP único, CIDR (192.168.1.0/24) ou wildcard (192.168.1.*)</small>
                            </form>
                                </div>

                        <!-- Seção Limites -->
                        <div id="limites" class="form-section">
                            <h5 class="text-black mb-4">Gerenciamento de Limites</h5>
                            <div class="info-card">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-calendar-day"></i>
                                            <div>
                                                <strong class="text-black">Limite Diário:</strong>
                                                <span class="text-black ms-2">R$ {{ number_format(auth()->user()->limite_diario ?? 10000, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-id-card"></i>
                                            <div>
                                                <strong class="text-black">Limite por CPF:</strong>
                                                <span class="text-black ms-2">R$ {{ number_format(auth()->user()->limite_por_cpf ?? 5000, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <i class="fas fa-arrow-up"></i>
                                            <div>
                                                <strong class="text-black">Limite por Saque:</strong>
                                                <span class="text-black ms-2">R$ {{ number_format(auth()->user()->limite_por_saque ?? 10000, 2, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-list-ol"></i>
                                            <div>
                                                <strong class="text-black">Saques por CPF:</strong>
                                                <span class="text-black ms-2">{{ auth()->user()->saques_por_cpf ?? 5 }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function submitAvatarForm() {
            const form = document.getElementById('avatarForm');
            const input = document.getElementById('avatarInput');
            if (input.files.length > 0) {
                form.submit();
            }
        }

        function showSection(sectionName) {
            // Esconder todas as seções
            const sections = document.querySelectorAll('.form-section');
            sections.forEach(section => {
                section.classList.remove('active');
            });

            // Remover active de todos os nav items
            const navItems = document.querySelectorAll('.config-nav-item');
            navItems.forEach(item => {
                item.classList.remove('active');
            });

            // Mostrar a seção selecionada
            document.getElementById(sectionName).classList.add('active');

            // Adicionar active ao nav item clicado
            event.target.classList.add('active');
        }

        function removeIP(ip) {
            if (confirm('Tem certeza que deseja remover este IP?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("profile.remove-ip") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const ipInput = document.createElement('input');
                ipInput.type = 'hidden';
                ipInput.name = 'ip';
                ipInput.value = ip;
                
                form.appendChild(csrfToken);
                form.appendChild(ipInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function togglePin(active) {
            if (confirm(active ? 'Tem certeza que deseja ativar o PIN?' : 'Tem certeza que deseja desativar o PIN?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("profile.toggle-pin") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const activeInput = document.createElement('input');
                activeInput.type = 'hidden';
                activeInput.name = 'active';
                activeInput.value = active ? '1' : '0';
                
                form.appendChild(csrfToken);
                form.appendChild(activeInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // ===== FUNCIONALIDADES DO 2FA =====
        
        // Verificar status do 2FA ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM carregado, verificando status do 2FA');
            check2FAStatus();
        });

        // Verificar status do 2FA
        async function check2FAStatus() {
            try {
                const response = await fetch('/2fa/status');
                const data = await response.json();
                
                update2FAUI(data);
            } catch (error) {
                console.error('Erro ao verificar status do 2FA:', error);
                update2FAUI({ enabled: false, configured: false });
            }
        }

        // Atualizar interface do 2FA
        function update2FAUI(status) {
            const statusBadge = document.getElementById('2fa-status-badge');
            const setupBtn = document.getElementById('btn-2fa-setup');
            const disableBtn = document.getElementById('btn-2fa-disable');

            if (status.enabled) {
                statusBadge.textContent = 'Ativo';
                statusBadge.className = 'badge bg-success';
                setupBtn.style.display = 'none';
                disableBtn.style.display = 'inline-block';
            } else if (status.configured) {
                statusBadge.textContent = 'Inativo';
                statusBadge.className = 'badge bg-warning text-dark';
                setupBtn.textContent = 'Ativar 2FA →';
                setupBtn.style.display = 'inline-block';
                disableBtn.style.display = 'none';
            } else {
                statusBadge.textContent = 'Não configurado';
                statusBadge.className = 'badge bg-secondary';
                setupBtn.textContent = 'Configurar 2FA →';
                setupBtn.style.display = 'inline-block';
                disableBtn.style.display = 'none';
            }
        }

        // Event listeners para os botões
        const setupBtn = document.getElementById('btn-2fa-setup');
        if (setupBtn) {
            setupBtn.addEventListener('click', function() {
                console.log('Botão Configurar 2FA clicado');
                const modal = new bootstrap.Modal(document.getElementById('setup2FAModal'));
                modal.show();
            });
        } else {
            console.error('Botão btn-2fa-setup não encontrado');
        }

        document.getElementById('btn-2fa-disable').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('disable2FAModal'));
            modal.show();
        });

        // Função para gerar QR Code
        async function generateQrCode() {
            const btn = document.getElementById('btn-generate-qr');
            if (!btn) {
                console.error('Botão btn-generate-qr não encontrado');
                return;
            }
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Gerando...';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                console.log('CSRF Token:', csrfToken);
                
                const response = await fetch('/2fa/generate-qr', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (data.success) {
                    // Mostrar QR Code
                    document.getElementById('qr-code-container').innerHTML = data.qr_code;
                    document.getElementById('manual-key').value = data.manual_entry_key;
                    
                    // Mostrar passo 2
                    document.getElementById('2fa-setup-step1').style.display = 'none';
                    document.getElementById('2fa-setup-step2').style.display = 'block';
                } else {
                    showAlert('Erro ao gerar QR Code: ' + (data.message || 'Erro desconhecido'), 'danger');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao gerar QR Code: ' + error.message, 'danger');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Gerar QR Code';
            }
        }

        // Gerar QR Code - usando delegação de eventos
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'btn-generate-qr') {
                console.log('Botão Gerar QR Code clicado');
                generateQrCode();
            }
        });

        // Função para verificar código e ativar 2FA
        async function verifyAndEnable2FA() {
            const code = document.getElementById('verification-code').value;
            
            if (!code || code.length !== 6) {
                showAlert('Digite um código de 6 dígitos', 'warning');
                return;
            }

            const btn = document.getElementById('btn-verify-code');
            if (!btn) {
                console.error('Botão btn-verify-code não encontrado');
                return;
            }
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Verificando...';

            try {
                const response = await fetch('/2fa/enable', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ code: code })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('2FA ativado com sucesso!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('setup2FAModal')).hide();
                    check2FAStatus();
                } else {
                    showAlert(data.message || 'Código inválido', 'danger');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao ativar 2FA', 'danger');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Verificar e Ativar 2FA';
            }
        }

        // Verificar código e ativar 2FA - usando delegação de eventos
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'btn-verify-code') {
                console.log('Botão Verificar Código clicado');
                verifyAndEnable2FA();
            }
        });

        // Função para desativar 2FA
        async function disable2FA() {
            const code = document.getElementById('disable-code').value;
            
            if (!code || code.length !== 6) {
                showAlert('Digite um código de 6 dígitos', 'warning');
                return;
            }

            const btn = document.getElementById('btn-disable-2fa');
            if (!btn) {
                console.error('Botão btn-disable-2fa não encontrado');
                return;
            }
            
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Desativando...';

            try {
                const response = await fetch('/2fa/disable', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ code: code })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('2FA desativado com sucesso!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('disable2FAModal')).hide();
                    check2FAStatus();
                } else {
                    showAlert(data.message || 'Código inválido', 'danger');
                }
            } catch (error) {
                console.error('Erro:', error);
                showAlert('Erro ao desativar 2FA', 'danger');
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Desativar 2FA';
            }
        }

        // Desativar 2FA - usando delegação de eventos
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'btn-disable-2fa') {
                console.log('Botão Desativar 2FA clicado');
                disable2FA();
            }
        });

        // Copiar chave manual para área de transferência
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999);
            document.execCommand('copy');
            showAlert('Chave copiada para área de transferência!', 'success');
        }

        // Mostrar alertas
        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alertDiv);
            
            // Remover automaticamente após 5 segundos
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 5000);
        }

        // Limpar modais ao fechar
        document.getElementById('setup2FAModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('2fa-setup-step1').style.display = 'block';
            document.getElementById('2fa-setup-step2').style.display = 'none';
            document.getElementById('qr-code-container').innerHTML = '';
            document.getElementById('manual-key').value = '';
            document.getElementById('verification-code').value = '';
        });

        document.getElementById('disable2FAModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('disable-code').value = '';
        });
    </script>

    <!-- Modal Criar PIN -->
    <div class="modal fade" id="createPinModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Criar PIN</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.create-pin') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="pin" class="form-label">PIN (6 dígitos)</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="pin" name="pin" maxlength="6" pattern="\d{6}" 
                                   placeholder="000000" required>
                            <div class="form-text text-black">Digite um PIN de 6 dígitos numéricos</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary-custom">Criar PIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Alterar PIN -->
    <div class="modal fade" id="changePinModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Alterar PIN</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.change-pin') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_pin" class="form-label">PIN Atual</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="current_pin" name="current_pin" maxlength="6" pattern="\d{6}" 
                                   placeholder="000000" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_pin" class="form-label">Novo PIN (6 dígitos)</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="new_pin" name="new_pin" maxlength="6" pattern="\d{6}" 
                                   placeholder="000000" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary-custom">Alterar PIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Remover PIN -->
    <div class="modal fade" id="removePinModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Remover PIN</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.remove-pin') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-black mb-3">Digite seu PIN atual para confirmar a remoção:</p>
                        <div class="mb-3">
                            <label for="current_pin_remove" class="form-label">PIN Atual</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="current_pin_remove" name="current_pin" maxlength="6" pattern="\d{6}" 
                                   placeholder="000000" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Remover PIN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Configurar 2FA -->
    <div class="modal fade" id="setup2FAModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Configurar Autenticação 2FA</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="2fa-setup-step1">
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Passo 1: Instalar App Autenticador</h6>
                            <p class="mb-0">Instale um aplicativo autenticador no seu celular:</p>
                            <ul class="mb-0 mt-2">
                                <li><strong>Google Authenticator</strong> (iOS/Android)</li>
                                <li><strong>Microsoft Authenticator</strong> (iOS/Android)</li>
                                <li><strong>Authy</strong> (iOS/Android)</li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <button id="btn-generate-qr" class="btn btn-primary-custom">Gerar QR Code</button>
                        </div>
                    </div>
                    
                    <div id="2fa-setup-step2" style="display: none;">
                        <div class="alert alert-success">
                            <h6><i class="fas fa-qrcode me-2"></i>Passo 2: Escanear QR Code</h6>
                            <p class="mb-0">Escaneie o QR Code abaixo com seu app autenticador:</p>
                        </div>
                        <div class="text-center mb-3">
                            <div id="qr-code-container"></div>
                        </div>
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-key me-2"></i>Chave Manual (se não conseguir escanear)</h6>
                            <div class="input-group">
                                <input type="text" id="manual-key" class="form-control" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('manual-key')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <h6><i class="fas fa-check-circle me-2"></i>Passo 3: Verificar Código</h6>
                            <p class="mb-0">Digite o código de 6 dígitos gerado pelo seu app:</p>
                        </div>
                        <div class="mb-3">
                            <label for="verification-code" class="form-label">Código de Verificação</label>
                            <input type="text" id="verification-code" class="form-control" placeholder="000000" maxlength="6" pattern="[0-9]{6}">
                        </div>
                        <div class="text-center">
                            <button id="btn-verify-code" class="btn btn-success">Verificar e Ativar 2FA</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Desativar 2FA -->
    <div class="modal fade" id="disable2FAModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Desativar Autenticação 2FA</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Atenção!</h6>
                        <p class="mb-0">Desativar o 2FA reduzirá a segurança da sua conta. Para confirmar, digite o código atual do seu app autenticador:</p>
                    </div>
                    <div class="mb-3">
                        <label for="disable-code" class="form-label">Código de Verificação</label>
                        <input type="text" id="disable-code" class="form-control" placeholder="000000" maxlength="6" pattern="[0-9]{6}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="btn-disable-2fa" class="btn btn-danger">Desativar 2FA</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Alteração de Senha -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: #1a1a2e; border: 1px solid #333;">
                <div class="modal-header" style="border-bottom: 1px solid #333;">
                    <h5 class="modal-title text-white" id="passwordModalLabel">Alterar Senha</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('password.update') }}" id="passwordForm">
                        @csrf
                        @method('put')
                        
                        <!-- Senha Atual -->
                        <div class="mb-3">
                            <label for="current_password" class="form-label text-white">Senha Atual</label>
                            <div class="password-container position-relative">
                                <input type="password" 
                                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       required>
                                <button type="button" class="password-toggle position-absolute" onclick="togglePassword('current_password')" style="right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nova Senha -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-white">Nova Senha</label>
                            <div class="password-container position-relative">
                                <input type="password" 
                                       class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                <button type="button" class="password-toggle position-absolute" onclick="togglePassword('password')" style="right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            
                            <!-- Requisitos da Senha -->
                            <div class="password-requirements mt-2" id="passwordRequirements" style="display: none;">
                                <h6 class="text-white mb-2">Requisitos da senha:</h6>
                                <ul class="list-unstyled mb-0">
                                    <li id="req-length">Pelo menos 8 caracteres</li>
                                    <li id="req-lowercase">Uma letra minúscula (a-z)</li>
                                    <li id="req-uppercase">Uma letra maiúscula (A-Z)</li>
                                    <li id="req-number">Um número (0-9)</li>
                                    <li id="req-special">Um caractere especial (@$!%*?&+#^~`|/:";'<>,.=-_[]{}())</li>
                                </ul>
                            </div>
                            
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirmar Nova Senha -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label text-white">Confirmar Nova Senha</label>
                            <div class="password-container position-relative">
                                <input type="password" 
                                       class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required>
                                <button type="button" class="password-toggle position-absolute" onclick="togglePassword('password_confirmation')" style="right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af;">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #333;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="submitPasswordForm()">Alterar Senha</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password validation
        function validatePassword(password) {
            const requirements = {
                length: password.length >= 8,
                lowercase: /[a-z]/.test(password),
                uppercase: /[A-Z]/.test(password),
                number: /\d/.test(password),
                special: /[@$!%*?&+#^~`|\\/:";'<>,.=\-_\[\]{}()]/.test(password)
            };
            
            return requirements;
        }

        function updatePasswordRequirements(password) {
            const requirements = validatePassword(password);
            const requirementsDiv = document.getElementById('passwordRequirements');
            
            // Show/hide requirements div
            if (password.length > 0) {
                requirementsDiv.style.display = 'block';
            } else {
                requirementsDiv.style.display = 'none';
            }
            
            // Update each requirement
            Object.keys(requirements).forEach((key, index) => {
                const element = document.getElementById(`req-${key}`);
                if (element) {
                    if (requirements[key]) {
                        element.classList.add('valid');
                    } else {
                        element.classList.remove('valid');
                    }
                }
            });
        }

        // Password toggle function
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Open password modal
        function openPasswordModal() {
            const modal = new bootstrap.Modal(document.getElementById('passwordModal'));
            modal.show();
        }

        // Submit password form
        function submitPasswordForm() {
            document.getElementById('passwordForm').submit();
        }

        // Add event listeners when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            if (passwordInput) {
                passwordInput.addEventListener('input', function(e) {
                    updatePasswordRequirements(e.target.value);
                });
                
                passwordInput.addEventListener('focus', function(e) {
                    if (e.target.value.length > 0) {
                        updatePasswordRequirements(e.target.value);
                    }
                });
            }
        });
    </script>

</x-app-layout>

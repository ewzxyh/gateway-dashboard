<x-app-layout :route="'[ADMIN] Ajustes Gerais'">

    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-4 row justify-content-between align-items-center">
                <div class="col-12 col-md-8">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-cogs me-3" style="font-size: 2rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        <h1 class="mb-0 display-5 page-title-glassmorphism">Ajustes Gerais</h1>
                    </div>
                    <p class="text-muted mt-2">Configure taxas, informações do gateway e personalizações visuais</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.ajustes.gerais') }}" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Card: Taxas -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card glassmorphism-user-card" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                            <div class="card-header" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px 16px 0 0;">
                                <h5 class="card-title mb-0" style="color: #ffffff; font-weight: 600; text-shadow: none;">Taxas</h5>
                            </div>
                            <div class="card-body" style="background: transparent;">
                                <!-- SEÇÃO: DEPÓSITOS -->
                                <div class="mb-4">
                                    <h6 class="text-black mb-3" style="font-weight: 600; border-bottom: 2px solid rgba(255, 255, 255, 0.2); padding-bottom: 8px;">
                                        <i class="fas fa-arrow-down me-2"></i>Configurações de Depósito
                                    </h6>
                                    <div class="row gy-3">
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="taxa_cash_in_padrao" class="form-label adobe-text fw-semibold">Taxa Percentual Depósito (%)</label>
                                                <input type="text" class="glassmorphism-form-control @error('taxa_cash_in_padrao') is-invalid @enderror" name="taxa_cash_in_padrao" value="{{ $setting->taxa_cash_in_padrao }}" required>
                                                @error('taxa_cash_in_padrao')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Taxa percentual aplicada sobre o valor do depósito</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="taxa_fixa_padrao" class="form-label adobe-text fw-semibold">Taxa Fixa Depósito (R$)</label>
                                                <input type="text" class="glassmorphism-form-control @error('taxa_fixa_padrao') is-invalid @enderror" name="taxa_fixa_padrao" value="{{ $setting->taxa_fixa_padrao }}" required>
                                                @error('taxa_fixa_padrao')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Taxa fixa aplicada em todos os depósitos</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="deposito_minimo" class="form-label adobe-text fw-semibold">Valor Mínimo de Depósito (R$)</label>
                                                <input type="text" class="glassmorphism-form-control @error('deposito_minimo') is-invalid @enderror" name="deposito_minimo" value="{{ $setting->deposito_minimo }}" required>
                                                @error('deposito_minimo')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Valor mínimo que o usuário pode depositar</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- EXPLICAÇÃO DO CÁLCULO DE TRANSFERÊNCIA -->
                                <div class="col-12 mb-4">
                                    <div class="alert alert-info" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.2); color: #0dcaf0;">
                                        <strong>Como funciona o cálculo de taxa de saque PIX:</strong><br>
                                        • <strong>Taxa percentual PIX:</strong> {{ $setting->taxa_cash_out_padrao ?? 2.00 }}% do valor<br>
                                        • <strong>Taxa mínima PIX:</strong> R$ {{ $setting->baseline ?? 0.80 }}<br>
                                        • <strong>Taxa fixa PIX:</strong> R$ {{ $setting->taxa_fixa_pix ?? 0.00 }} (sempre somada)<br>
                                        • <strong>Taxa aplicada:</strong> Maior valor entre taxa percentual e taxa mínima + taxa fixa<br>
                                        • <strong>Exemplo:</strong> R$ 2,00 → 2% = R$ 0,04 < R$ 0,80 → Taxa = R$ 0,80 + R$ {{ $setting->taxa_fixa_pix ?? 0.00 }} = R$ {{ number_format(($setting->baseline ?? 0.80) + ($setting->taxa_fixa_pix ?? 0.00), 2, ',', '.') }}
                                    </div>
                                </div>

                                <!-- SEÇÃO: SAQUES -->
                                <div class="mb-4">
                                    <h6 class="text-black mb-3" style="font-weight: 600; border-bottom: 2px solid rgba(255, 255, 255, 0.2); padding-bottom: 8px;">
                                        <i class="fas fa-arrow-up me-2"></i>Configurações de Saque
                                    </h6>
                                    <div class="row gy-3">
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="taxa_cash_out_padrao" class="form-label adobe-text fw-semibold">Taxa Percentual PIX (%)</label>
                                                <input type="text" class="glassmorphism-form-control @error('taxa_cash_out_padrao') is-invalid @enderror" name="taxa_cash_out_padrao" value="{{ $setting->taxa_cash_out_padrao }}" required>
                                                @error('taxa_cash_out_padrao')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Taxa percentual aplicada sobre o valor do saque PIX</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="baseline" class="form-label adobe-text fw-semibold">Taxa Mínima PIX (R$)</label>
                                                <input type="text" class="glassmorphism-form-control @error('baseline') is-invalid @enderror" name="baseline" value="{{ $setting->baseline }}" required>
                                                @error('baseline')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Valor mínimo de taxa para saques PIX (sempre aplicado se maior que percentual)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="taxa_fixa_pix" class="form-label adobe-text fw-semibold">Taxa Fixa PIX (R$)</label>
                                                <input type="text" class="glassmorphism-form-control @error('taxa_fixa_pix') is-invalid @enderror" name="taxa_fixa_pix" value="{{ $setting->taxa_fixa_pix ?? 0.00 }}" required>
                                                @error('taxa_fixa_pix')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Taxa fixa adicional aplicada sobre saques PIX (sempre somada à taxa principal)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="saque_minimo" class="form-label adobe-text fw-semibold">Valor Mínimo de Saque (R$)</label>
                                                <input type="text" class="glassmorphism-form-control @error('saque_minimo') is-invalid @enderror" name="saque_minimo" value="{{ $setting->saque_minimo }}" required>
                                                @error('saque_minimo')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Valor mínimo que o usuário pode sacar</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="limite_saque_mensal" class="form-label adobe-text fw-semibold">Limite Mensal Pessoa Física (R$)</label>
                                                <input type="text" class="glassmorphism-form-control @error('limite_saque_mensal') is-invalid @enderror" name="limite_saque_mensal" value="{{ $setting->limite_saque_mensal }}" required>
                                                @error('limite_saque_mensal')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Limite máximo de saques por mês para pessoa física</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="taxa_saque_api_padrao" class="form-label adobe-text fw-semibold">Taxa Saque via API (%)</label>
                                                <input type="text" class="glassmorphism-form-control @error('taxa_saque_api_padrao') is-invalid @enderror" name="taxa_saque_api_padrao" value="{{ $setting->taxa_saque_api_padrao ?? 5.00 }}" required>
                                                @error('taxa_saque_api_padrao')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Taxa percentual para saques realizados via API externa</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3">
                                            <div class="taxas-field-container">
                                                <label for="taxa_saque_cripto_padrao" class="form-label adobe-text fw-semibold">Taxa Saque Criptomoedas (%)</label>
                                                <input type="text" class="glassmorphism-form-control @error('taxa_saque_cripto_padrao') is-invalid @enderror" name="taxa_saque_cripto_padrao" value="{{ $setting->taxa_saque_cripto_padrao ?? 1.00 }}" required>
                                                @error('taxa_saque_cripto_padrao')<span class="text-danger">{{ $message }}</span>@enderror
                                                <small class="text-muted">Taxa percentual para saques em criptomoedas (Bitcoin, Ethereum, etc.)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sistema de Taxas Flexível -->
                <div class="row mb-4">
                    <div class="col-xl-12">
                        <div class="card adobe-glass-card">
                            <div class="card-header" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px 16px 0 0;">
                                <h5 class="card-title mb-0" style="color: #ffffff; font-weight: 600; text-shadow: none;">Sistema de Taxas Flexível para Depósitos</h5>
                            </div>
                            <div class="card-body" style="background: transparent;">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="taxa_flexivel_ativa" name="taxa_flexivel_ativa" value="1" {{ $setting->taxa_flexivel_ativa ? 'checked' : '' }}>
                                            <label class="form-check-label adobe-text fw-semibold" for="taxa_flexivel_ativa">
                                                Ativar Sistema de Taxas Flexível
                                            </label>
                                        </div>
                                        <small class="text-muted">Quando ativado, o sistema aplicará taxas diferentes baseadas no valor do depósito</small>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="taxas-field-container">
                                            <label for="taxa_flexivel_valor_minimo" class="form-label adobe-text fw-semibold">Valor Mínimo (R$)</label>
                                            <input type="text" class="glassmorphism-form-control @error('taxa_flexivel_valor_minimo') is-invalid @enderror" name="taxa_flexivel_valor_minimo" value="{{ $setting->taxa_flexivel_valor_minimo ?? 15.00 }}" required>
                                            @error('taxa_flexivel_valor_minimo')<span class="text-danger">{{ $message }}</span>@enderror
                                            <small class="text-muted">Depósitos abaixo deste valor terão taxa fixa</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="taxas-field-container">
                                            <label for="taxa_flexivel_fixa_baixo" class="form-label adobe-text fw-semibold">Taxa Fixa para Valores Baixos (R$)</label>
                                            <input type="text" class="glassmorphism-form-control @error('taxa_flexivel_fixa_baixo') is-invalid @enderror" name="taxa_flexivel_fixa_baixo" value="{{ $setting->taxa_flexivel_fixa_baixo ?? 1.00 }}" required>
                                            @error('taxa_flexivel_fixa_baixo')<span class="text-danger">{{ $message }}</span>@enderror
                                            <small class="text-muted">Taxa fixa para depósitos abaixo do valor mínimo</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-3">
                                        <div class="taxas-field-container">
                                            <label for="taxa_flexivel_percentual_alto" class="form-label adobe-text fw-semibold">Taxa Percentual para Valores Altos (%)</label>
                                            <input type="text" class="glassmorphism-form-control @error('taxa_flexivel_percentual_alto') is-invalid @enderror" name="taxa_flexivel_percentual_alto" value="{{ $setting->taxa_flexivel_percentual_alto ?? 4.00 }}" required>
                                            @error('taxa_flexivel_percentual_alto')<span class="text-danger">{{ $message }}</span>@enderror
                                            <small class="text-muted">Taxa percentual para depósitos acima do valor mínimo</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-info" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.2); color: #0dcaf0;">
                                            <strong>Como funciona:</strong><br>
                                            • <strong>Depósitos abaixo de R$ {{ $setting->taxa_flexivel_valor_minimo ?? 15.00 }}:</strong> Taxa fixa de R$ {{ $setting->taxa_flexivel_fixa_baixo ?? 1.00 }}<br>
                                            • <strong>Depósitos acima de R$ {{ $setting->taxa_flexivel_valor_minimo ?? 15.00 }}:</strong> Taxa percentual de {{ $setting->taxa_flexivel_percentual_alto ?? 4.00 }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Personalização de Relatórios -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card glassmorphism-user-card" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                            <div class="card-header" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px 16px 0 0;">
                                <h5 class="card-title mb-0" style="color: #ffffff; font-weight: 600; text-shadow: none;">
                                    <i class="fas fa-chart-line me-2"></i>Personalização de Relatórios
                                </h5>
                            </div>
                            <div class="card-body" style="background: transparent;">
                                <div class="row">
                                    <!-- RELATÓRIO DE ENTRADAS -->
                                    <div class="col-md-6">
                                        <h6 class="text-black mb-3" style="font-weight: 600; border-bottom: 2px solid rgba(255, 255, 255, 0.2); padding-bottom: 8px;">
                                            <i class="fas fa-arrow-down me-2"></i>Relatório de Entradas
                                        </h6>
                                        <div class="row gy-2">
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_meio" name="relatorio_entradas_mostrar_meio" value="1" {{ ($setting->relatorio_entradas_mostrar_meio ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_meio">Mostrar Meio</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_transacao_id" name="relatorio_entradas_mostrar_transacao_id" value="1" {{ ($setting->relatorio_entradas_mostrar_transacao_id ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_transacao_id">Mostrar Transação ID</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_valor" name="relatorio_entradas_mostrar_valor" value="1" {{ ($setting->relatorio_entradas_mostrar_valor ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_valor">Mostrar Valor</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_valor_liquido" name="relatorio_entradas_mostrar_valor_liquido" value="1" {{ ($setting->relatorio_entradas_mostrar_valor_liquido ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_valor_liquido">Mostrar Valor Líquido</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_nome" name="relatorio_entradas_mostrar_nome" value="1" {{ ($setting->relatorio_entradas_mostrar_nome ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_nome">Mostrar Nome</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_documento" name="relatorio_entradas_mostrar_documento" value="1" {{ ($setting->relatorio_entradas_mostrar_documento ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_documento">Mostrar Documento</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_status" name="relatorio_entradas_mostrar_status" value="1" {{ ($setting->relatorio_entradas_mostrar_status ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_status">Mostrar Status</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_data" name="relatorio_entradas_mostrar_data" value="1" {{ ($setting->relatorio_entradas_mostrar_data ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_data">Mostrar Data</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_entradas_mostrar_taxa" name="relatorio_entradas_mostrar_taxa" value="1" {{ ($setting->relatorio_entradas_mostrar_taxa ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_entradas_mostrar_taxa">Mostrar Taxa</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- RELATÓRIO DE SAÍDAS -->
                                    <div class="col-md-6">
                                        <h6 class="text-black mb-3" style="font-weight: 600; border-bottom: 2px solid rgba(255, 255, 255, 0.2); padding-bottom: 8px;">
                                            <i class="fas fa-arrow-up me-2"></i>Relatório de Saídas
                                        </h6>
                                        <div class="row gy-2">
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_saidas_mostrar_transacao_id" name="relatorio_saidas_mostrar_transacao_id" value="1" {{ ($setting->relatorio_saidas_mostrar_transacao_id ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_saidas_mostrar_transacao_id">Mostrar Transação ID</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_saidas_mostrar_valor" name="relatorio_saidas_mostrar_valor" value="1" {{ ($setting->relatorio_saidas_mostrar_valor ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_saidas_mostrar_valor">Mostrar Valor</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_saidas_mostrar_nome" name="relatorio_saidas_mostrar_nome" value="1" {{ ($setting->relatorio_saidas_mostrar_nome ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_saidas_mostrar_nome">Mostrar Nome</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_saidas_mostrar_chave_pix" name="relatorio_saidas_mostrar_chave_pix" value="1" {{ ($setting->relatorio_saidas_mostrar_chave_pix ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_saidas_mostrar_chave_pix">Mostrar Chave PIX</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_saidas_mostrar_tipo_chave" name="relatorio_saidas_mostrar_tipo_chave" value="1" {{ ($setting->relatorio_saidas_mostrar_tipo_chave ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_saidas_mostrar_tipo_chave">Mostrar Tipo Chave</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_saidas_mostrar_status" name="relatorio_saidas_mostrar_status" value="1" {{ ($setting->relatorio_saidas_mostrar_status ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_saidas_mostrar_status">Mostrar Status</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_saidas_mostrar_data" name="relatorio_saidas_mostrar_data" value="1" {{ ($setting->relatorio_saidas_mostrar_data ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_saidas_mostrar_data">Mostrar Data</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="relatorio_saidas_mostrar_taxa" name="relatorio_saidas_mostrar_taxa" value="1" {{ ($setting->relatorio_saidas_mostrar_taxa ?? true) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-black" for="relatorio_saidas_mostrar_taxa">Mostrar Taxa</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12 mt-4">
                                    <div class="alert alert-info" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.2); color: #0dcaf0;">
                                        <strong>💡 Como funciona:</strong><br>
                                        • Desative os campos que você não quer que apareçam nos relatórios dos usuários<br>
                                        • Os campos desativados ficarão ocultos tanto na visualização quanto na exportação<br>
                                        • Esta configuração é global e afeta todos os usuários do sistema
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Configurações de Segurança -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card glassmorphism-user-card" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                            <div class="card-header" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px 16px 0 0;">
                                <h5 class="card-title mb-0" style="color: #ffffff; font-weight: 600; text-shadow: none;">Configurações de Segurança</h5>
                            </div>
                            <div class="card-body" style="background: transparent;">
                                <div class="row gy-3">
                                    <div class="col-12">
                                        <label for="global_ips" class="form-label adobe-text fw-semibold">IPs Globais Autorizados</label>
                                        <input type="text" class="glassmorphism-form-control @error('global_ips') is-invalid @enderror" 
                                               name="global_ips" 
                                               value="{{ is_array($setting->global_ips) ? implode(', ', $setting->global_ips) : ($setting->global_ips ?? '') }}" 
                                               placeholder="Ex: 54.232.237.217, 192.168.1.100">
                                        @error('global_ips')<span class="text-danger">{{ $message }}</span>@enderror
                                        <small class="text-muted">
                                            <strong>IPs que são autorizados para TODOS os usuários</strong><br>
                                            • Separe múltiplos IPs com vírgula<br>
                                            • Estes IPs funcionam para saques via interface web<br>
                                            • Útil quando o servidor muda de IP
                                        </small>
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-info" style="background: rgba(13, 202, 240, 0.1); border: 1px solid rgba(13, 202, 240, 0.2); color: #0dcaf0;">
                                            <strong>💡 Dica:</strong> Adicione o IP atual do servidor aqui para que os saques via interface web funcionem automaticamente.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Gateway -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card glassmorphism-user-card" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 16px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1); transition: all 0.3s ease;">
                            <div class="card-header" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(5px); border-bottom: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px 16px 0 0;">
                                <h5 class="card-title mb-0" style="color: #ffffff; font-weight: 600; text-shadow: none;">Gerais</h5>
                            </div>
                            <div class="card-body" style="background: transparent;">
                                <div class="row gy-3">
                                    <div class="col-md-6 col-lg-4">
                                        <label for="gateway_name" class="form-label adobe-text fw-semibold">Gateway Name</label>
                                        <input type="text" class="glassmorphism-form-control @error('gateway_name') is-invalid @enderror" name="gateway_name" value="{{ $setting->gateway_name }}" required>
                                        @error('gateway_name')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label for="gateway_color" class="form-label adobe-text fw-semibold">Cor padrão</label>
                                        <input type="color" class="glassmorphism-form-control form-control-color @error('gateway_color') is-invalid @enderror" name="gateway_color" value="{{ $setting->gateway_color }}" required>
                                        @error('gateway_color')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label for="cnpj" class="form-label adobe-text fw-semibold">CNPJ</label>
                                        <input type="text" class="glassmorphism-form-control @error('cnpj') is-invalid @enderror" name="cnpj" value="{{ $setting->cnpj }}" required>
                                        @error('cnpj')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <label for="contato" class="form-label adobe-text fw-semibold">Contato (Gerente)</label>
                                        <input type="text" class="glassmorphism-form-control @error('contato') is-invalid @enderror" name="contato" value="{{ $setting->contato }}" required>
                                        @error('contato')<span class="text-danger">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-image-upload id="gateway_logo" name="gateway_logo" label="Logo" :value="asset($setting->gateway_logo)" />
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <x-image-upload id="gateway_favicon" name="gateway_favicon" label="Icone" :value="asset($setting->gateway_favicon)" />
                                    </div>
                                    <div class="col-12">
                                        <x-image-upload id="gateway_banner_home" name="gateway_banner_home" label="Banner Dashboard" :value="asset($setting->gateway_banner_home)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="adobe-glass-card">
                                    <div class="adobe-card-body">
                                        <div class="row gy-3">
                                            <div class="col-12 text-end mt-4">
                                                <button type="submit" class="adobe-btn-primary">Alterar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>

        </div>
    </div>
</x-app-layout>
<x-app-layout :route="'[ADMIN] Configurações de Saque'">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Configurações de Saque Automático</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.saque-config.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="saque_automatico"
                                                name="saque_automatico" value="1"
                                                {{ $config->saque_automatico ? 'checked' : '' }}>
                                            <label class="form-check-label" for="saque_automatico">
                                                <strong>Saque Automático Global Ativo</strong>
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Quando ativo, saques até o limite configurado são processados
                                            automaticamente.
                                            Valores acima do limite vão para aprovação manual.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="limite_saque_automatico" class="form-label">
                                            <strong>Limite para Saque Automático (R$)</strong>
                                        </label>
                                        <input type="text" class="form-control" id="limite_saque_automatico"
                                            name="limite_saque_automatico"
                                            value="{{ number_format($config->limite_saque_automatico, 2, ',', '.') }}"
                                            placeholder="Ex: 1.000,00">
                                        <small class="form-text text-muted">
                                            Valores até este limite serão processados automaticamente.
                                            Valores acima irão para aprovação manual.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Salvar Configurações
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Voltar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
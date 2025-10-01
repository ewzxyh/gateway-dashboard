<x-app-layout :route="'[ADMIN] Criar transação de entrada'">
    <div class="main-content app-content">
        <div class="container-fluid">

            <div class="row">
                <div class="mb-4 row justify-content-between align-items-center">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-plus-circle" style="color: white; font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h1 class="mb-1 adobe-text fw-bold" style="font-size: 2rem;">Criar Transações de Entrada
                                </h1>
                                <p class="mb-0 adobe-text-muted">Gerencie e crie novas transações de entrada para
                                    usuários</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body p-0">
                            <div class="p-3 d-grid border-bottom border-block-end-dashed">
                                <button class="adobe-btn-primary d-flex align-items-center justify-content-center"
                                    data-bs-toggle="modal" data-bs-target="#addtask"
                                    data-saldo="{{ number_format($saldoliquido, 2, ',', '.') }}">
                                    <i class="align-middle ri-add-circle-line fs-16 me-1"></i> Criar Transação de
                                    entrada
                                </button>
                            </div>

                            <div class="mt-4 glassmorphism-alert alert">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-info-circle me-3 mt-1"
                                        style="color: #0dcaf0; font-size: 1.2rem;"></i>
                                    <div>
                                        <h6 class="adobe-text fw-bold mb-2">Instruções para Criar Transação</h6>
                                        <ul class="adobe-text-muted mb-0" style="list-style: none; padding-left: 0;">
                                            <li class="mb-2"><i class="fas fa-check-circle me-2"
                                                    style="color: #0dcaf0;"></i><strong>Crie pagamentos de
                                                    entradas</strong></li>
                                            <li class="mb-2"><i class="fas fa-check-circle me-2"
                                                    style="color: #0dcaf0;"></i><strong>Escolha o usuário e insira o
                                                    valor do saldo que vai ser inserido</strong></li>
                                            <li><i class="fas fa-check-circle me-2"
                                                    style="color: #0dcaf0;"></i><strong>A descrição vai ficar como
                                                    {{ env('APP_NAME') }}</strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade glassmorphism-modal" id="addtask" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-2">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-plus-circle me-3" style="color: #667eea; font-size: 1.5rem;"></i>
                        <h5 class="modal-title adobe-text fw-bold" id="mail-ComposeLabel">Novo Depósito</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="background: rgba(255, 255, 255, 0.2); border-radius: 50%; padding: 0.5rem;">X</button>
                </div>
                <form id="inserir-entrada" method="POST" action="{{ route('admin.transacoes.addentrada') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="px-4 modal-body pt-3">
                        <div class="row gy-3">

                            <div class="col-xl-12">
                                <label for="user_id" class="form-label adobe-text fw-semibold">Selecionar
                                    Usuário</label>
                                <select class="form-select glassmorphism-form-control" id="user_id" name="user_id"
                                    required>
                                    <option value="">Selecione um usuário</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}">{{ $user->username.' | '.$user->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xl-12">
                                <label for="valor" class="form-label adobe-text fw-semibold">Valor</label>
                                <input type="number" step="0.01" class="form-control glassmorphism-form-control"
                                    id="valor" name="valor" placeholder="Digite o valor" required>
                                <div id="valorError" class="mt-2 text-danger" style="display: none;">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Saldo insuficiente para o valor solicitado.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-2">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal"
                            style="border-radius: 8px;">Cancelar</button>
                        <button type="submit" class="adobe-btn-primary" @if (isset($count) && $count> 0) disabled
                            @endif>
                            <i class="fas fa-check me-2"></i>Adicionar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
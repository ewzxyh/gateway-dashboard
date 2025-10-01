<x-app-layout :route="'[ADMIN] Ajustes de níveis'">

    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5 page-title-glassmorphism">Ajuste de Níveis</h1>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row">
                <div class="mb-3 col-xl-12">
                    <div class="card glassmorphism-switch-card">
                        <div class="card-body">
                            <div class="form-check form-switch">
                                <input class="form-check-input custom-switch-lg" name="niveis_ativo" {{ ($niveis_ativo ?? false) ? 'checked' : '' }} value="{{$niveis_ativo}}" type="checkbox" role="switch" id="switchCheckDefault">
                                <label class="ml-3 text-xl form-check-label" for="switchCheckDefault">Sistema de níveis ativo</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 col-xl-12">
                        <div class="container-clone row">
                            @foreach ($niveis as $nivel)
                                <div class="mb-3 col-md-6 col-xl-4 col-xxl-3 card-nivel" data-id="{{ $nivel->id }}">
                                    <div class="card glassmorphism-nivel-card">
                                        <div class="card-body">
                                            <form class="form-nivel" data-id="{{ $nivel->id }}">
                                                <input type="hidden" name="id" value="{{ $nivel->id }}">
                                                <div class="row">

                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Nome</label>
                                                        <input type="text" class="form-control" name="nome" value="{{ $nivel->nome }}" required>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Mínimo (R$)</label>
                                                        <input type="text" class="form-control" name="minimo" value="{{ $nivel->id == 1 ? 0 : $nivel->minimo }}" {{ $nivel->id == 1 ? 'disabled' : 'required' }}>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Máximo (R$)</label>
                                                        <input type="text" class="form-control" name="maximo" value="{{ $nivel->maximo }}" required>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Cor</label>
                                                        <input type="color" class="form-control" name="cor" value="{{ $nivel->cor }}" required>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <div class="btn-group w-100">
                                                            <button type="button" class="btn glassmorphism-btn glassmorphism-btn-danger btn-excluir"><i class="fa fa-trash"></i>&nbsp;Excluir</button>
                                                            <button type="button" class="btn glassmorphism-btn glassmorphism-btn-success btn-salvar"><i class="fa fa-save"></i>&nbsp;Salvar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Card modelo para clonagem --}}
                            <div id="card-clone" class="mb-3 col-md-6 col-xl-4 col-xxl-3 d-none">
                                <div class="card glassmorphism-nivel-card">
                                    <div class="card-body">
                                        <form class="form-nivel" data-id="">
                                            <div class="row">

                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Nome</label>
                                                    <input type="text" class="form-control" name="nome" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Mínimo (R$)</label>
                                                    <input type="text" class="form-control" name="minimo" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Máximo (R$)</label>
                                                    <input type="text" class="form-control" name="maximo" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Cor</label>
                                                    <input type="color" class="form-control" name="cor" value="{{ $nivel->cor }}" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <div class="btn-group w-100">
                                                        <button type="button" class="btn glassmorphism-btn glassmorphism-btn-danger btn-excluir"><i class="fa fa-trash"></i>&nbsp;Excluir</button>
                                                        <button type="button" class="btn glassmorphism-btn glassmorphism-btn-success btn-salvar"><i class="fa fa-save"></i>&nbsp;Salvar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="clone-card" class="mb-3 col-md-6 col-xl-4 col-xxl-3">
                                <div class="card glassmorphism-nivel-card glassmorphism-btn-add">
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-plus" style="font-size: 36px;"></i>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Glassmorphism para Confirmação de Exclusão -->
    <div class="modal fade glassmorphism-delete-modal" id="deleteNivelModal" tabindex="-1" aria-labelledby="deleteNivelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteNivelModalLabel">
                        <i class="fas fa-trash-alt me-2"></i>Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <p>Você tem certeza que deseja excluir este nível <span class="text-highlight" id="nivelNome"></span>?</p>
                    <p class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-glassmorphism btn-cancel-glass" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-glassmorphism btn-delete-glass" id="confirmDeleteBtn">
                        <i class="fas fa-trash-alt me-2"></i>Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = "/{{env("ADM_ROUTE")}}/ajustes";

        document.addEventListener("DOMContentLoaded", function () {
            const container = document.querySelector('.container-clone');
            const clonador = document.getElementById('clone-card');
            const modelo = document.getElementById('card-clone');

            // Clonagem
            clonador.addEventListener('click', () => {
                const novo = modelo.cloneNode(true);
                novo.classList.remove('d-none');
                novo.removeAttribute('id');
                novo.querySelectorAll('input').forEach(i => i.value = '');
                container.insertBefore(novo, clonador);
            });

            // Delegação de eventos para excluir/salvar
            container.addEventListener('click', function (e) {
                const card = e.target.closest('.card-nivel') || e.target.closest('#card-clone')?.nextElementSibling;
                const form = e.target.closest('.form-nivel');
                if (!form) return;

                const id = form.dataset.id;

                // EXCLUIR
                if (e.target.closest('.btn-excluir')) {
                    if (id) {
                        const nome = form.querySelector('input[name="nome"]').value;
                        excluirNivel(id, nome, card);
                    } else {
                        // Novo (não salvo ainda)
                        card.remove();
                    }
                }

                // SALVAR
                if (e.target.closest('.btn-salvar')) {
                    const formData = new FormData(form);
                    const url = id ? `${baseUrl}/niveis/${id}` : `${baseUrl}/niveis`;
                    const method = 'POST'; // Sempre POST com _method para Laravel

                    if (id) {
                        formData.append('_method', 'PUT');
                    }

                    fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message);
                            if (!id) {
                                form.dataset.id = data.id;
                                card.dataset.id = data.id;
                                form.querySelector('input[name="id"]')?.remove();
                                const inputHidden = document.createElement('input');
                                inputHidden.setAttribute('type', 'hidden');
                                inputHidden.setAttribute('name', 'id');
                                inputHidden.value = data.id;
                                form.appendChild(inputHidden);
                            }
                        } else {
                            showToast('error','Erro ao salvar');
                        }
                    });
                }

            });
        });

        let nivelToDelete = null;

        function excluirNivel(id, nome, card) {
            // Armazenar dados do nível a ser excluído
            nivelToDelete = { id: id, nome: nome, card: card };
            
            // Atualizar o modal com o nome do nível
            document.getElementById('nivelNome').textContent = '"' + nome + '"';
            
            // Mostrar o modal
            const modal = new bootstrap.Modal(document.getElementById('deleteNivelModal'));
            modal.show();
        }

        // Event listener para o botão de confirmação no modal
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (nivelToDelete) {
                fetch(`${baseUrl}/niveis/${nivelToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                }).then(res => {
                    if (res.ok) {
                        // Fechar o modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteNivelModal'));
                        modal.hide();
                        
                        // Remover o card
                        nivelToDelete.card.remove();
                        
                        showToast('success', 'Nível excluído com sucesso');
                    } else {
                        showToast('error','Erro ao excluir');
                    }
                }).catch(error => {
                    console.error('Erro:', error);
                    showToast('error','Erro ao excluir');
                });
                
                // Limpar a variável
                nivelToDelete = null;
            }
        });

        // Limpar a variável quando o modal for fechado
        document.getElementById('deleteNivelModal').addEventListener('hidden.bs.modal', function() {
            nivelToDelete = null;
        });
        </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        let active = document.querySelector('[name="niveis_ativo"]');
        let url = baseUrl + '/active-niveis';

        active.addEventListener('change', function () {
            const formData = new FormData();
            formData.append('niveis_ativo', active.checked ? 1 : 0);

            fetch(url, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            }).then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                } else {
                    showToast('error', 'Erro ao salvar');
                }
            });
        });
    });
    </script>
</x-app-layout>

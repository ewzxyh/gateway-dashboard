<x-app-layout :route="'[ADMIN] Ajutes de gerentes'">
 <!-- Dropzone CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" />

<!-- Dropzone JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
 

    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Start::page-header -->
            <div class="mb-3 row justify-content-between align-items-">
                <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                    <h1 class="mb-0 display-5">Ajustes de gerentes</h1>
                </div>
            </div>

            <!-- Start::row-2 -->
            <div class="row">
                <div class="mb-3 col-xl-12">
                    <div class="card card-raised">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.ajustes.gerais') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3 row align-items-center">
                                    <div class="col-md-7 d-flex align-items-center">
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input custom-switch-lg"
                                                name="gerente_active"
                                                type="checkbox"
                                                role="switch"
                                                id="switchCheckDefault"
                                                value="{{ $gerente_active }}"
                                                {{ ($gerente_active ?? false) ? 'checked' : '' }}>
                                            <label class="text-xl form-check-label ms-2" for="switchCheckDefault">
                                                Sistema de gerente ativo
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label for="gerente_percentage" class="form-label">Porcentagem (%)</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="gerente_percentage"
                                            id="gerente_percentage"
                                            value="{{ $porcentagem }}"
                                            required>
                                    </div>

                                    <div class="pt-4 col-md-2 d-flex align-end">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fa fa-save"></i> Salvar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="mb-3 row justify-content-between align-items-">
                    <div style="display:flex;align-item:center;justify-content:flex-start;" class="mb-0 md-mb-5 col-12 col-md-4 mb-md-0 justify-content-start align-items-center">
                        <h1 class="mb-0 display-5">Material de apoio</h1>
                    </div>
                </div>
                <div class="mb-3 col-xl-12">
                        <div class="container-clone row">
                            @foreach ($apoios as $apoio)
                                <div class="mb-3 col-md-6 col-xl-4 col-xxl-3 card-nivel" data-id="{{ $apoio->id }}">
                                    <div class="card card-raised">
                                        <div class="card-body">
                                            <form class="form-nivel" data-id="{{ $apoio->id }}" enctype="multipart/form-data">
                                                <input type="hidden" name="id" value="{{ $apoio->id }}">
                                                <div class="row">

                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Titulo</label>
                                                        <input type="text" class="form-control" name="titulo" value="{{ $apoio->titulo }}" required>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <label class="form-label">Descrição</label>
                                                        <textarea class="form-control" name="descricao" required>{{ $apoio->descricao }}</textarea>
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <x-image-upload id="imagem-{{$apoio->id}}" name="imagem" label="Imagem" :value="$apoio->imagem" :height="'130px'" />
                                                    </div>
                                                    <div class="mb-2 col-12">
                                                        <div class="btn-group w-100">
                                                            <button type="button" class="btn btn-danger btn-excluir"><i class="fa fa-trash"></i>&nbsp;Excluir</button>
                                                            <button type="button" class="btn btn-success btn-salvar"><i class="fa fa-save"></i>&nbsp;Salvar</button>
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
                                <div class="card card-raised">
                                    <div class="card-body">
                                        <form class="form-nivel" data-id="" enctype="multipart/form-data">
                                            <div class="row">

                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Titulo</label>
                                                    <input type="text" class="form-control" name="titulo" required>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <label class="form-label">Descrição</label>
                                                    <textarea class="form-control" name="descricao" required></textarea>
                                                </div>
                                                <div class="mb-2 col-12">
                                                   {{--  <div class="mb-2 col-12">
                                                        <x-image-upload id="imagem-{{uniqid()}}" name="imagem" label="Imagem" :value="null" :height="'130px'" />
                                                    </div> --}}
                                                    <div class="form-group">
                                                        <label class="form-label">Imagem</label>
                                                        <div class="dropzone dz-wrapper" data-name="imagem" style="min-height: 130px; border: 2px dashed #ccc; padding: 10px;"></div>
                                                    </div>
                                                </div>
                                                <div class="mb-2 col-12">
                                                    <div class="btn-group w-100">
                                                        <button type="button" class="btn btn-danger btn-excluir"><i class="fa fa-trash"></i>&nbsp;Excluir</button>
                                                        <button type="button" class="btn btn-success btn-salvar"><i class="fa fa-save"></i>&nbsp;Salvar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="clone-card" class="mb-3 col-md-6 col-xl-4 col-xxl-3" style="cursor:pointer;">
                                <div class="card card-raised" style="min-height: 376px">
                                    <div class="card-body d-flex align-items-center justify-content-center">
                                        <i class="fa-solid fa-plus" style="font-size: 36px;color:rgb(206, 206, 206);"></i>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = "/{{env("ADM_ROUTE")}}/ajustes";

        Dropzone.autoDiscover = false;

        function initDropzones() {
            document.querySelectorAll('.dropzone:not([data-initialized])').forEach((dzElement, index) => {
                dzElement.dataset.initialized = "true";

                // Cria um novo Dropzone para o elemento
                const dz = new Dropzone(dzElement, {
                    url: '/fake', // Não será usado, envio é manual
                    autoProcessQueue: false,
                    maxFiles: 1,
                    addRemoveLinks: true,
                    acceptedFiles: 'image/*',
                    init: function () {
                        this.on("addedfile", file => {
                            // Armazena o arquivo no dataset do elemento pai
                            dzElement.dataset.file = file.name;
                            dzElement.file = file;
                        });

                        this.on("removedfile", () => {
                            delete dzElement.file;
                        });
                    }
                });

                // Se quiser mostrar imagem antiga:
                const value = dzElement.dataset.value;
                if (value) {
                    const mockFile = { name: "imagem", size: 12345 };
                    dz.emit("addedfile", mockFile);
                    dz.emit("thumbnail", mockFile, value);
                    dz.emit("complete", mockFile);
                    dz.files.push(mockFile);
                }
            });
        }

        // Iniciar os dropzones na carga e após clonagem
        document.addEventListener("DOMContentLoaded", () => {
            initDropzones();

            const container = document.querySelector('.container-clone');
            const clonador = document.getElementById('clone-card');
            const modelo = document.getElementById('card-clone');

            clonador.addEventListener('click', () => {
                const novo = modelo.cloneNode(true);
                novo.classList.remove('d-none');
                novo.removeAttribute('id');

                novo.querySelectorAll('input, textarea').forEach(el => el.value = '');

                // Reset dropzone DOM
                novo.querySelectorAll('.dropzone').forEach(el => {
                    el.innerHTML = '';
                    el.removeAttribute('data-initialized');
                });

                container.insertBefore(novo, clonador);
                initDropzones();
            });

            // Salvar com imagem
            container.addEventListener('click', async function (e) {
                const form = e.target.closest('.form-nivel');
                const card = e.target.closest('.card-nivel') || e.target.closest('#card-clone')?.nextElementSibling;

                if (e.target.closest('.btn-salvar') && form) {
                    const formData = new FormData(form);
                    const dz = form.querySelector('.dropzone');
                    const file = dz?.file;

                    if (file) {
                        formData.append("imagem", file);
                    }

                    const id = form.dataset.id;
                    const url = id ? `${baseUrl}/apoio/${id}` : `${baseUrl}/apoio`;
                    if (id) formData.append('_method', 'PUT');

                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const data = await res.json();
                    if (data.success) {
                        showToast('success', data.message);
                        if (!id) {
                            form.dataset.id = data.id;
                            card.dataset.id = data.id;

                            const hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = 'id';
                            hidden.value = data.id;
                            form.appendChild(hidden);
                        }
                    } else {
                        showToast('error', 'Erro ao salvar');
                    }
                }
            });
        });
        </script>
</x-app-layout>

<x-app-layout :route="'Integrações'">

    <div class="main-content app-content mt-4">
        <div class="container-fluid">
            <div class="row mb-5">
                <div class="col-12">
                    <div class="adobe-glass-card">
                        <div class="adobe-card-body integration-header text-center">
                            <div class="integration-icon">
                                <i class="fa-solid fa-link"></i>
                            </div>
                            <h1 class="adobe-title mb-3">Integrações</h1>
                            <p class="adobe-text-muted mb-0">Conecte suas ferramentas favoritas e automatize seus processos de negócio</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                {{-- Início do card UTMFY com layout ajustado --}}
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        {{-- A classe "d-flex flex-column" transforma o card-body em um container flex vertical --}}
                        <div class="card-body d-flex flex-column">
                            {{-- Esta div cresce para ocupar o espaço disponível, empurrando o botão para baixo --}}
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center">
                                    <div class="text-white rounded p-3 me-3" style="min-width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa-solid fa-link fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-0 text-primary fw-bold">UTMFY</h5>
                                        <small class="text-muted">URL Tracking & Analytics</small>
                                    </div>
                                </div>
                                @if(auth()->user()->integracao_utmfy)
                                <div class="mt-3">
                                    <small><i class="fa-solid fa-circle-check text-success"></i>{{ ' ' }}Token: {{ auth()->user()->integracao_utmfy }}</small>
                                </div>
                                @endif
                            </div>

                            {{-- Botão agora está na base do card, dentro de um container "d-grid" para preencher a largura --}}
                            <div class="d-grid mt-3">
                                @php
                                $utmfy = auth()->user()->integracao_utmfy;
                                $title = is_null($utmfy) || $utmfy == '' ? "Adicionar" : "Alterar";
                                @endphp
                                <button
                                    class="btn btn-primary" {{-- Estilo alterado para dar mais destaque --}}
                                    data-bs-toggle="modal"
                                    data-bs-target="#utmfyModal">
                                    {{ $title }} Token
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Fim do card UTMFY --}}

                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="adobe-glass-card h-100">
                        <div class="adobe-card-body text-center">
                            <div class="integration-icon" style="background: linear-gradient(135deg, #6b7280, #9ca3af);">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                            <h5 class="adobe-subtitle mb-3">Nova Integração</h5>
                            <p class="adobe-text-muted mb-3">Em breve, novas integrações estarão disponíveis</p>
                            <button class="btn btn-outline-secondary" disabled>
                                Em desenvolvimento
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="utmfyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    @php
                        $utmfy = auth()->user()->integracao_utmfy;
                        $title = is_null($utmfy) || $utmfy == '' ? "Adicionar" : "Alterar";
                    @endphp
                    <h6 class="modal-title" id="utmfyLabel">UTMFY TOKEN - {{ $title }}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <form method="POST" action="{{ route('integracoes.utmfy.edit') }}">
                    @csrf
                    <div class="px-4 modal-body">
                        <div class="row gy-2">
                            <div class="col-xl-12">
                                <label for="integracao_utmfy" class="form-label">API TOKEN</label>
                                <input type="text" class="form-control" id="integracao_utmfy" name="integracao_utmfy" placeholder="Digite seu API TOKEN" value="{{ $utmfy }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">{{ $title }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
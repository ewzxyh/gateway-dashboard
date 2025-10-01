@props(['user'])
 <!-- Modal Foto Frente RG -->
 <div class="modal fade" id="fotoFrenteModal" tabindex="-1" aria-labelledby="fotoFrenteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoFrenteLabel">Foto de Frente RG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="text-center modal-body">
                <img src="{{ asset($user->foto_rg_frente) }}" alt="Foto de Frente RG" class="img-fluid">
            </div>
        </div>
    </div>
</div>

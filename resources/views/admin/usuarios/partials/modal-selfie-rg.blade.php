@props(['user'])
<!-- Modal Selfie RG -->
<div class="modal fade" id="selfieModal" tabindex="-1" aria-labelledby="selfieLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selfieLabel">Selfie com RG</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="text-center modal-body">
                <img src="{{ asset($user->selfie_rg) }}" alt="Selfie com RG" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@props([
    'user'
])



<div class="modal fade glassmorphism-delete-modal" id="deleteModal-{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $user->id }}">
                    <i class="fas fa-trash-alt me-2"></i>Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja excluir o usuário <span class="text-highlight">{{ $user->name }}</span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-cancel-glass" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form method="POST" action="{{ route('admin.usuarios.delete', ['id'=> $user->id]) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary btn-delete-glass">
                        <i class="fas fa-trash-alt me-2"></i>Excluir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@props([
    'user'
])


<div class="modal fade glassmorphism-approve-modal" id="aprovarModal-{{ $user->id }}" tabindex="-1" aria-labelledby="aprovarModalLabel-{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aprovarModalLabel-{{ $user->id }}">
                    @if($user->status == 1)
                        <i class="fas fa-times-circle me-2"></i>Reprovar usuário
                    @else
                        <i class="fas fa-check-circle me-2"></i>Aprovar usuário
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <p>Você tem certeza que deseja {{ $user->status == 1 ? 'reprovar' : 'aprovar' }} o usuário <span class="text-highlight">{{ $user->name }}</span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-cancel-glass" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancelar
                </button>
                <form method="POST" action="{{ route('admin.usuarios.mudarstatus') }}" style="display: inline;">
                    @csrf
                    <input id="id" name="id" value="{{$user->id}}" hidden />
                    <input id="tipo" name="tipo" value="status" hidden />
                    @if($user->status == 1)
                        <button type="submit" class="btn btn-primary btn-reprove-glass">
                            <i class="fas fa-times-circle me-2"></i>Reprovar
                        </button>
                    @else
                        <button type="submit" class="btn btn-primary btn-approve-glass">
                            <i class="fas fa-check-circle me-2"></i>Aprovar
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<div class="d-flex align-items-start gap-3">
    <div class="flex-grow-1">
        <p class="text-muted small mb-3">
            Une fois votre compte supprimé, toutes vos données seront définitivement perdues.
            Téléchargez toute information importante avant de supprimer votre compte.
        </p>
        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="fas fa-trash me-2"></i>Supprimer mon compte
        </button>
    </div>
</div>

{{-- Modal confirmation --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Supprimer le compte
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('delete')
                <div class="modal-body">
                    <p class="text-muted">
                        Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est <strong>irréversible</strong>.
                        Toutes vos données seront définitivement supprimées.
                    </p>
                    <div class="mt-3">
                        <label class="form-label fw-semibold">
                            Confirmez avec votre mot de passe <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Entrez votre mot de passe pour confirmer" required />
                        @if($errors->userDeletion->get('password'))
                            <div class="text-danger small mt-1">
                                {{ $errors->userDeletion->first('password') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash me-2"></i>Oui, supprimer mon compte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    @if($errors->updatePassword->any())
        <div class="alert alert-danger py-2 mb-3">
            <ul class="mb-0">
                @foreach($errors->updatePassword->all() as $e)
                    <li class="small">{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12">
            <label class="form-label fw-semibold small">
                <i class="fas fa-lock me-1 text-warning"></i>Mot de passe actuel <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password" name="current_password" class="form-control"
                       id="currentPwd" placeholder="Votre mot de passe actuel" autocomplete="current-password" />
                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('currentPwd', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold small">
                <i class="fas fa-key me-1 text-warning"></i>Nouveau mot de passe <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password" name="password" class="form-control"
                       id="newPwd" placeholder="Minimum 8 caractères" autocomplete="new-password" />
                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('newPwd', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold small">
                <i class="fas fa-key me-1 text-warning"></i>Confirmer le nouveau mot de passe <span class="text-danger">*</span>
            </label>
            <div class="input-group">
                <input type="password" name="password_confirmation" class="form-control"
                       id="confirmPwd" placeholder="Répétez le mot de passe" autocomplete="new-password" />
                <button class="btn btn-outline-secondary" type="button" onclick="togglePwd('confirmPwd', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-shield-alt me-2"></i>Mettre à jour le mot de passe
            </button>
        </div>
    </div>
</form>

<script>
function togglePwd(inputId, btn) {
    const f = document.getElementById(inputId);
    const i = btn.querySelector('i');
    f.type = f.type === 'password' ? 'text' : 'password';
    i.classList.toggle('fa-eye');
    i.classList.toggle('fa-eye-slash');
}
</script>

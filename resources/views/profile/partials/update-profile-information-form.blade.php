<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3">
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li class="small">{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold small">
                <i class="fas fa-user me-1 text-primary"></i>Prénom <span class="text-danger">*</span>
            </label>
            <input type="text" name="firstname" class="form-control"
                   value="{{ old('firstname', $user->firstname) }}" required />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold small">
                <i class="fas fa-user me-1 text-primary"></i>Nom <span class="text-danger">*</span>
            </label>
            <input type="text" name="lastname" class="form-control"
                   value="{{ old('lastname', $user->lastname) }}" required />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold small">
                <i class="fas fa-at me-1 text-primary"></i>Nom d'utilisateur <span class="text-danger">*</span>
            </label>
            <input type="text" name="username" class="form-control"
                   value="{{ old('username', $user->username) }}" required />
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold small">
                <i class="fas fa-phone me-1 text-primary"></i>Téléphone
            </label>
            <input type="text" name="phone" class="form-control"
                   value="{{ old('phone', $user->phone) }}" placeholder="+229 00 00 00 00" />
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold small">
                <i class="fas fa-envelope me-1 text-primary"></i>Email <span class="text-danger">*</span>
            </label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $user->email) }}" required />
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning py-2 mt-2 small">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Votre adresse email n'est pas vérifiée.
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link btn-sm p-0">
                            Cliquez ici pour renvoyer l'email de vérification.
                        </button>
                    </form>
                </div>
            @endif
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-2"></i>Enregistrer les modifications
            </button>
        </div>
    </div>
</form>

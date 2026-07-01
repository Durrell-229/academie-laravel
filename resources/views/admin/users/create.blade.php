<x-admin-layout>
  <x-slot name="title">{{ __('Nouvel utilisateur') }}</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">
        <i class="fas fa-user-plus me-2 text-primary"></i>
        <strong>Nouvel utilisateur</strong>
      </h1>
      <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
      </a>
    </div>
  </x-slot>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
      <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Informations du compte</h5>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold">Prénom <span class="text-danger">*</span></label>
            <input type="text" name="firstname" class="form-control"
                   value="{{ old('firstname') }}" placeholder="Prénom" required />
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
            <input type="text" name="lastname" class="form-control"
                   value="{{ old('lastname') }}" placeholder="Nom de famille" required />
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Nom d'utilisateur <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-at"></i></span>
              <input type="text" name="username" class="form-control"
                     value="{{ old('username') }}" placeholder="identifiant_unique" required />
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Téléphone <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-phone"></i></span>
              <input type="text" name="phone" class="form-control"
                     value="{{ old('phone') }}" placeholder="+229 00 00 00 00" required />
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              <input type="email" name="email" class="form-control"
                     value="{{ old('email') }}" placeholder="email@exemple.com" required />
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Mot de passe <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-lock"></i></span>
              <input type="password" name="password" class="form-control"
                     placeholder="Minimum 8 caractères" required />
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">
              <i class="fas fa-user-tag me-1 text-warning"></i>
              Rôle <span class="text-danger">*</span>
            </label>
            <select name="role_id" class="form-select" required>
              <option value="">-- Choisir un rôle --</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                  {{ $role->title }}
                </option>
              @endforeach
            </select>
            <small class="text-muted">Ce rôle détermine l'accès et le dashboard de l'utilisateur.</small>
          </div>

          <div class="col-md-6">
            <label class="form-label fw-semibold">Statut</label>
            <select name="status" class="form-select">
              <option value="1" selected>✅ Actif</option>
              <option value="0">❌ Inactif</option>
            </select>
          </div>

          <div class="col-12 pt-3 border-top">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save me-2"></i>Créer l'utilisateur
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary ms-2">
              <i class="fas fa-times me-2"></i>Annuler
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</x-admin-layout>

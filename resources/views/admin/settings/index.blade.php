<x-admin-layout>
  <x-slot name="title">Paramètres généraux</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#0A2D6E;">
      <i class="fas fa-cog me-2"></i>Paramètres généraux
    </h1>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-4">

    {{-- Informations de la plateforme --}}
    <div class="col-md-7">
      <div class="card border-0 shadow-sm" style="border-top:3px solid #0A2D6E!important;">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">
            <i class="fas fa-university me-2"></i>Informations de la plateforme
          </h5>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nom de la plateforme</label>
                <input type="text" name="app_name" class="form-control"
                       value="{{ config('app.name') }}" />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email de contact</label>
                <input type="email" name="contact_email" class="form-control"
                       value="{{ env('MAIL_FROM_ADDRESS', '') }}" />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Téléphone</label>
                <input type="text" name="contact_phone" class="form-control"
                       placeholder="+229 XX XX XX XX" />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Ville / Pays</label>
                <input type="text" name="location" class="form-control"
                       placeholder="Cotonou, Bénin" />
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save me-2"></i>Enregistrer
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    {{-- Numéros MoMo --}}
    <div class="col-md-5">
      <div class="card border-0 shadow-sm mb-4" style="border-top:3px solid #0F6E56!important;">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold" style="color:#0F6E56;">
            <i class="fas fa-mobile-alt me-2"></i>Numéros MoMo (Répartition)
          </h5>
        </div>
        <div class="card-body">
          <div class="mb-3 p-3 rounded" style="background:rgba(15,110,86,0.05);border:1px solid rgba(15,110,86,0.15);">
            <div class="fw-semibold small mb-1">Super Admin (20%)</div>
            <div class="h5 fw-bold mb-0" style="color:#0F6E56;">
              <i class="fas fa-phone me-2"></i>0159290652
            </div>
          </div>
          <div class="mb-3 p-3 rounded" style="background:rgba(10,45,110,0.05);border:1px solid rgba(10,45,110,0.15);">
            <div class="fw-semibold small mb-1">Admin (70%)</div>
            <div class="h5 fw-bold mb-0" style="color:#0A2D6E;">
              <i class="fas fa-phone me-2"></i>0149518565
            </div>
          </div>
          <div class="p-3 rounded" style="background:rgba(245,127,23,0.05);border:1px solid rgba(245,127,23,0.15);">
            <div class="fw-semibold small mb-1">Professeur (10%)</div>
            <div class="small text-muted">
              <i class="fas fa-info-circle me-1"></i>
              Le numéro est récupéré depuis le profil du professeur concerné.
            </div>
          </div>
        </div>
      </div>

      {{-- Infos système --}}
      <div class="card border-0 shadow-sm" style="border-top:3px solid #F57F17!important;">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold" style="color:#F57F17;">
            <i class="fas fa-server me-2"></i>Informations système
          </h5>
        </div>
        <div class="card-body">
          <table class="table table-sm mb-0">
            <tr>
              <td class="text-muted small">Laravel</td>
              <td class="fw-semibold small">{{ app()->version() }}</td>
            </tr>
            <tr>
              <td class="text-muted small">PHP</td>
              <td class="fw-semibold small">{{ PHP_VERSION }}</td>
            </tr>
            <tr>
              <td class="text-muted small">Environnement</td>
              <td class="fw-semibold small">{{ app()->environment() }}</td>
            </tr>
            <tr>
              <td class="text-muted small">Debug</td>
              <td>
                @if(config('app.debug'))
                  <span class="badge bg-warning text-dark">Activé</span>
                @else
                  <span class="badge bg-success">Désactivé</span>
                @endif
              </td>
            </tr>
            <tr>
              <td class="text-muted small">Base de données</td>
              <td class="fw-semibold small">{{ config('database.default') }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>

    {{-- Actions rapides --}}
    <div class="col-12">
      <div class="card border-0 shadow-sm" style="border-top:3px solid #C62828!important;">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold" style="color:#C62828;">
            <i class="fas fa-tools me-2"></i>Actions de maintenance
          </h5>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3">
              <form method="POST" action="{{ route('admin.cache.clear') }}">
                @csrf
                <button type="submit" class="btn btn-outline-warning w-100">
                  <i class="fas fa-broom me-2"></i>Vider le cache
                </button>
              </form>
            </div>
            <div class="col-md-3">
              <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary w-100">
                <i class="fas fa-sync me-2"></i>Rafraîchir
              </a>
            </div>
            <div class="col-md-3">
              <a href="{{ route('site.home') }}" target="_blank" class="btn btn-outline-success w-100">
                <i class="fas fa-globe me-2"></i>Voir le site
              </a>
            </div>
            <div class="col-md-3">
              <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100">
                  <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</x-admin-layout>
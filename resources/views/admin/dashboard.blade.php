<x-admin-layout>
  <x-slot name="title">Tableau de bord Admin</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h1 class="h3 mb-1 fw-bold" style="color:#0A2D6E;">
          🛡️ Bonjour, <strong>{{ Auth::guard('admin')->user()->firstname }} !</strong>
        </h1>
        <p class="text-muted small mb-0">
          <i class="fas fa-calendar me-1"></i>
          {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
        </p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('users.index') }}" class="btn btn-primary">
          <i class="fas fa-users me-2"></i>Gérer les utilisateurs
        </a>
        <a href="{{ route('courses.create') }}" class="btn btn-outline-primary">
          <i class="fas fa-plus me-2"></i>Nouveau cours
        </a>
      </div>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Alerte utilisateurs en attente --}}
  @php $nbAttente = \App\Models\User::where('status', 0)->count(); @endphp
  @if($nbAttente > 0)
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-3">
      <i class="fas fa-user-clock fa-2x"></i>
      <div class="flex-grow-1">
        <strong>{{ $nbAttente }} utilisateur(s) en attente de validation !</strong>
        <div class="small">Ces comptes ne peuvent pas se connecter avant votre validation.</div>
      </div>
      <a href="{{ route('users.index') }}" class="btn btn-warning btn-sm">
        Valider maintenant
      </a>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Alerte paiements en attente --}}
  @php
    $nbPaiements = 0;
    if(\Illuminate\Support\Facades\Schema::hasTable('course_payments')) {
      $nbPaiements = \App\Models\CoursePayment::where('status','pending')->count();
    }
  @endphp
  @if($nbPaiements > 0)
    <div class="alert alert-info alert-dismissible fade show d-flex align-items-center gap-3">
      <i class="fas fa-money-bill fa-2x"></i>
      <div class="flex-grow-1">
        <strong>{{ $nbPaiements }} paiement(s) en attente de validation !</strong>
        <div class="small">Des apprenants attendent que vous validiez leur accès aux cours.</div>
      </div>
      <a href="{{ route('payments.index') }}" class="btn btn-info btn-sm text-white">
        Voir les paiements
      </a>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ══ STATISTIQUES ══ --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100" style="border-left:4px solid #0A2D6E!important;">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(10,45,110,0.1);">
            <i class="fas fa-users fa-2x" style="color:#0A2D6E;"></i>
          </div>
          <div>
            <div class="h3 fw-bold mb-0" style="color:#0A2D6E;">{{ $totalUsers }}</div>
            <div class="small text-muted">Utilisateurs</div>
            @if($nbAttente > 0)
              <div class="small text-warning fw-semibold">{{ $nbAttente }} en attente</div>
            @endif
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary w-100">Gérer</a>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100" style="border-left:4px solid #0F6E56!important;">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(15,110,86,0.1);">
            <i class="fas fa-book fa-2x" style="color:#0F6E56;"></i>
          </div>
          <div>
            <div class="h3 fw-bold mb-0" style="color:#0F6E56;">{{ $totalCours }}</div>
            <div class="small text-muted">Cours</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-success w-100">Gérer</a>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100" style="border-left:4px solid #F57F17!important;">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(245,127,23,0.1);">
            <i class="fas fa-list fa-2x" style="color:#F57F17;"></i>
          </div>
          <div>
            <div class="h3 fw-bold mb-0" style="color:#F57F17;">{{ $totalLecons }}</div>
            <div class="small text-muted">Leçons</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <a href="{{ route('lessons.index') }}" class="btn btn-sm btn-outline-warning w-100">Gérer</a>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100" style="border-left:4px solid #C62828!important;">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(198,40,40,0.1);">
            <i class="fas fa-user-check fa-2x" style="color:#C62828;"></i>
          </div>
          <div>
            <div class="h3 fw-bold mb-0" style="color:#C62828;">{{ $totalInscriptions }}</div>
            <div class="small text-muted">Inscriptions</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-outline-danger w-100">Voir</a>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">

    {{-- ══ UTILISATEURS RÉCENTS ══ --}}
    <div class="col-xl-8">
      <div class="card shadow-sm border-0 h-100" style="border-top:3px solid #0A2D6E!important;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
          <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">
            <i class="fas fa-users me-2"></i>Derniers utilisateurs inscrits
          </h5>
          <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">Voir tous</a>
        </div>
        <div class="card-body p-0">
          @if($users->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead style="background:rgba(10,45,110,0.04);">
                  <tr>
                    <th class="small text-muted fw-semibold">Utilisateur</th>
                    <th class="small text-muted fw-semibold">Rôle</th>
                    <th class="small text-muted fw-semibold">Statut</th>
                    <th class="small text-muted fw-semibold">Inscrit le</th>
                    <th class="small text-muted fw-semibold">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $user)
                  <tr>
                    <td>
                      <div class="fw-semibold small">{{ $user->firstname }} {{ $user->lastname }}</div>
                      <small class="text-muted">{{ $user->email }}</small>
                    </td>
                    <td>
                      <span class="badge" style="background:rgba(10,45,110,0.1);color:#0A2D6E;font-size:0.7rem;">
                        {{ $user->role?->title ?? 'Non défini' }}
                      </span>
                    </td>
                    <td>
                      @if($user->status == 1)
                        <span class="badge bg-success" style="font-size:0.65rem;">✅ Actif</span>
                      @else
                        <span class="badge bg-warning text-dark" style="font-size:0.65rem;">⏳ En attente</span>
                      @endif
                    </td>
                    <td><small>{{ $user->created_at->format('d/m/Y') }}</small></td>
                    <td>
                      <div class="d-flex gap-1">
                        <a href="{{ route('users.edit', $user->id) }}"
                           class="btn btn-xs btn-sm btn-outline-primary">
                          <i class="fas fa-edit"></i>
                        </a>
                        @if($user->status == 0)
                          <form method="POST" action="{{ route('users.validate', $user->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-xs btn-sm btn-warning text-dark"
                                    title="Activer le compte">
                              <i class="fas fa-check"></i>
                            </button>
                          </form>
                        @endif
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-4 text-muted">Aucun utilisateur.</div>
          @endif
        </div>
      </div>
    </div>

    {{-- ══ ACCÈS RAPIDES ══ --}}
    <div class="col-xl-4">

      {{-- Gestion utilisateurs --}}
      <div class="card shadow-sm border-0 mb-3" style="border-top:3px solid #0F6E56!important;">
        <div class="card-header bg-white py-3">
          <h6 class="mb-0 fw-bold" style="color:#0F6E56;">
            <i class="fas fa-users-cog me-2"></i>Gestion utilisateurs
          </h6>
        </div>
        <div class="card-body d-flex flex-column gap-2">
          <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-primary w-100 text-start">
            <i class="fas fa-list me-2"></i>Tous les utilisateurs
          </a>
          <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary w-100 text-start">
            <i class="fas fa-user-plus me-2"></i>Créer un utilisateur
          </a>
          <a href="{{ route('roles.index') }}" class="btn btn-sm btn-outline-secondary w-100 text-start">
            <i class="fas fa-user-shield me-2"></i>Gérer les rôles
          </a>
        </div>
      </div>

      {{-- Gestion des cours --}}
      <div class="card shadow-sm border-0 mb-3" style="border-top:3px solid #0A2D6E!important;">
        <div class="card-header bg-white py-3">
          <h6 class="mb-0 fw-bold" style="color:#0A2D6E;">
            <i class="fas fa-book me-2"></i>Gestion des cours
          </h6>
        </div>
        <div class="card-body d-flex flex-column gap-2">
          <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-primary w-100 text-start">
            <i class="fas fa-list me-2"></i>Tous les cours
          </a>
          <a href="{{ route('courses.create') }}" class="btn btn-sm btn-primary w-100 text-start">
            <i class="fas fa-plus me-2"></i>Créer un cours
          </a>
          <a href="{{ route('lessons.index') }}" class="btn btn-sm btn-outline-secondary w-100 text-start">
            <i class="fas fa-chalkboard-user me-2"></i>Leçons
          </a>
          <a href="{{ route('category.index') }}" class="btn btn-sm btn-outline-secondary w-100 text-start">
            <i class="fas fa-code-branch me-2"></i>Catégories
          </a>
        </div>
      </div>

      {{-- Paiements --}}
      <div class="card shadow-sm border-0 mb-3" style="border-top:3px solid #C62828!important;">
        <div class="card-header bg-white py-3">
          <h6 class="mb-0 fw-bold" style="color:#C62828;">
            <i class="fas fa-money-bill me-2"></i>Paiements
            @if($nbPaiements > 0)
              <span class="badge bg-warning text-dark ms-2">{{ $nbPaiements }} en attente</span>
            @endif
          </h6>
        </div>
        <div class="card-body d-flex flex-column gap-2">
          <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-danger w-100 text-start">
            <i class="fas fa-list me-2"></i>Voir les paiements
          </a>
          <a href="{{ route('enrollments.index') }}" class="btn btn-sm btn-outline-secondary w-100 text-start">
            <i class="fas fa-user-check me-2"></i>Inscriptions
          </a>
        </div>
      </div>

      {{-- Examens --}}
      <div class="card shadow-sm border-0" style="border-top:3px solid #F57F17!important;">
        <div class="card-body text-center py-3">
          <i class="fas fa-file-alt fa-2x mb-2" style="color:#F57F17;"></i>
          <p class="small text-muted mb-2">Plateforme d'examens</p>
          <a href="https://academie-numerique-n4du.onrender.com/" target="_blank"
             class="btn btn-warning btn-sm w-100">
            <i class="fas fa-external-link-alt me-2"></i>Ouvrir
          </a>
        </div>
      </div>

    </div>
  </div>

  {{-- Déconnexion --}}
  <div class="text-end mt-3">
    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
      @csrf
      <button type="submit" class="btn btn-outline-danger btn-sm">
        <i class="fas fa-sign-out-alt me-1"></i>Se déconnecter
      </button>
    </form>
  </div>

</x-admin-layout>
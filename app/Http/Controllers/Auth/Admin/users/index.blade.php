<x-admin-layout>
  <x-slot name="title">Utilisateurs</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold" style="color:#0A2D6E;">
        <i class="fas fa-users me-2"></i>Utilisateurs inscrits
      </h1>
      <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouvel utilisateur
      </a>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Alerte en attente --}}
  @php $enAttente = $users->where('status', 0); @endphp
  @if($enAttente->count() > 0)
    <div class="alert alert-warning d-flex align-items-center gap-3 mb-3">
      <i class="fas fa-user-clock fa-2x"></i>
      <div>
        <strong>{{ $enAttente->count() }} compte(s) en attente de validation !</strong>
        <div class="small">Ces utilisateurs ne peuvent pas se connecter avant votre validation.</div>
      </div>
    </div>
  @endif

  {{-- Compteurs --}}
  <div class="row g-3 mb-4">
    @php
      $apprenants  = $users->filter(fn($u) => $u->role?->slug === 'apprenant');
      $profs       = $users->filter(fn($u) => $u->role?->slug === 'professeur');
      $conseillers = $users->filter(fn($u) => $u->role?->slug === 'conseiller-pedagogique');
      $inspecteurs = $users->filter(fn($u) => $u->role?->slug === 'inspecteur');
    @endphp
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="h3 fw-bold text-primary mb-0">{{ $apprenants->count() }}</div>
        <div class="small text-muted">🎓 Apprenants</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="h3 fw-bold text-success mb-0">{{ $profs->count() }}</div>
        <div class="small text-muted">👨‍🏫 Professeurs</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="h3 fw-bold text-info mb-0">{{ $conseillers->count() }}</div>
        <div class="small text-muted">🧭 Conseillers</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="h3 fw-bold text-warning mb-0">{{ $inspecteurs->count() }}</div>
        <div class="small text-muted">🔍 Inspecteurs</div>
      </div>
    </div>
  </div>

  {{-- Filtres --}}
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
      <div class="row g-2">
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher...">
          </div>
        </div>
        <div class="col-md-3">
          <select id="filterRole" class="form-select">
            <option value="">Tous les rôles</option>
            @foreach($roles as $role)
              <option value="{{ strtolower($role->title) }}">{{ $role->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <select id="filterStatus" class="form-select">
            <option value="">Tous</option>
            <option value="actif">Actif</option>
            <option value="attente">En attente</option>
          </select>
        </div>
        <div class="col-md-3 text-muted small d-flex align-items-center justify-content-end">
          <span id="countDisplay">{{ $users->count() }}</span> utilisateur(s)
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="usersTable">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Utilisateur</th>
              <th>Email / Tél</th>
              <th>Rôle actuel</th>
              <th>Attribuer rôle</th>
              <th>Statut</th>
              <th>Inscrit le</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $i => $user)
            <tr style="{{ $user->status == 0 ? 'background:rgba(255,193,7,0.05);' : '' }}">
              <td>{{ $i + 1 }}</td>
              <td>
                <div class="fw-semibold">{{ $user->firstname }} {{ $user->lastname }}</div>
                <small class="text-muted"><i class="fas fa-at me-1"></i>{{ $user->username }}</small>
              </td>
              <td>
                <div class="small">{{ $user->email }}</div>
                <div class="small text-muted">{{ $user->phone }}</div>
              </td>
              <td>
                <span class="badge bg-info bg-opacity-15 text-info border border-info">
                  {{ $user->role?->title ?? 'Non défini' }}
                </span>
              </td>
              <td>
                <select class="form-select form-select-sm role-select"
                        data-user-id="{{ $user->id }}"
                        data-token="{{ csrf_token() }}"
                        style="min-width:160px;">
                  @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                      {{ $user->role_id == $role->id ? 'selected' : '' }}>
                      {{ $role->title }}
                    </option>
                  @endforeach
                </select>
              </td>
              <td>
                @if($user->status == 1)
                  <span class="badge bg-success">
                    <i class="fas fa-circle me-1" style="font-size:0.5rem;"></i>Actif
                  </span>
                @else
                  <div class="d-flex flex-column gap-1">
                    <span class="badge bg-warning text-dark" style="font-size:0.7rem;">
                      <i class="fas fa-clock me-1"></i>En attente
                    </span>
                    {{-- Bouton Valider via formulaire POST classique --}}
                    <form method="POST"
                          action="{{ route('users.validate', $user->id) }}">
                      @csrf
                      <button type="submit" class="btn btn-success btn-sm w-100"
                              style="font-size:0.7rem;"
                              onclick="return confirm('Activer le compte de {{ $user->firstname }} ?')">
                        <i class="fas fa-check me-1"></i>Valider
                      </button>
                    </form>
                  </div>
                @endif
              </td>
              <td>
                <small>{{ $user->created_at->format('d/m/Y') }}</small><br>
                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
              </td>
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('users.edit', $user->id) }}"
                     class="btn btn-sm btn-outline-primary" title="Modifier">
                    <i class="fas fa-edit"></i>
                  </a>
                  <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                        onsubmit="return confirm('Supprimer {{ $user->firstname }} ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
                      <i class="fas fa-trash"></i>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-5 text-muted">Aucun utilisateur.</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <x-slot name="script">
  <script>
    // Attribution rapide du rôle
    document.querySelectorAll('.role-select').forEach(select => {
      select.addEventListener('change', function () {
        const userId = this.dataset.userId;
        const roleId = this.value;
        const roleName = this.options[this.selectedIndex].text;
        const token = this.dataset.token;
        this.disabled = true;

        fetch(`/app/users/${userId}/role`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
          body: JSON.stringify({ role_id: roleId }),
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            // Mettre à jour le badge rôle dans la même ligne
            const row = this.closest('tr');
            row.querySelector('.badge.bg-info').textContent = roleName;
            showToast('✅ Rôle "' + roleName + '" attribué !', 'success');
          }
        })
        .catch(() => showToast('❌ Erreur réseau.', 'danger'))
        .finally(() => { this.disabled = false; });
      });
    });

    // Filtres
    function filterTable() {
      const s  = document.getElementById('searchInput').value.toLowerCase();
      const r  = document.getElementById('filterRole').value.toLowerCase();
      const st = document.getElementById('filterStatus').value.toLowerCase();
      let count = 0;
      document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        const t  = row.textContent.toLowerCase();
        const isActif   = row.querySelector('.badge.bg-success') !== null;
        const isAttente = row.querySelector('.badge.bg-warning') !== null;
        const okStatus  = !st
          || (st === 'actif' && isActif)
          || (st === 'attente' && isAttente);
        const ok = t.includes(s) && (!r || t.includes(r)) && okStatus;
        row.style.display = ok ? '' : 'none';
        if (ok) count++;
      });
      document.getElementById('countDisplay').textContent = count;
    }
    document.getElementById('searchInput').addEventListener('input', filterTable);
    document.getElementById('filterRole').addEventListener('change', filterTable);
    document.getElementById('filterStatus').addEventListener('change', filterTable);

    function showToast(message, type) {
      const t = document.createElement('div');
      t.className = `alert alert-${type} position-fixed bottom-0 end-0 m-4 shadow`;
      t.style.cssText = 'z-index:9999;min-width:280px;';
      t.innerHTML = message;
      document.body.appendChild(t);
      setTimeout(() => t.remove(), 3500);
    }
  </script>
  </x-slot>
</x-admin-layout>

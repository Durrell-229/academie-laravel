<x-admin-layout>
  <x-slot name="title">{{ __('Gestion des utilisateurs') }}</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">👥 <strong>Utilisateurs</strong></h1>
      <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouvel utilisateur
      </a>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Filtres --}}
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
      <div class="row g-2 align-items-center">
        <div class="col-md-4">
          <input type="text" id="searchInput" class="form-control" placeholder="🔍 Rechercher un utilisateur...">
        </div>
        <div class="col-md-3">
          <select id="filterRole" class="form-select">
            <option value="">Tous les rôles</option>
            @foreach($roles as $role)
              <option value="{{ $role->title }}">{{ $role->title }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <select id="filterStatus" class="form-select">
            <option value="">Tous les statuts</option>
            <option value="Actif">Actif</option>
            <option value="Inactif">Inactif</option>
          </select>
        </div>
        <div class="col-md-2">
          <span class="text-muted small">{{ $users->count() }} utilisateur(s)</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Tableau --}}
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="usersTable">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Utilisateur</th>
              <th>Email</th>
              <th>Rôle actuel</th>
              <th>Attribuer un rôle</th>
              <th>Statut</th>
              <th>Inscrit le</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $i => $user)
              <tr id="row-{{ $user->id }}">
                <td>{{ $i + 1 }}</td>
                <td>
                  <div class="fw-semibold">{{ $user->firstname }} {{ $user->lastname }}</div>
                  <small class="text-muted">@{{ $user->username }}</small>
                </td>
                <td>
                  <small>{{ $user->email }}</small>
                </td>
                <td>
                  <span class="badge bg-info bg-opacity-15 text-info fw-semibold" id="role-badge-{{ $user->id }}">
                    {{ $user->role?->title ?? 'Non défini' }}
                  </span>
                </td>

                {{-- Dropdown attribution rapide de rôle --}}
                <td>
                  <select class="form-select form-select-sm role-select"
                          data-user-id="{{ $user->id }}"
                          style="min-width: 160px;">
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
                    <span class="badge bg-success">Actif</span>
                  @else
                    <span class="badge bg-danger">Inactif</span>
                  @endif
                </td>
                <td>
                  <small>{{ $user->created_at->format('d/m/Y') }}</small>
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('users.edit', $user->id) }}"
                       class="btn btn-sm btn-outline-primary" title="Modifier">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                          onsubmit="return confirm('Supprimer cet utilisateur ?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" title="Supprimer">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-5 text-muted">
                  <i class="fas fa-users fa-3x d-block mb-3"></i>
                  Aucun utilisateur inscrit pour le moment.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <x-slot name="script">
  <script>
    // ── Attribution rapide de rôle via dropdown ──────────────────
    document.querySelectorAll('.role-select').forEach(select => {
      select.addEventListener('change', function() {
        const userId  = this.dataset.userId;
        const roleId  = this.value;
        const roleName = this.options[this.selectedIndex].text;

        fetch(`/app/users/${userId}/role`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({ role_id: roleId }),
        })
        .then(r => r.json())
        .then(data => {
          if (data.success) {
            // Mettre à jour le badge
            document.getElementById(`role-badge-${userId}`).textContent = roleName;
            // Notification
            showToast('✅ Rôle "' + roleName + '" attribué !', 'success');
          }
        })
        .catch(() => showToast('❌ Erreur lors de la mise à jour.', 'danger'));
      });
    });

    // ── Recherche en temps réel ───────────────────────────────────
    document.getElementById('searchInput').addEventListener('input', filterTable);
    document.getElementById('filterRole').addEventListener('change', filterTable);
    document.getElementById('filterStatus').addEventListener('change', filterTable);

    function filterTable() {
      const search = document.getElementById('searchInput').value.toLowerCase();
      const role   = document.getElementById('filterRole').value.toLowerCase();
      const status = document.getElementById('filterStatus').value.toLowerCase();

      document.querySelectorAll('#usersTable tbody tr').forEach(row => {
        const text       = row.textContent.toLowerCase();
        const rowRole    = row.querySelector('.badge.bg-info')?.textContent.trim().toLowerCase() ?? '';
        const rowStatus  = row.querySelector('.badge.bg-success, .badge.bg-danger')?.textContent.trim().toLowerCase() ?? '';

        const matchSearch = text.includes(search);
        const matchRole   = !role   || rowRole.includes(role);
        const matchStatus = !status || rowStatus.includes(status === 'actif' ? 'actif' : 'inactif');

        row.style.display = matchSearch && matchRole && matchStatus ? '' : 'none';
      });
    }

    // ── Toast notification ────────────────────────────────────────
    function showToast(message, type) {
      const toast = document.createElement('div');
      toast.className = `alert alert-${type} position-fixed bottom-0 end-0 m-3`;
      toast.style.zIndex = '9999';
      toast.style.minWidth = '250px';
      toast.textContent = message;
      document.body.appendChild(toast);
      setTimeout(() => toast.remove(), 3000);
    }
  </script>
  </x-slot>
</x-admin-layout>

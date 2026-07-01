<x-admin-layout>
  <x-slot name="title">Utilisateurs</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold">Utilisateurs inscrits</h1>
      <a href="{{ route('users.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Nouvel utilisateur</a>
    </div>
  </x-slot>
  @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  @php $enAttente = $users->where('status', 0); @endphp
  @if($enAttente->count() > 0)
  <div class="alert alert-warning d-flex align-items-center gap-3 mb-3">
    <i class="fas fa-user-clock fa-2x"></i>
    <div><strong>{{ $enAttente->count() }} compte(s) en attente !</strong><div class="small">Ces utilisateurs ne peuvent pas se connecter avant validation.</div></div>
  </div>
  @endif
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr><th>#</th><th>Utilisateur</th><th>Email / Tél</th><th>Rôle actuel</th><th>Attribuer rôle</th><th>Statut</th><th>Inscrit le</th><th>Actions</th></tr>
          </thead>
          <tbody>
            @forelse($users as $i => $user)
            <tr style="{{ $user->status == 0 ? 'background:rgba(255,193,7,0.05);' : '' }}">
              <td>{{ $i + 1 }}</td>
              <td><div class="fw-semibold">{{ $user->firstname }} {{ $user->lastname }}</div><small class="text-muted">@{{ $user->username }}</small></td>
              <td><div class="small">{{ $user->email }}</div><div class="small text-muted">{{ $user->phone }}</div></td>
              <td><span class="badge bg-info bg-opacity-15 text-info border border-info">{{ $user->role?->title ?? 'Non défini' }}</span></td>
              <td>
                <select class="form-select form-select-sm role-select" data-user-id="{{ $user->id }}" data-token="{{ csrf_token() }}" style="min-width:160px;">
                  @foreach($roles as $role)<option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->title }}</option>@endforeach
                </select>
              </td>
              <td>
                @if($user->status == 1)
                  <span class="badge bg-success">Actif</span>
                @else
                  <div class="d-flex flex-column gap-1">
                    <span class="badge bg-warning text-dark" style="font-size:0.7rem;">En attente</span>
                    <form method="POST" action="{{ route('users.validate', $user->id) }}">
                      @csrf
                      <button type="submit" class="btn btn-success btn-sm w-100" style="font-size:0.7rem;" onclick="return confirm('Activer ce compte ?')">
                        <i class="fas fa-check me-1"></i>Valider
                      </button>
                    </form>
                  </div>
                @endif
              </td>
              <td><small>{{ $user->created_at->format('d/m/Y') }}</small></td>
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                  <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Supprimer ?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button></form>
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">Aucun utilisateur.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <x-slot name="script">
  <script>
    document.querySelectorAll('.role-select').forEach(select => {
      select.addEventListener('change', function() {
        const userId = this.dataset.userId;
        const roleId = this.value;
        const roleName = this.options[this.selectedIndex].text;
        const token = this.dataset.token;
        this.disabled = true;
        fetch(`/app/users/${userId}/role`, {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN':token},
          body: JSON.stringify({role_id: roleId})
        })
        .then(r => r.json())
        .then(data => { if(data.success){ const row = this.closest('tr'); row.querySelector('.badge.bg-info').textContent = roleName; } })
        .finally(() => { this.disabled = false; });
      });
    });
  </script>
  </x-slot>
</x-admin-layout>

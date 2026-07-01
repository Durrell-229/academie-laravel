<x-admin-layout>
  <x-slot name="title">Rôles</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold" style="color:#0A2D6E;"><i class="fas fa-user-shield me-2"></i>Gestion des rôles</h1>
      <a href="{{ route('roles.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Nouveau rôle</a>
    </div>
  </x-slot>
  @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light"><tr><th>#</th><th>Titre</th><th>Slug</th><th>Statut</th><th>Actions</th></tr></thead>
          <tbody>
            @foreach($roles as $i => $role)
            <tr>
              <td>{{ $i+1 }}</td>
              <td class="fw-semibold">{{ $role->title }}</td>
              <td><code>{{ $role->slug }}</code></td>
              <td>@if($role->status)<span class="badge bg-success">Actif</span>@else<span class="badge bg-secondary">Inactif</span>@endif</td>
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                  <form method="POST" action="{{ route('roles.destroy', $role->id) }}" onsubmit="return confirm('Supprimer ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                  </form>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-admin-layout>

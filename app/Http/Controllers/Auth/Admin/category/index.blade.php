<x-admin-layout>
  <x-slot name="title">Catégories</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold" style="color:#0A2D6E;"><i class="fas fa-code-branch me-2"></i>Catégories</h1>
      <a href="{{ route('category.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Nouvelle</a>
    </div>
  </x-slot>
  @if(session('success'))<div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>@endif
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($cats->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>#</th><th>Titre</th><th>Parent</th><th>Cours</th><th>Actions</th></tr></thead>
            <tbody>
              @foreach($cats as $i => $cat)
              <tr>
                <td>{{ $i+1 }}</td>
                <td class="fw-semibold">{{ $cat->title }}</td>
                <td>{{ $cat->parent?->title ?? '—' }}</td>
                <td><span class="badge" style="background:rgba(10,45,110,0.1);color:#0A2D6E;">{{ $cat->courses?->count() ?? 0 }}</span></td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('category.edit', $cat->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="{{ route('category.destroy', $cat->id) }}" onsubmit="return confirm('Supprimer ?')">
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
      @else
        <div class="text-center py-5"><i class="fas fa-code-branch fa-3x d-block mb-3" style="color:rgba(10,45,110,0.2);"></i><p class="text-muted">Aucune catégorie.</p></div>
      @endif
    </div>
  </div>
</x-admin-layout>

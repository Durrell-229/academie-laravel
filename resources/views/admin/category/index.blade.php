<x-admin-layout>
  <x-slot name="title">Catégories</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold">Catégories</h1>
      <a href="{{ route('category.create') }}" class="btn btn-primary">+ Nouvelle</a>
    </div>
  </x-slot>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($cats->count() > 0)
        <table class="table table-hover mb-0">
          <thead class="table-light"><tr><th>#</th><th>Titre</th><th>Parent</th><th>Actions</th></tr></thead>
          <tbody>
            @foreach($cats as $i => $cat)
            <tr>
              <td>{{ $i+1 }}</td>
              <td class="fw-semibold">{{ $cat->title }}</td>
              <td>{{ $cat->parent?->title ?? '—' }}</td>
              <td>
                <a href="{{ route('category.edit', $cat->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                <form method="POST" action="{{ route('category.destroy', $cat->id) }}" class="d-inline" onsubmit="return confirm('Supprimer ?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="text-center py-5">
          <p class="text-muted">Aucune catégorie.</p>
          <a href="{{ route('category.create') }}" class="btn btn-primary btn-sm">Créer</a>
        </div>
      @endif
    </div>
  </div>
</x-admin-layout>
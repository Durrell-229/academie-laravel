<x-admin-layout>
  <x-slot name="title">Gestion des leçons</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold" style="color:#0A2D6E;">
        <i class="fas fa-chalkboard-user me-2"></i>Gestion des leçons
      </h1>
      <a href="{{ route('lessons.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouvelle leçon
      </a>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($lessons->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Cours</th>
                <th>Durée</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($lessons as $i => $lesson)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td class="fw-semibold">{{ Str::limit($lesson->title, 40) }}</td>
                <td>
                  <span class="badge" style="background:rgba(10,45,110,0.1);color:#0A2D6E;">
                    {{ $lesson->course?->title ?? '—' }}
                  </span>
                </td>
                <td>{{ $lesson->duration ?? '—' }}</td>
                <td>
                  @if($lesson->status == 1)
                    <span class="badge bg-success">Publié</span>
                  @else
                    <span class="badge bg-secondary">Brouillon</span>
                  @endif
                </td>
                <td><small>{{ $lesson->created_at->format('d/m/Y') }}</small></td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('lessons.edit', $lesson->id) }}"
                       class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('lessons.destroy', $lesson->id) }}"
                          onsubmit="return confirm('Supprimer ?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-5">
          <i class="fas fa-chalkboard-user fa-4x d-block mb-3" style="color:rgba(10,45,110,0.2);"></i>
          <h5 class="text-muted">Aucune leçon créée.</h5>
        </div>
      @endif
    </div>
  </div>
</x-admin-layout>

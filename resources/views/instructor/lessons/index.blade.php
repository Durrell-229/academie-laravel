<x-instructor-layout>
  <x-slot name="title">{{ __('Mes Leçons') }}</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">📝 <strong>Mes Leçons</strong></h1>
      <a href="{{ route('instructor.lessons.create') }}" class="btn btn-success">
        <i class="fas fa-plus me-2"></i>Nouvelle leçon
      </a>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      @if($lessons->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr><th>#</th><th>Titre</th><th>Cours</th><th>Durée</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
              @foreach($lessons as $i => $lesson)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td class="fw-semibold">{{ $lesson->title }}</td>
                  <td>
                    <span class="badge bg-primary bg-opacity-10 text-primary">
                      {{ $lesson->course?->title ?? '—' }}
                    </span>
                  </td>
                  <td>{{ $lesson->duration }}</td>
                  <td>
                    @if($lesson->status)
                      <span class="badge bg-success">Publié</span>
                    @else
                      <span class="badge bg-warning text-dark">Brouillon</span>
                    @endif
                  </td>
                  <td>{{ $lesson->created_at->format('d/m/Y') }}</td>
                  <td>
                    <div class="d-flex gap-1">
                      <a href="{{ route('instructor.lessons.edit', $lesson->id) }}"
                         class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                      <form method="POST" action="{{ route('instructor.lessons.destroy', $lesson->id) }}"
                            onsubmit="return confirm('Supprimer cette leçon ?')">
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
        <div class="text-center py-5">
          <i class="fas fa-file-alt fa-4x text-muted mb-3 d-block"></i>
          <h5 class="text-muted">Aucune leçon créée</h5>
          <a href="{{ route('instructor.lessons.create') }}" class="btn btn-success mt-2">Créer ma première leçon</a>
        </div>
      @endif
    </div>
  </div>
</x-instructor-layout>
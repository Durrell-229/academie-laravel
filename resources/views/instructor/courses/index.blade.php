<x-instructor-layout>
  <x-slot name="title">{{ __('Mes Cours') }}</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">📚 <strong>Mes Cours</strong></h1>
      <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouveau cours
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
      @if($courses->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Prix</th>
                <th>Leçons</th>
                <th>Statut</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($courses as $i => $course)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>
                    <div class="fw-semibold">{{ $course->title }}</div>
                    <small class="text-muted"><code>{{ $course->course_code }}</code></small>
                  </td>
                  <td>{{ $course->category?->title ?? '—' }}</td>
                  <td>{{ number_format($course->regular_price, 0) }} FCFA
                    @if($course->offer_price)
                      <br><small class="text-danger">Promo: {{ number_format($course->offer_price, 0) }}</small>
                    @endif
                  </td>
                  <td>
                    <span class="badge" style="background:rgba(26,95,180,0.1);color:#1A5FB4;">
                      {{ $course->lessons?->count() ?? 0 }}
                    </span>
                  </td>
                  <td>
                    @if($course->status)
                      <span class="badge bg-success">✅ Publié</span>
                    @else
                      <span class="badge bg-secondary">Brouillon</span>
                    @endif
                  </td>
                  <td>{{ $course->created_at->format('d/m/Y') }}</td>
                  <td>
                    <div class="d-flex gap-1">
                      <a href="{{ route('instructor.courses.edit', $course->id) }}"
                         class="btn btn-sm btn-outline-primary" title="Modifier">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form method="POST" action="{{ route('instructor.courses.destroy', $course->id) }}"
                            onsubmit="return confirm('Supprimer ce cours ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
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
          <i class="fas fa-chalkboard fa-4x d-block mb-3" style="color:rgba(26,95,180,0.2);"></i>
          <h5 class="text-muted">Vous n'avez pas encore créé de cours</h5>
          <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary mt-2">
            <i class="fas fa-plus me-2"></i>Créer mon premier cours
          </a>
        </div>
      @endif
    </div>
  </div>
</x-instructor-layout>
<x-admin-layout>
  <x-slot name="title">{{ __('Tableau de bord Professeur') }}</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3">
      👨‍🏫 <strong>Bonjour, {{ Auth::user()->firstname }} !</strong>
      <span class="badge bg-success ms-2 fs-6">{{ Auth::user()->role?->title ?? 'Professeur' }}</span>
    </h1>
  </x-slot>

  {{-- Messages flash --}}
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Statistiques --}}
  <div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle bg-primary bg-opacity-10">
            <i class="fas fa-book fa-2x text-primary"></i>
          </div>
          <div>
            <div class="text-muted small text-uppercase fw-semibold">Mes cours</div>
            <div class="h3 mb-0 fw-bold">{{ $courses->count() }}</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0">
          <a href="{{ route('instructor.courses.index') }}" class="btn btn-sm btn-outline-primary w-100">Voir mes cours</a>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle bg-success bg-opacity-10">
            <i class="fas fa-list fa-2x text-success"></i>
          </div>
          <div>
            <div class="text-muted small text-uppercase fw-semibold">Mes leçons</div>
            <div class="h3 mb-0 fw-bold">{{ $lessons->count() }}</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0">
          <a href="{{ route('instructor.lessons.index') }}" class="btn btn-sm btn-outline-success w-100">Voir mes leçons</a>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle bg-info bg-opacity-10">
            <i class="fas fa-users fa-2x text-info"></i>
          </div>
          <div>
            <div class="text-muted small text-uppercase fw-semibold">Étudiants inscrits</div>
            <div class="h3 mb-0 fw-bold">{{ $students }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle bg-warning bg-opacity-10">
            <i class="fas fa-plus-circle fa-2x text-warning"></i>
          </div>
          <div>
            <div class="text-muted small text-uppercase fw-semibold">Actions rapides</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0 d-flex gap-2">
          <a href="{{ route('instructor.courses.create') }}" class="btn btn-sm btn-primary flex-fill">+ Cours</a>
          <a href="{{ route('instructor.lessons.create') }}" class="btn btn-sm btn-success flex-fill">+ Leçon</a>
        </div>
      </div>
    </div>
  </div>

  {{-- Tableau des cours --}}
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
      <h5 class="mb-0"><i class="fas fa-book me-2 text-primary"></i>Mes derniers cours</h5>
      <a href="{{ route('instructor.courses.create') }}" class="btn btn-sm btn-primary">
        <i class="fas fa-plus me-1"></i>Nouveau cours
      </a>
    </div>
    <div class="card-body p-0">
      @if($courses->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>Titre</th>
                <th>Code</th>
                <th>Prix</th>
                <th>Statut</th>
                <th>Leçons</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($courses->take(5) as $course)
                <tr>
                  <td class="fw-semibold">{{ $course->title }}</td>
                  <td><code>{{ $course->course_code }}</code></td>
                  <td>{{ number_format($course->regular_price, 0) }} FCFA</td>
                  <td>
                    @if($course->status)
                      <span class="badge bg-success">Publié</span>
                    @else
                      <span class="badge bg-warning text-dark">Brouillon</span>
                    @endif
                  </td>
                  <td>{{ $course->lessons?->count() ?? 0 }}</td>
                  <td>
                    <a href="{{ route('instructor.courses.edit', $course->id) }}" class="btn btn-xs btn-outline-primary btn-sm">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('instructor.courses.destroy', $course->id) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer ce cours ?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-xs btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-5">
          <i class="fas fa-chalkboard fa-3x text-muted mb-3 d-block"></i>
          <p class="text-muted">Aucun cours créé pour le moment.</p>
          <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">Créer mon premier cours</a>
        </div>
      @endif
    </div>
  </div>

  {{-- Tableau des leçons --}}
  <div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
      <h5 class="mb-0"><i class="fas fa-list me-2 text-success"></i>Mes dernières leçons</h5>
      <a href="{{ route('instructor.lessons.create') }}" class="btn btn-sm btn-success">
        <i class="fas fa-plus me-1"></i>Nouvelle leçon
      </a>
    </div>
    <div class="card-body p-0">
      @if($lessons->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr><th>Titre</th><th>Cours</th><th>Durée</th><th>Statut</th><th>Actions</th></tr>
            </thead>
            <tbody>
              @foreach($lessons->take(5) as $lesson)
                <tr>
                  <td class="fw-semibold">{{ $lesson->title }}</td>
                  <td>{{ $lesson->course?->title ?? '—' }}</td>
                  <td>{{ $lesson->duration }}</td>
                  <td>
                    @if($lesson->status)
                      <span class="badge bg-success">Publié</span>
                    @else
                      <span class="badge bg-warning text-dark">Brouillon</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('instructor.lessons.edit', $lesson->id) }}" class="btn btn-xs btn-outline-primary btn-sm">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('instructor.lessons.destroy', $lesson->id) }}" class="d-inline"
                          onsubmit="return confirm('Supprimer cette leçon ?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-xs btn-outline-danger btn-sm"><i class="fas fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-5">
          <i class="fas fa-file-alt fa-3x text-muted mb-3 d-block"></i>
          <p class="text-muted">Aucune leçon créée pour le moment.</p>
          <a href="{{ route('instructor.lessons.create') }}" class="btn btn-success">Créer ma première leçon</a>
        </div>
      @endif
    </div>
  </div>
</x-admin-layout>

<x-admin-layout>
  <x-slot name="title">Gestion des cours</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold" style="color:#0A2D6E;">
        <i class="fas fa-graduation-cap me-2"></i>Gestion des cours
      </h1>
      <a href="{{ route('courses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nouveau cours
      </a>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Filtres --}}
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
      <div class="row g-2">
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search" class="form-control" placeholder="Rechercher un cours...">
          </div>
        </div>
        <div class="col-md-3">
          <select id="filterStatus" class="form-select">
            <option value="">Tous les statuts</option>
            <option value="publié">Publié</option>
            <option value="brouillon">Brouillon</option>
          </select>
        </div>
        <div class="col-md-2 text-muted small d-flex align-items-center">
          {{ $courses->count() }} cours
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($courses->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="coursesTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Professeur</th>
                <th>Catégorie</th>
                <th>Prix (FCFA)</th>
                <th>Leçons</th>
                <th>Inscrits</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($courses as $i => $course)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  <div class="fw-semibold">{{ Str::limit($course->title, 35) }}</div>
                  <small class="text-muted"><code>{{ $course->course_code }}</code></small>
                </td>
                <td>
                  <small>{{ $course->user?->firstname }} {{ $course->user?->lastname }}</small>
                </td>
                <td>
                  <span class="badge" style="background:rgba(10,45,110,0.1);color:#0A2D6E;">
                    {{ $course->category?->title ?? '—' }}
                  </span>
                </td>
                <td>
                  <div class="fw-semibold">{{ number_format($course->regular_price, 0, ',', ' ') }}</div>
                  @if($course->offer_price)
                    <small class="text-danger">Promo: {{ number_format($course->offer_price, 0) }}</small>
                  @endif
                </td>
                <td>
                  <span class="badge" style="background:rgba(15,110,86,0.1);color:#0F6E56;">
                    {{ $course->lessons?->count() ?? 0 }}
                  </span>
                </td>
                <td>
                  <span class="badge" style="background:rgba(245,127,23,0.1);color:#F57F17;">
                    {{ \App\Models\Enrollment::where('course_id', $course->id)->count() }}
                  </span>
                </td>
                <td>
                  @if($course->status == 1)
                    <span class="badge bg-success">✅ Publié</span>
                  @else
                    <span class="badge bg-secondary">📝 Brouillon</span>
                  @endif
                </td>
                <td>
                  <div class="d-flex gap-1">
                    <a href="{{ route('courses.edit', $course->id) }}"
                       class="btn btn-sm btn-outline-primary" title="Modifier">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form method="POST" action="{{ route('courses.destroy', $course->id) }}"
                          onsubmit="return confirm('Supprimer ce cours ?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" title="Supprimer">
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
          <i class="fas fa-graduation-cap fa-4x d-block mb-3" style="color:rgba(10,45,110,0.2);"></i>
          <h5 class="text-muted">Aucun cours créé pour le moment.</h5>
          <a href="{{ route('courses.create') }}" class="btn btn-primary mt-2">
            <i class="fas fa-plus me-2"></i>Créer un cours
          </a>
        </div>
      @endif
    </div>
  </div>

  <x-slot name="script">
  <script>
    document.getElementById('search').addEventListener('input', filter);
    document.getElementById('filterStatus').addEventListener('change', filter);
    function filter() {
      const s = document.getElementById('search').value.toLowerCase();
      const st = document.getElementById('filterStatus').value.toLowerCase();
      document.querySelectorAll('#coursesTable tbody tr').forEach(row => {
        const t = row.textContent.toLowerCase();
        row.style.display = t.includes(s) && (!st || t.includes(st)) ? '' : 'none';
      });
    }
  </script>
  </x-slot>
</x-admin-layout>

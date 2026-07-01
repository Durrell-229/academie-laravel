<x-instructor-layout>
  <x-slot name="title">Mes apprenants</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3">👥 <strong>Mes apprenants</strong></h1>
  </x-slot>

  @php
    $courseIds   = \App\Models\Course::where('user_id', Auth::id())->pluck('id');
    $enrollments = \App\Models\Enrollment::whereIn('course_id', $courseIds)
        ->with(['user', 'course'])
        ->latest()
        ->get();
  @endphp

  @if($enrollments->count() > 0)
    {{-- Filtres --}}
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body py-2">
        <div class="row g-2">
          <div class="col-md-4">
            <input type="text" id="search" class="form-control form-control-sm"
                   placeholder="🔍 Rechercher un apprenant...">
          </div>
          <div class="col-md-4">
            <select id="filterCours" class="form-select form-select-sm">
              <option value="">Tous les cours</option>
              @foreach(\App\Models\Course::where('user_id', Auth::id())->get() as $course)
                <option value="{{ $course->title }}">{{ $course->title }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 text-muted small d-flex align-items-center">
            {{ $enrollments->count() }} apprenant(s)
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="apprenantsTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Apprenant</th>
                <th>Email</th>
                <th>Cours inscrit</th>
                <th>Statut</th>
                <th>Inscrit le</th>
              </tr>
            </thead>
            <tbody>
              @foreach($enrollments as $i => $enrollment)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  <div class="fw-semibold">
                    {{ $enrollment->user?->firstname }} {{ $enrollment->user?->lastname }}
                  </div>
                  <small class="text-muted">@{{ $enrollment->user?->username }}</small>
                </td>
                <td><small>{{ $enrollment->user?->email }}</small></td>
                <td>
                  <span class="badge bg-success bg-opacity-15 text-success">
                    {{ $enrollment->course?->title ?? '—' }}
                  </span>
                </td>
                <td>
                  @if($enrollment->status == 2)
                    <span class="badge bg-success">✅ Terminé</span>
                  @elseif($enrollment->status == 1)
                    <span class="badge bg-warning text-dark">📚 En cours</span>
                  @else
                    <span class="badge bg-secondary">⏳ En attente</span>
                  @endif
                </td>
                <td><small>{{ $enrollment->created_at->format('d/m/Y') }}</small></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @else
    <div class="card border-0 shadow-sm">
      <div class="card-body text-center py-5">
        <i class="fas fa-users fa-4x text-muted d-block mb-3"></i>
        <h5 class="text-muted">Aucun apprenant inscrit pour le moment</h5>
        <p class="text-muted">Publiez vos cours pour que les apprenants puissent s'inscrire.</p>
        <a href="{{ route('instructor.courses.index') }}" class="btn btn-success">
          Voir mes cours
        </a>
      </div>
    </div>
  @endif

  <x-slot name="script">
  <script>
    document.getElementById('search')?.addEventListener('input', filter);
    document.getElementById('filterCours')?.addEventListener('change', filter);
    function filter() {
      const s = document.getElementById('search').value.toLowerCase();
      const c = document.getElementById('filterCours').value.toLowerCase();
      document.querySelectorAll('#apprenantsTable tbody tr').forEach(row => {
        const t = row.textContent.toLowerCase();
        row.style.display = t.includes(s) && (!c || t.includes(c)) ? '' : 'none';
      });
    }
  </script>
  </x-slot>
</x-instructor-layout>

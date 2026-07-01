<x-admin-layout>
  <x-slot name="title">Inscriptions</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-0 fw-bold">Inscriptions aux cours</h1>
  </x-slot>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($enrollments->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr><th>#</th><th>Apprenant</th><th>Cours</th><th>Statut</th><th>Date</th></tr>
            </thead>
            <tbody>
              @foreach($enrollments as $i => $e)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>
                  <div class="fw-semibold small">{{ $e->user?->firstname }} {{ $e->user?->lastname }}</div>
                  <small class="text-muted">{{ $e->user?->email }}</small>
                </td>
                <td><span class="badge bg-primary bg-opacity-15 text-primary">{{ $e->course?->title ?? '—' }}</span></td>
                <td>
                  @if($e->status == 2)<span class="badge bg-success">✅ Terminé</span>
                  @elseif($e->status == 1)<span class="badge bg-warning text-dark">📚 En cours</span>
                  @else<span class="badge bg-secondary">⏳ En attente</span>@endif
                </td>
                <td><small>{{ $e->created_at->format('d/m/Y') }}</small></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-5">
          <p class="text-muted">Aucune inscription.</p>
        </div>
      @endif
    </div>
  </div>
</x-admin-layout>
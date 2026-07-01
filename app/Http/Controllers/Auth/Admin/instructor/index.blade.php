<x-admin-layout>
  <x-slot name="title">Instructeurs</x-slot>
  <x-slot name="header"><h1 class="h3 mb-3 fw-bold" style="color:#0A2D6E;"><i class="fas fa-user-tie me-2"></i>Instructeurs</h1></x-slot>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if(isset($instructors) && $instructors->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>#</th><th>Nom</th><th>Email</th><th>Cours</th></tr></thead>
            <tbody>
              @foreach($instructors as $i => $inst)
              <tr>
                <td>{{ $i+1 }}</td>
                <td class="fw-semibold">{{ $inst->firstname }} {{ $inst->lastname }}</td>
                <td>{{ $inst->email }}</td>
                <td><span class="badge" style="background:rgba(10,45,110,0.1);color:#0A2D6E;">{{ $inst->courses?->count() ?? 0 }}</span></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-5"><i class="fas fa-user-tie fa-3x d-block mb-3" style="color:rgba(10,45,110,0.2);"></i><p class="text-muted">Aucun instructeur.</p></div>
      @endif
    </div>
  </div>
</x-admin-layout>

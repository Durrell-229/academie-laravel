{{-- COURS --}}
@php $view = request()->segment(2); @endphp

@if($view === 'cours')
<x-conseiller-layout>
  <x-slot name="title">Cours disponibles</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#0D4F3C;">📚 <strong>Cours disponibles</strong></h1>
  </x-slot>
  <div class="row g-3">
    @forelse($cours as $c)
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100" style="transition:transform 0.2s;"
           onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform=''">
        <div class="card-body">
          <span class="badge mb-2" style="background:rgba(13,79,60,0.1);color:#0D4F3C;">{{ $c->category?->title }}</span>
          <h6 class="fw-bold">{{ $c->title }}</h6>
          <p class="small text-muted">{{ Str::limit($c->details?->description, 80) }}</p>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="fw-bold" style="color:#0D4F3C;">{{ number_format($c->regular_price,0) }} FCFA</span>
            <span class="badge" style="background:rgba(26,95,180,0.1);color:#1A5FB4;">
              {{ \App\Models\Enrollment::where('course_id',$c->id)->count() }} inscrits
            </span>
          </div>
        </div>
      </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
      <i class="fas fa-book fa-4x d-block mb-3" style="color:rgba(13,79,60,0.2);"></i>
      <p class="text-muted">Aucun cours publié.</p>
    </div>
    @endforelse
  </div>
</x-conseiller-layout>

@elseif($view === 'progression')
<x-conseiller-layout>
  <x-slot name="title">Progression</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#0D4F3C;">📈 <strong>Progression des apprenants</strong></h1>
  </x-slot>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr><th>Apprenant</th><th>Cours</th><th>Statut</th><th>Inscrit le</th></tr>
          </thead>
          <tbody>
            @forelse($enrollments as $e)
            <tr>
              <td>
                <div class="fw-semibold small">{{ $e->user?->firstname }} {{ $e->user?->lastname }}</div>
                <small class="text-muted">{{ $e->user?->email }}</small>
              </td>
              <td><small>{{ $e->course?->title }}</small></td>
              <td>
                @if($e->status == 2)
                  <span class="badge bg-success">✅ Terminé</span>
                @elseif($e->status == 1)
                  <span class="badge bg-warning text-dark">📚 En cours</span>
                @else
                  <span class="badge bg-secondary">⏳ En attente</span>
                @endif
              </td>
              <td><small>{{ $e->created_at->format('d/m/Y') }}</small></td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-4 text-muted">Aucune inscription.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-conseiller-layout>

@elseif($view === 'evaluations')
<x-conseiller-layout>
  <x-slot name="title">Évaluations</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#0D4F3C;">📋 <strong>Résultats & Évaluations</strong></h1>
  </x-slot>
  <div class="card border-0 shadow-sm text-center py-5">
    <div class="card-body">
      <i class="fas fa-clipboard-check fa-4x d-block mb-3" style="color:rgba(13,79,60,0.2);"></i>
      <h5 class="text-muted">Accéder à la plateforme d'évaluations</h5>
      <p class="text-muted">Les évaluations et examens sont gérés sur une plateforme dédiée.</p>
      <a href="https://academie-numerique-n4du.onrender.com/" target="_blank" class="btn btn-primary">
        <i class="fas fa-external-link-alt me-2"></i>Ouvrir la plateforme d'examens
      </a>
    </div>
  </div>
</x-conseiller-layout>

@elseif($view === 'contacts')
<x-conseiller-layout>
  <x-slot name="title">Contacts</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#0D4F3C;">✉️ <strong>Contacter</strong></h1>
  </x-slot>

  <div class="row g-4">
    {{-- Apprenants --}}
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100" style="border-top:3px solid #0D4F3C!important;">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold" style="color:#0D4F3C;"><i class="fas fa-user-graduate me-2"></i>Apprenants</h5>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            @foreach($apprenants->take(10) as $a)
            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
              <div>
                <div class="small fw-semibold">{{ $a->firstname }} {{ $a->lastname }}</div>
                <small class="text-muted">{{ $a->email }}</small>
              </div>
              <a href="mailto:{{ $a->email }}" class="btn btn-sm" style="background:rgba(13,79,60,0.1);color:#0D4F3C;border:none;">
                <i class="fas fa-envelope"></i>
              </a>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

    {{-- Professeurs --}}
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100" style="border-top:3px solid #1A5FB4!important;">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold" style="color:#1A5FB4;"><i class="fas fa-chalkboard-teacher me-2"></i>Professeurs</h5>
        </div>
        <div class="card-body p-0">
          <ul class="list-group list-group-flush">
            @foreach($professeurs as $p)
            <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
              <div>
                <div class="small fw-semibold">{{ $p->firstname }} {{ $p->lastname }}</div>
                <small class="text-muted">{{ $p->email }}</small>
              </div>
              <a href="mailto:{{ $p->email }}" class="btn btn-sm" style="background:rgba(26,95,180,0.1);color:#1A5FB4;border:none;">
                <i class="fas fa-envelope"></i>
              </a>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </div>
</x-conseiller-layout>
@endif

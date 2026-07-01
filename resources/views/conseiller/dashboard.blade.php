<x-conseiller-layout>
  <x-slot name="title">Tableau de bord</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-1 fw-bold" style="color:#0D4F3C;">
          🧭 Bonjour, <strong>{{ Auth::user()->firstname }}</strong> !
        </h1>
        <p class="text-muted small mb-0">
          <i class="fas fa-calendar me-1"></i>
          {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
        </p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('conseiller.apprenants') }}" class="btn btn-primary">
          <i class="fas fa-users me-2"></i>Voir les apprenants
        </a>
        <a href="{{ route('conseiller.contacts') }}" class="btn btn-outline-primary">
          <i class="fas fa-envelope me-2"></i>Contacter
        </a>
      </div>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ══ STATISTIQUES ══ --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100 stat-card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(13,79,60,0.1);">
            <i class="fas fa-users fa-2x" style="color:#0D4F3C;"></i>
          </div>
          <div>
            <div class="stat-number h3 fw-bold mb-0" style="color:#0D4F3C;"
                 data-target="{{ $totalApprenants }}">0</div>
            <div class="small text-muted">Apprenants</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100 stat-card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(26,95,180,0.1);">
            <i class="fas fa-book fa-2x" style="color:#1A5FB4;"></i>
          </div>
          <div>
            <div class="stat-number h3 fw-bold mb-0" style="color:#1A5FB4;"
                 data-target="{{ $totalCours }}">0</div>
            <div class="small text-muted">Cours publiés</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100 stat-card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(245,127,23,0.1);">
            <i class="fas fa-user-check fa-2x" style="color:#F57F17;"></i>
          </div>
          <div>
            <div class="stat-number h3 fw-bold mb-0" style="color:#F57F17;"
                 data-target="{{ $totalInscriptions }}">0</div>
            <div class="small text-muted">Inscriptions</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100 stat-card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(198,40,40,0.1);">
            <i class="fas fa-chalkboard-teacher fa-2x" style="color:#C62828;"></i>
          </div>
          <div>
            <div class="stat-number h3 fw-bold mb-0" style="color:#C62828;"
                 data-target="{{ $totalProfesseurs }}">0</div>
            <div class="small text-muted">Professeurs</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    {{-- ══ LISTE APPRENANTS ══ --}}
    <div class="col-xl-7">
      <div class="card shadow-sm border-0 h-100" style="border-top:3px solid #0D4F3C!important;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3"
             style="border-bottom:1px solid rgba(13,79,60,0.1);">
          <h5 class="mb-0 fw-bold" style="color:#0D4F3C;">
            <i class="fas fa-users me-2"></i>Derniers apprenants inscrits
          </h5>
          <a href="{{ route('conseiller.apprenants') }}" class="btn btn-sm btn-primary">
            Voir tous
          </a>
        </div>
        <div class="card-body p-0">
          @if($recentApprenants->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead style="background:rgba(13,79,60,0.04);">
                  <tr>
                    <th class="small text-muted fw-semibold">Apprenant</th>
                    <th class="small text-muted fw-semibold">Niveau</th>
                    <th class="small text-muted fw-semibold">Cours inscrits</th>
                    <th class="small text-muted fw-semibold">Statut</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($recentApprenants as $apprenant)
                  <tr>
                    <td>
                      <div class="fw-semibold small">{{ $apprenant->firstname }} {{ $apprenant->lastname }}</div>
                      <small class="text-muted">{{ $apprenant->email }}</small>
                    </td>
                    <td>
                      <span class="badge" style="background:rgba(13,79,60,0.1);color:#0D4F3C;font-size:0.7rem;">
                        {{ ucfirst(str_replace('_',' ', $apprenant->profile?->niveau_scolaire ?? 'Non défini')) }}
                      </span>
                    </td>
                    <td>
                      <span class="badge" style="background:rgba(26,95,180,0.1);color:#1A5FB4;">
                        {{ $apprenant->enrollments?->count() ?? 0 }}
                      </span>
                    </td>
                    <td>
                      @if($apprenant->status == 1)
                        <span class="badge bg-success" style="font-size:0.65rem;">Actif</span>
                      @else
                        <span class="badge bg-danger" style="font-size:0.65rem;">Inactif</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-5">
              <i class="fas fa-users fa-3x d-block mb-3" style="color:rgba(13,79,60,0.2);"></i>
              <p class="text-muted">Aucun apprenant inscrit pour le moment.</p>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- ══ COURS + CONTACTS ══ --}}
    <div class="col-xl-5">

      {{-- Cours récents --}}
      <div class="card shadow-sm border-0 mb-4" style="border-top:3px solid #1A5FB4!important;">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3"
             style="border-bottom:1px solid rgba(26,95,180,0.1);">
          <h5 class="mb-0 fw-bold" style="color:#1A5FB4;">
            <i class="fas fa-book me-2"></i>Cours disponibles
          </h5>
          <a href="{{ route('conseiller.cours') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
        </div>
        <div class="card-body p-0">
          @if($recentCours->count() > 0)
            <ul class="list-group list-group-flush">
              @foreach($recentCours as $cours)
              <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2"
                  style="transition:background 0.15s;"
                  onmouseover="this.style.background='rgba(26,95,180,0.03)'"
                  onmouseout="this.style.background=''">
                <div>
                  <div class="fw-semibold small">{{ Str::limit($cours->title, 35) }}</div>
                  <small class="text-muted">{{ $cours->category?->title }}</small>
                </div>
                <span class="badge" style="background:rgba(13,79,60,0.1);color:#0D4F3C;font-size:0.65rem;">
                  {{ \App\Models\Enrollment::where('course_id',$cours->id)->count() }} inscrits
                </span>
              </li>
              @endforeach
            </ul>
          @else
            <div class="text-center py-3">
              <p class="text-muted small">Aucun cours disponible.</p>
            </div>
          @endif
        </div>
      </div>

      {{-- Contacter --}}
      <div class="card shadow-sm border-0" style="border-top:3px solid #F57F17!important;">
        <div class="card-header bg-white py-3" style="border-bottom:1px solid rgba(245,127,23,0.1);">
          <h5 class="mb-0 fw-bold" style="color:#F57F17;">
            <i class="fas fa-envelope me-2"></i>Contacts rapides
          </h5>
        </div>
        <div class="card-body">
          <div class="d-flex flex-column gap-2">
            <a href="{{ route('conseiller.contacts') }}?type=apprenant"
               class="btn btn-sm d-flex align-items-center gap-2"
               style="background:rgba(13,79,60,0.08);color:#0D4F3C;border:1px solid rgba(13,79,60,0.2);transition:all 0.2s;"
               onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform=''">
              <i class="fas fa-user-graduate"></i> Contacter un apprenant
            </a>
            <a href="{{ route('conseiller.contacts') }}?type=professeur"
               class="btn btn-sm d-flex align-items-center gap-2"
               style="background:rgba(26,95,180,0.08);color:#1A5FB4;border:1px solid rgba(26,95,180,0.2);transition:all 0.2s;"
               onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform=''">
              <i class="fas fa-chalkboard-teacher"></i> Contacter un professeur
            </a>
            <a href="https://academie-numerique-n4du.onrender.com/" target="_blank"
               class="btn btn-sm d-flex align-items-center gap-2"
               style="background:rgba(245,127,23,0.08);color:#F57F17;border:1px solid rgba(245,127,23,0.2);transition:all 0.2s;"
               onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform=''">
              <i class="fas fa-file-alt"></i> Accéder aux examens
              <i class="fas fa-external-link-alt ms-auto" style="font-size:0.65rem;"></i>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>

  <x-slot name="script">
  <style>
    @keyframes bounceIn { 0%{transform:scale(0.7);opacity:0} 60%{transform:scale(1.08)} 100%{transform:scale(1);opacity:1} }
    @keyframes fadeInUp { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
  </style>
  <script>
    document.querySelectorAll('.stat-number[data-target]').forEach(el => {
      const target = parseInt(el.dataset.target);
      if (!target) return;
      let current = 0;
      const step = Math.max(1, Math.ceil(target / 30));
      const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current;
        if (current >= target) clearInterval(timer);
      }, 40);
    });
  </script>
  </x-slot>
</x-conseiller-layout>

<x-student-layout>
  <x-slot name="title">Mon Espace</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="h3 mb-1 fw-bold" style="color:#3B1F8C;">
          🎓 Bonjour, <strong>{{ $user->firstname }}</strong> !
        </h1>
        <p class="text-muted small mb-0">
          <i class="fas fa-calendar me-1"></i>
          {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
        </p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('site.courses') }}" class="btn btn-primary">
          <i class="fas fa-search me-2"></i>Découvrir les cours
        </a>
        <a href="https://academie-numerique-n4du.onrender.com/" target="_blank" class="btn btn-outline-primary">
          <i class="fas fa-file-alt me-2"></i>Mes examens
        </a>
      </div>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Paiements en attente --}}
  @if($pendingPayments->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-3 mb-4">
      <i class="fas fa-clock fa-2x"></i>
      <div>
        <strong>{{ $pendingPayments->count() }} paiement(s) en attente de validation !</strong>
        <div class="small">L'administrateur va valider vos inscriptions sous peu.</div>
      </div>
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ══ STATISTIQUES ══ --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100 stat-card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(59,31,140,0.1);">
            <i class="fas fa-book fa-2x" style="color:#3B1F8C;"></i>
          </div>
          <div>
            <div class="stat-number h3 fw-bold mb-0" style="color:#3B1F8C;"
                 data-target="{{ $totalCours }}">0</div>
            <div class="small text-muted">Cours inscrits</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100 stat-card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(245,127,23,0.1);">
            <i class="fas fa-spinner fa-2x" style="color:#F57F17;"></i>
          </div>
          <div>
            <div class="stat-number h3 fw-bold mb-0" style="color:#F57F17;"
                 data-target="{{ $coursEnCours }}">0</div>
            <div class="small text-muted">En cours</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100 stat-card">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle" style="background:rgba(13,79,60,0.1);">
            <i class="fas fa-check-circle fa-2x" style="color:#0D4F3C;"></i>
          </div>
          <div>
            <div class="stat-number h3 fw-bold mb-0" style="color:#0D4F3C;"
                 data-target="{{ $coursTermines }}">0</div>
            <div class="small text-muted">Terminés</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card shadow-sm border-0 h-100 stat-card">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3 mb-2">
            <div class="p-3 rounded-circle" style="background:rgba(26,95,180,0.1);">
              <i class="fas fa-chart-pie fa-2x" style="color:#1A5FB4;"></i>
            </div>
            <div>
              <div class="stat-number h3 fw-bold mb-0" style="color:#1A5FB4;"
                   data-target="{{ $progression }}" data-suffix="%">0%</div>
              <div class="small text-muted">Progression</div>
            </div>
          </div>
          <div class="progress" style="height:8px;border-radius:10px;">
            <div class="progress-bar" style="width:{{ $progression }}%;background:linear-gradient(90deg,#3B1F8C,#7C3AED);border-radius:10px;"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ══ MES COURS INSCRITS ══ --}}
  <div id="mes-cours" class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0" style="color:#3B1F8C;">
        <i class="fas fa-book-open me-2"></i>Mes cours
      </h5>
      <a href="{{ route('site.courses') }}" class="btn btn-sm btn-outline-primary">
        + Trouver d'autres cours
      </a>
    </div>

    @if($enrollments->count() > 0)
      <div class="row g-3">
        @foreach($enrollments as $enrollment)
          @if($enrollment->course)
          <div class="col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius:12px;overflow:hidden;transition:all 0.2s;"
                 onmouseover="this.style.transform='translateY(-4px)'"
                 onmouseout="this.style.transform=''">
              @if($enrollment->course->details?->thumbnail)
                <img src="{{ asset('storage/'.$enrollment->course->details->thumbnail) }}"
                     style="height:140px;object-fit:cover;width:100%;">
              @else
                <div style="height:140px;background:linear-gradient(135deg,#3B1F8C,#7C3AED);display:flex;align-items:center;justify-content:center;">
                  <i class="fas fa-graduation-cap fa-3x text-white"></i>
                </div>
              @endif
              <div class="p-3">
                @if($enrollment->course->category)
                  <span class="badge mb-2" style="background:rgba(59,31,140,0.1);color:#3B1F8C;font-size:0.7rem;">
                    {{ $enrollment->course->category->title }}
                  </span>
                @endif
                <h6 class="fw-bold mb-2">{{ Str::limit($enrollment->course->title, 40) }}</h6>
                <div class="d-flex justify-content-between align-items-center mb-3">
                  @if($enrollment->status == 2)
                    <span class="badge bg-success">✅ Terminé</span>
                  @elseif($enrollment->status == 1)
                    <span class="badge bg-warning text-dark">📚 En cours</span>
                  @else
                    <span class="badge bg-secondary">⏳ En attente</span>
                  @endif
                  <small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                </div>
                <a href="{{ route('course.display', ['slug' => $enrollment->course->slug]) }}"
                   class="btn btn-primary btn-sm w-100">
                  <i class="fas fa-play me-2"></i>
                  {{ $enrollment->status == 2 ? 'Revoir' : 'Continuer' }}
                </a>
              </div>
            </div>
          </div>
          @endif
        @endforeach
      </div>
    @else
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
          <i class="fas fa-book-open fa-4x d-block mb-3" style="color:rgba(59,31,140,0.2);"></i>
          <h5 class="text-muted">Aucun cours inscrit</h5>
          <p class="text-muted small">Explorez notre catalogue et commencez votre apprentissage !</p>
          <a href="{{ route('site.courses') }}" class="btn btn-primary mt-2">
            <i class="fas fa-search me-2"></i>Découvrir les cours
          </a>
        </div>
      </div>
    @endif
  </div>

  {{-- ══ COURS DISPONIBLES (avec bouton Payer) ══ --}}
  @if($coursDisponibles->count() > 0)
  <div id="catalogue">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0" style="color:#F57F17;">
        <i class="fas fa-compass me-2"></i>Cours disponibles
      </h5>
      <a href="{{ route('site.courses') }}" class="btn btn-sm btn-outline-warning">
        Voir tout le catalogue
      </a>
    </div>
    <div class="row g-3">
      @foreach($coursDisponibles as $cours)
      <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;overflow:hidden;transition:all 0.2s;"
             onmouseover="this.style.transform='translateY(-4px)'"
             onmouseout="this.style.transform=''">
          @if($cours->details?->thumbnail)
            <img src="{{ asset('storage/'.$cours->details->thumbnail) }}"
                 style="height:140px;object-fit:cover;width:100%;">
          @else
            <div style="height:140px;background:linear-gradient(135deg,#F57F17,#FFB300);display:flex;align-items:center;justify-content:center;">
              <i class="fas fa-star fa-3x text-white"></i>
            </div>
          @endif
          <div class="p-3">
            @if($cours->category)
              <span class="badge mb-2" style="background:rgba(245,127,23,0.1);color:#F57F17;font-size:0.7rem;">
                {{ $cours->category->title }}
              </span>
            @endif
            <h6 class="fw-bold mb-2">{{ Str::limit($cours->title, 40) }}</h6>
            <div class="d-flex gap-3 small text-muted mb-3">
              @if($cours->details?->duration)
                <span><i class="fas fa-clock me-1"></i>{{ $cours->details->duration }}</span>
              @endif
              <span><i class="fas fa-list me-1"></i>{{ $cours->lessons?->count() ?? 0 }} leçons</span>
            </div>

            {{-- Prix + bouton Payer --}}
            @php
              $hasPendingPayment = $pendingPayments->where('course_id', $cours->id)->count() > 0;
            @endphp

            @if($hasPendingPayment)
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-warning text-dark">⏳ Paiement en attente</span>
              </div>
            @else
              <div class="d-flex justify-content-between align-items-center">
                @if($cours->regular_price > 0)
                  <div>
                    <div class="fw-bold" style="color:#3B1F8C;">
                      {{ number_format($cours->offer_price ?? $cours->regular_price, 0, ',', ' ') }} FCFA
                    </div>
                    @if($cours->offer_price && $cours->offer_price < $cours->regular_price)
                      <small class="text-muted text-decoration-line-through">
                        {{ number_format($cours->regular_price, 0) }} FCFA
                      </small>
                    @endif
                  </div>
                  <a href="{{ route('payment.show', $cours->id) }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-credit-card me-1"></i>Payer
                  </a>
                @else
                  <span class="badge bg-success">Gratuit</span>
                  <a href="{{ route('course.display', ['slug' => $cours->slug]) }}"
                     class="btn btn-sm btn-success">
                    <i class="fas fa-play me-1"></i>Accéder
                  </a>
                @endif
              </div>
            @endif
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
  @endif

  <x-slot name="script">
  <script>
    document.querySelectorAll('.stat-number[data-target]').forEach(el => {
      const target = parseInt(el.dataset.target);
      const suffix = el.dataset.suffix ?? '';
      if (!target) return;
      let current = 0;
      const step = Math.max(1, Math.ceil(target / 30));
      const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current + suffix;
        if (current >= target) clearInterval(timer);
      }, 40);
    });
  </script>
  </x-slot>
</x-student-layout>

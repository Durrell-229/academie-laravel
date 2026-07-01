<x-student-layout>
  <x-slot name="title">Paiement — {{ $course->title }}</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#3B1F8C;">
      💳 Paiement pour accéder au cours
    </h1>
  </x-slot>

  @if($pendingPayment)
    <div class="alert alert-warning d-flex align-items-center gap-3">
      <i class="fas fa-clock fa-2x"></i>
      <div>
        <strong>Paiement en attente de validation !</strong>
        <div class="small">Votre reçu a été soumis le {{ $pendingPayment->created_at->format('d/m/Y à H:i') }}.</div>
        <div class="small">L'administrateur va valider votre inscription sous peu.</div>
      </div>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
      <i class="fas fa-home me-2"></i>Retour au tableau de bord
    </a>

  @else

  <div class="row g-4">

    {{-- ── Résumé du cours ── --}}
    <div class="col-lg-4">

      <div class="card border-0 shadow-sm mb-3" style="border-top:3px solid #3B1F8C!important;">
        <div class="card-body">
          @if($course->details?->thumbnail)
            <img src="{{ asset('storage/'.$course->details->thumbnail) }}"
                 class="img-fluid rounded mb-3" style="width:100%;height:160px;object-fit:cover;">
          @else
            <div class="rounded mb-3 d-flex align-items-center justify-content-center"
                 style="height:160px;background:linear-gradient(135deg,#3B1F8C,#7C3AED);">
              <i class="fas fa-graduation-cap fa-3x text-white"></i>
            </div>
          @endif

          <h5 class="fw-bold mb-2">{{ $course->title }}</h5>

          @if($course->category)
            <span class="badge mb-2" style="background:rgba(59,31,140,0.1);color:#3B1F8C;">
              {{ $course->category->title }}
            </span>
          @endif

          @if($course->user)
            <div class="small text-muted mb-1">
              <i class="fas fa-chalkboard-teacher me-1"></i>
              Prof. {{ $course->user->firstname }} {{ $course->user->lastname }}
            </div>
          @endif

          @if($course->lessons)
            <div class="small text-muted mb-3">
              <i class="fas fa-list me-1"></i>
              {{ $course->lessons->count() }} leçons
            </div>
          @endif

          <hr>

          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted">Prix</span>
            @if($course->offer_price && $course->offer_price < $course->regular_price)
              <div class="text-end">
                <div class="h4 fw-bold mb-0" style="color:#3B1F8C;">
                  {{ number_format($amount, 0, ',', ' ') }} FCFA
                </div>
                <small class="text-muted text-decoration-line-through">
                  {{ number_format($course->regular_price, 0, ',', ' ') }} FCFA
                </small>
              </div>
            @else
              <div class="h4 fw-bold mb-0" style="color:#3B1F8C;">
                {{ number_format($amount, 0, ',', ' ') }} FCFA
              </div>
            @endif
          </div>
        </div>
      </div>

      {{-- Répartition --}}
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h6 class="mb-0 fw-bold">💰 Répartition automatique</h6>
        </div>
        <div class="card-body">
          @php $split = \App\Models\CoursePayment::calculateSplit($amount); @endphp
          <div class="d-flex justify-content-between mb-2">
            <span class="small text-muted">Super Admin (20%)</span>
            <span class="small fw-semibold text-success">{{ number_format($split['amount_super_admin'], 0) }} FCFA</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="small text-muted">Professeur (10%)</span>
            <span class="small fw-semibold text-primary">{{ number_format($split['amount_professor'], 0) }} FCFA</span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="small text-muted">Académie (70%)</span>
            <span class="small fw-semibold text-warning">{{ number_format($split['amount_admin'], 0) }} FCFA</span>
          </div>
          <hr class="my-2">
          <div class="d-flex justify-content-between">
            <span class="small fw-bold">Total</span>
            <span class="small fw-bold" style="color:#3B1F8C;">{{ number_format($amount, 0) }} FCFA</span>
          </div>
        </div>
      </div>

    </div>

    {{-- ── Formulaire de paiement ── --}}
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm" style="border-top:3px solid #3B1F8C!important;">
        <div class="card-body p-4">

          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
              <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          {{-- Étape 1 : Choisir l'opérateur ──────────────────── --}}
          <h5 class="fw-bold mb-4" style="color:#3B1F8C;">
            <span class="badge rounded-circle me-2 text-white"
                  style="background:#3B1F8C;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;font-size:0.8rem;">1</span>
            Choisissez votre opérateur Mobile Money
          </h5>

          <div class="row g-3 mb-4" id="operatorCards">

            {{-- MTN MoMo --}}
            <div class="col-md-4">
              <div class="operator-card card border-2 h-100 text-center py-3 px-2"
                   style="cursor:pointer;border-color:#e9ecef;transition:all 0.2s;"
                   data-operator="mtn"
                   data-number="0149518565"
                   onclick="selectOperator(this)">
                <div class="mb-2">
                  <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                       style="width:60px;height:60px;background:#FFC107;">
                    <span style="font-size:1.4rem;font-weight:900;color:#333;">M</span>
                  </div>
                </div>
                <div class="fw-bold">MTN MoMo</div>
                <div class="small text-muted">Mobile Money</div>
                <div class="small fw-semibold mt-1" style="color:#FFC107;">📱 0149518565</div>
              </div>
            </div>

            {{-- Moov Money --}}
            <div class="col-md-4">
              <div class="operator-card card border-2 h-100 text-center py-3 px-2"
                   style="cursor:pointer;border-color:#e9ecef;transition:all 0.2s;"
                   data-operator="moov"
                   data-number="0149518565"
                   onclick="selectOperator(this)">
                <div class="mb-2">
                  <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                       style="width:60px;height:60px;background:#00A651;">
                    <span style="font-size:1.4rem;font-weight:900;color:white;">M</span>
                  </div>
                </div>
                <div class="fw-bold">Moov Money</div>
                <div class="small text-muted">Flooz</div>
                <div class="small fw-semibold mt-1" style="color:#00A651;">📱 0149518565</div>
              </div>
            </div>

            {{-- Celtiis Cash --}}
            <div class="col-md-4">
              <div class="operator-card card border-2 h-100 text-center py-3 px-2"
                   style="cursor:pointer;border-color:#e9ecef;transition:all 0.2s;"
                   data-operator="celtiis"
                   data-number="0149518565"
                   onclick="selectOperator(this)">
                <div class="mb-2">
                  <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center"
                       style="width:60px;height:60px;background:#E30613;">
                    <span style="font-size:1.4rem;font-weight:900;color:white;">C</span>
                  </div>
                </div>
                <div class="fw-bold">Celtiis Cash</div>
                <div class="small text-muted">Mobile Money</div>
                <div class="small fw-semibold mt-1" style="color:#E30613;">📱 0149518565</div>
              </div>
            </div>

          </div>

          {{-- Instructions dynamiques ──────────────────────────── --}}
          <div id="paymentInstructions" class="d-none mb-4">
            <div class="alert border-0 p-4" style="background:rgba(59,31,140,0.05);border-left:4px solid #3B1F8C!important;">
              <h6 class="fw-bold mb-3">
                <i class="fas fa-info-circle me-2" style="color:#3B1F8C;"></i>
                Instructions de paiement
              </h6>
              <ol class="mb-0 small" style="line-height:2.2;">
                <li>Ouvrez votre application <strong id="operatorName"></strong></li>
                <li>Sélectionnez <strong>Transfert d'argent</strong></li>
                <li>Envoyez <strong class="text-primary">{{ number_format($amount, 0, ',', ' ') }} FCFA</strong> au numéro : <strong id="operatorNumber" class="h5" style="color:#3B1F8C;"></strong></li>
                <li>Prenez une <strong>capture d'écran</strong> du message de confirmation</li>
                <li>Uploadez la capture ci-dessous</li>
              </ol>
            </div>
          </div>

          {{-- Étape 2 : Formulaire ──────────────────────────────── --}}
          <div id="formSection" class="d-none">
            <h5 class="fw-bold mb-4" style="color:#3B1F8C;">
              <span class="badge rounded-circle me-2 text-white"
                    style="background:#3B1F8C;width:28px;height:28px;display:inline-flex;align-items:center;justify-content:center;font-size:0.8rem;">2</span>
              Confirmez votre paiement
            </h5>

            <form method="POST"
                  action="{{ route('payment.store', $course->id) }}"
                  enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="operator" id="hiddenOperator">

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">
                    Votre numéro <span id="operatorLabel"></span>
                    <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" name="momo_number" class="form-control form-control-lg"
                           placeholder="Ex: 0197000000" required
                           value="{{ old('momo_number') }}" />
                  </div>
                  <small class="text-muted">Le numéro depuis lequel vous avez envoyé.</small>
                </div>

                <div class="col-md-6">
                  <label class="form-label fw-semibold">
                    Montant envoyé <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <input type="number" name="amount_sent" class="form-control form-control-lg"
                           value="{{ $amount }}" readonly
                           style="background:#f8f9fa;" />
                    <span class="input-group-text fw-bold">FCFA</span>
                  </div>
                </div>

                <div class="col-12">
                  <label class="form-label fw-semibold">
                    Capture d'écran de la confirmation
                    <span class="text-danger">*</span>
                  </label>
                  <div class="border-2 rounded-3 p-4 text-center"
                       style="border:2px dashed rgba(59,31,140,0.3);cursor:pointer;transition:all 0.2s;"
                       id="dropZone"
                       onclick="document.getElementById('receiptInput').click()"
                       ondragover="event.preventDefault();this.style.background='rgba(59,31,140,0.05)'"
                       ondragleave="this.style.background=''"
                       ondrop="handleDrop(event)">
                    <div id="uploadPlaceholder">
                      <i class="fas fa-cloud-upload-alt fa-3x d-block mb-2" style="color:#3B1F8C;"></i>
                      <p class="fw-semibold mb-1" style="color:#3B1F8C;">Cliquez ou glissez votre capture</p>
                      <p class="text-muted small mb-0">JPG, PNG ou PDF — max 5MB</p>
                    </div>
                    <div id="uploadPreview" class="d-none">
                      <i class="fas fa-check-circle fa-3x d-block mb-2 text-success"></i>
                      <p class="fw-semibold text-success mb-0" id="uploadFileName"></p>
                    </div>
                  </div>
                  <input type="file" name="receipt" id="receiptInput" class="d-none"
                         accept=".jpg,.jpeg,.png,.pdf"
                         onchange="handleFileSelect(this)" />
                </div>

                <div class="col-12 mt-2">
                  <div class="d-flex gap-3 align-items-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5"
                            style="background:#3B1F8C;border-color:#3B1F8C;">
                      <i class="fas fa-paper-plane me-2"></i>Soumettre mon paiement
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                      Annuler
                    </a>
                  </div>
                  <small class="text-muted mt-2 d-block">
                    <i class="fas fa-shield-alt me-1 text-success"></i>
                    Votre paiement sera vérifié par l'administrateur avant activation.
                  </small>
                </div>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>

  @endif

  <x-slot name="script">
  <script>
    const operatorColors = {
      mtn:     { color: '#FFC107', bg: 'rgba(255,193,7,0.08)',    border: '#FFC107', name: 'MTN MoMo' },
      moov:    { color: '#00A651', bg: 'rgba(0,166,81,0.08)',     border: '#00A651', name: 'Moov Money (Flooz)' },
      celtiis: { color: '#E30613', bg: 'rgba(227,6,19,0.08)',     border: '#E30613', name: 'Celtiis Cash' },
    };

    function selectOperator(card) {
      // Reset all cards
      document.querySelectorAll('.operator-card').forEach(c => {
        c.style.borderColor = '#e9ecef';
        c.style.background = 'white';
        c.style.transform = '';
      });

      // Highlight selected
      const op = card.dataset.operator;
      const num = card.dataset.number;
      const colors = operatorColors[op];

      card.style.borderColor = colors.border;
      card.style.background = colors.bg;
      card.style.transform = 'translateY(-3px)';

      // Update instructions
      document.getElementById('operatorName').textContent = colors.name;
      document.getElementById('operatorNumber').textContent = num;
      document.getElementById('operatorLabel').textContent = colors.name;
      document.getElementById('hiddenOperator').value = op;

      // Show sections
      document.getElementById('paymentInstructions').classList.remove('d-none');
      document.getElementById('formSection').classList.remove('d-none');

      // Scroll to form
      setTimeout(() => {
        document.getElementById('formSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 300);
    }

    function handleFileSelect(input) {
      if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('uploadFileName').textContent = file.name;
        document.getElementById('uploadPlaceholder').classList.add('d-none');
        document.getElementById('uploadPreview').classList.remove('d-none');
        document.getElementById('dropZone').style.background = 'rgba(25,135,84,0.05)';
        document.getElementById('dropZone').style.borderColor = '#198754';
      }
    }

    function handleDrop(e) {
      e.preventDefault();
      const file = e.dataTransfer.files[0];
      if (file) {
        const input = document.getElementById('receiptInput');
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;
        handleFileSelect(input);
      }
      e.currentTarget.style.background = '';
    }
  </script>
  </x-slot>
</x-student-layout>

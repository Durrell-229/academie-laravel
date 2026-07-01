<x-admin-layout>
  <x-slot name="title">Gestion des paiements</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#0A2D6E;">
      <i class="fas fa-money-bill me-2"></i>Gestion des paiements
    </h1>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- Stats --}}
  @php
    $pending   = $payments->where('status','pending')->count();
    $validated = $payments->where('status','validated')->count();
    $rejected  = $payments->where('status','rejected')->count();
    $totalAmount = $payments->where('status','validated')->sum('amount');
  @endphp

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3" style="border-left:4px solid #F57F17!important;">
        <div class="h3 fw-bold text-warning mb-0">{{ $pending }}</div>
        <div class="small text-muted">⏳ En attente</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3" style="border-left:4px solid #0D4F3C!important;">
        <div class="h3 fw-bold text-success mb-0">{{ $validated }}</div>
        <div class="small text-muted">✅ Validés</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3" style="border-left:4px solid #C62828!important;">
        <div class="h3 fw-bold text-danger mb-0">{{ $rejected }}</div>
        <div class="small text-muted">❌ Rejetés</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3" style="border-left:4px solid #0A2D6E!important;">
        <div class="h4 fw-bold mb-0" style="color:#0A2D6E;">{{ number_format($totalAmount, 0) }}</div>
        <div class="small text-muted">FCFA collectés</div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($payments->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Apprenant</th>
                <th>Cours</th>
                <th>Montant</th>
                <th>Répartition</th>
                <th>N° MoMo utilisé</th>
                <th>Reçu</th>
                <th>Statut</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($payments as $i => $payment)
              <tr style="{{ $payment->status === 'pending' ? 'background:rgba(255,193,7,0.05);' : '' }}">
                <td>{{ $i+1 }}</td>
                <td>
                  <div class="fw-semibold small">{{ $payment->user?->firstname }} {{ $payment->user?->lastname }}</div>
                  <small class="text-muted">{{ $payment->user?->email }}</small>
                </td>
                <td>
                  <div class="small fw-semibold">{{ Str::limit($payment->course?->title ?? '—', 30) }}</div>
                  <small class="text-muted">Prof: {{ $payment->course?->user?->firstname }}</small>
                </td>
                <td>
                  <div class="fw-bold" style="color:#0A2D6E;">{{ number_format($payment->amount, 0) }} FCFA</div>
                </td>
                <td>
                  <div class="small" style="line-height:1.8;">
                    <span class="text-muted">S.Admin:</span> <strong>{{ number_format($payment->amount_super_admin, 0) }}</strong><br>
                    <span class="text-muted">Prof:</span> <strong>{{ number_format($payment->amount_professor, 0) }}</strong><br>
                    <span class="text-muted">Admin:</span> <strong>{{ number_format($payment->amount_admin, 0) }}</strong>
                  </div>
                </td>
                <td><code>{{ $payment->momo_number }}</code></td>
                <td>
                  @if($payment->receipt_file)
                    <a href="{{ asset('storage/'.$payment->receipt_file) }}" target="_blank"
                       class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-eye me-1"></i>Voir
                    </a>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
                <td>
                  @if($payment->status === 'pending')
                    <span class="badge bg-warning text-dark">⏳ En attente</span>
                  @elseif($payment->status === 'validated')
                    <span class="badge bg-success">✅ Validé</span>
                    <div class="small text-muted">{{ $payment->validated_at?->format('d/m/Y') }}</div>
                  @else
                    <span class="badge bg-danger">❌ Rejeté</span>
                  @endif
                </td>
                <td>
                  @if($payment->status === 'pending')
                    <div class="d-flex flex-column gap-1">
                      <form method="POST" action="{{ route('payments.validate', $payment->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm w-100"
                                onclick="return confirm('Valider ce paiement et donner accès au cours ?')">
                          <i class="fas fa-check me-1"></i>Valider
                        </button>
                      </form>
                      <form method="POST" action="{{ route('payments.reject', $payment->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-100"
                                onclick="return confirm('Rejeter ce paiement ?')">
                          <i class="fas fa-times me-1"></i>Rejeter
                        </button>
                      </form>
                    </div>
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-5">
          <i class="fas fa-money-bill fa-4x d-block mb-3" style="color:rgba(10,45,110,0.2);"></i>
          <h5 class="text-muted">Aucun paiement enregistré.</h5>
          <p class="text-muted small">Les paiements apparaîtront ici quand des apprenants s'inscriront aux cours.</p>
        </div>
      @endif
    </div>
  </div>
</x-admin-layout>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paiement — {{ $course->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f9; }
        .payment-hero {
            background: linear-gradient(135deg, #0A2D6E, #1A5FB4);
            padding: 40px 0 60px;
        }
        .payment-card {
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(10,45,110,0.12);
            margin-top: -30px;
        }
        .step-badge {
            width: 36px; height: 36px;
            background: #0A2D6E; color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.9rem; flex-shrink: 0;
        }
        .feature-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 0; border-bottom: 1px solid #f0f0f0;
        }
        .feature-item:last-child { border-bottom: none; }
        .btn-pay {
            background: linear-gradient(135deg, #0A2D6E, #1A5FB4);
            border: none; color: white; font-weight: 700;
            padding: 16px 32px; border-radius: 12px; font-size: 1.1rem;
            width: 100%; transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(10,45,110,0.3);
        }
        .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(10,45,110,0.4); color: white; }
        .btn-pay:disabled { opacity: 0.7; transform: none; cursor: not-allowed; }
        .security-badge {
            background: #f0f9f0; border: 1px solid #c3e6cb;
            border-radius: 10px; padding: 12px 16px;
        }
        .status-box {
            border-radius: 12px; padding: 16px;
            display: none; margin-top: 16px;
        }
        .status-box.success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .status-box.error   { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .status-box.loading { background: #e8f4fd; border: 1px solid #bee5eb; color: #0c5460; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner { animation: spin 1s linear infinite; display: inline-block; }
        .sandbox-alert {
            background: #fff8e1; border: 1px solid #ffe082;
            border-radius: 10px; padding: 12px 16px; font-size: 0.88rem;
        }
    </style>
</head>
<body>

@include('partials.front.navbar')

{{-- HERO --}}
<div class="payment-hero">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('site.home') }}" class="text-white-50">Accueil</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('site.courses') }}" class="text-white-50">Cours</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('course.display', $course->slug) }}" class="text-white-50">
                        {{ Str::limit($course->title, 30) }}
                    </a>
                </li>
                <li class="breadcrumb-item active text-white">Paiement</li>
            </ol>
        </nav>
        <h1 class="text-white fw-bold mb-1">
            <i class="fas fa-lock-open me-3"></i>Accéder au cours
        </h1>
        <p class="text-white-50 mb-0">Finalisez votre paiement pour débloquer immédiatement l'accès.</p>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4 justify-content-center">

        {{-- COLONNE PRINCIPALE --}}
        <div class="col-lg-7">
            <div class="card border-0 payment-card p-4 mb-4">

                {{-- Info cours --}}
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:56px;height:56px;background:linear-gradient(135deg,#0A2D6E,#1A5FB4);">
                        <i class="fas fa-graduation-cap text-white fa-xl"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-1">Vous payez pour</div>
                        <h5 class="fw-bold mb-0" style="color:#0A2D6E;">{{ $course->title }}</h5>
                        @if($course->user)
                            <div class="text-muted small">
                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                {{ $course->user->firstname }} {{ $course->user->lastname }}
                            </div>
                        @endif
                    </div>
                    <div class="ms-auto text-end">
                        <div class="h3 fw-bold mb-0" style="color:#0A2D6E;">{{ number_format($montant, 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted small">Accès complet</div>
                    </div>
                </div>

                {{-- Alerte sandbox --}}
                @if($kkiapaySandbox === 'true')
                <div class="sandbox-alert mb-4">
                    <i class="fas fa-flask text-warning me-2"></i>
                    <strong>Mode TEST</strong> — Aucun argent réel ne sera débité.
                    Utilisez le numéro de test <strong>61000000</strong> (MTN Bénin) pour simuler un paiement réussi.
                </div>
                @endif

                {{-- Étapes --}}
                <h6 class="fw-bold mb-3" style="color:#0A2D6E;">Comment ça marche ?</h6>
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="step-badge">1</div>
                        <div>
                            <div class="fw-semibold">Cliquez sur "Payer maintenant"</div>
                            <div class="text-muted small">Le widget de paiement sécurisé Kkiapay s'ouvre</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="step-badge">2</div>
                        <div>
                            <div class="fw-semibold">Choisissez votre moyen de paiement</div>
                            <div class="text-muted small">MTN MoMo, Moov Money ou Carte bancaire</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-3">
                        <div class="step-badge">3</div>
                        <div>
                            <div class="fw-semibold">Accès immédiat au cours</div>
                            <div class="text-muted small">Dès confirmation, toutes les leçons sont débloquées</div>
                        </div>
                    </div>
                </div>

{{-- Zone statut --}}
<div id="status-box" class="status-box">
    <span id="status-icon"></span>
    <span id="status-message"></span>
</div>

{{-- OPTION 1 : Kkiapay --}}
<div class="mb-3">
    <div class="text-muted small fw-semibold mb-2">
        <i class="fas fa-mobile-alt me-1"></i> PAIEMENT MOBILE MONEY / CARTE
    </div>
    <button id="btn-payer" class="btn-pay" onclick="lancerPaiement()">
        <i class="fas fa-lock-open me-2"></i>
        Payer {{ number_format($montant, 0, ',', ' ') }} FCFA via Kkiapay
    </button>
    <div class="text-center mt-1">
        <small class="text-muted">MTN MoMo · Moov Money · Carte bancaire</small>
    </div>
</div>

{{-- Séparateur --}}
<div class="d-flex align-items-center gap-3 my-3">
    <hr class="flex-grow-1 m-0">
    <span class="text-muted small fw-semibold">OU</span>
    <hr class="flex-grow-1 m-0">
</div>

{{-- OPTION 2 : FedaPay --}}
<div class="mb-3">
    <div class="text-muted small fw-semibold mb-2">
        <i class="fas fa-credit-card me-1"></i> PAIEMENT VIA FEDAPAY
    </div>
    <form method="POST" action="{{ route('payment.fedapay.initier', $course->id) }}">
        @csrf
        <button type="submit" class="btn-pay" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
            <i class="fas fa-external-link-alt me-2"></i>
            Payer {{ number_format($montant, 0, ',', ' ') }} FCFA via FedaPay
        </button>
    </form>
    <div class="text-center mt-1">
        <small class="text-muted">Mobile Money · Virement · Carte</small>
    </div>
</div>

                {{-- Sécurité --}}
                <div class="security-badge mt-3 d-flex align-items-center gap-2">
                    <i class="fas fa-shield-alt text-success fa-lg"></i>
                    <div>
                        <div class="fw-semibold small text-success">Paiement 100% sécurisé</div>
                        <div class="text-muted" style="font-size:0.78rem;">
                            Vos données sont protégées par le chiffrement SSL. Propulsé par Kkiapay.
                        </div>
                    </div>
                </div>

            </div>

            {{-- Lien retour --}}
            <div class="text-center">
                <a href="{{ route('course.display', $course->slug) }}" class="text-muted text-decoration-none small">
                    <i class="fas fa-arrow-left me-1"></i>Retour à la fiche du cours
                </a>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-4">

            {{-- Résumé commande --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
                <div class="card-header bg-white py-3" style="border-radius:16px 16px 0 0;">
                    <h6 class="mb-0 fw-bold" style="color:#0A2D6E;">
                        <i class="fas fa-receipt me-2"></i>Résumé de la commande
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Cours</span>
                        <span class="fw-semibold">{{ Str::limit($course->title, 20) }}</span>
                    </div>
                    @if($course->offer_price && $course->offer_price < $course->regular_price)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Prix normal</span>
                        <span class="text-decoration-line-through text-muted">{{ number_format($course->regular_price, 0) }} FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-success fw-semibold">Réduction</span>
                        <span class="text-success fw-semibold">-{{ number_format($course->regular_price - $course->offer_price, 0) }} FCFA</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold fs-5" style="color:#0A2D6E;">{{ number_format($montant, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>

            {{-- Ce que vous obtenez --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
                <div class="card-header bg-white py-3" style="border-radius:16px 16px 0 0;">
                    <h6 class="mb-0 fw-bold" style="color:#0A2D6E;">
                        <i class="fas fa-gift me-2"></i>Ce que vous obtenez
                    </h6>
                </div>
                <div class="card-body">
                    <div class="feature-item">
                        <i class="fas fa-infinity text-primary"></i>
                        <span class="small">Accès illimité au cours</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-play-circle text-danger"></i>
                        <span class="small">{{ $course->lessons->count() }} leçon(s) disponibles</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-mobile-alt text-success"></i>
                        <span class="small">Accessible sur tous les appareils</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-certificate text-warning"></i>
                        <span class="small">Certificat de réussite</span>
                    </div>
                </div>
            </div>

            {{-- Moyens de paiement --}}
            <div class="card border-0 shadow-sm" style="border-radius:16px;">
                <div class="card-body text-center p-4">
                    <div class="text-muted small mb-3 fw-semibold">MOYENS DE PAIEMENT ACCEPTÉS</div>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <div class="text-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                                 style="width:48px;height:48px;background:#FFC107;">
                                <span class="fw-bold text-dark" style="font-size:0.75rem;">MTN</span>
                            </div>
                            <div style="font-size:0.7rem;" class="text-muted">MoMo</div>
                        </div>
                        <div class="text-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                                 style="width:48px;height:48px;background:#00B0CC;">
                                <span class="fw-bold text-white" style="font-size:0.7rem;">MOOV</span>
                            </div>
                            <div style="font-size:0.7rem;" class="text-muted">Money</div>
                        </div>
                        <div class="text-center">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-1"
                                 style="width:48px;height:48px;background:#1A1F71;">
                                <i class="fas fa-credit-card text-white"></i>
                            </div>
                            <div style="font-size:0.7rem;" class="text-muted">Carte</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@include('partials.front.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.kkiapay.me/k.js"></script>

<script>
    const KKIAPAY_KEY       = "{{ $kkiapayPublicKey }}";
    const KKIAPAY_SANDBOX   = {{ $kkiapaySandbox }};
    const MONTANT           = {{ $montant }};
    const REFERENCE_INTERNE = "{{ $referenceInterne }}";
    const CSRF_TOKEN        = document.querySelector('meta[name="csrf-token"]').content;
    const CONFIRM_URL       = "{{ route('payment.store', $course->id) }}";
    const SUCCESS_URL       = "{{ route('course.display', $course->slug) }}";

    function lancerPaiement() {
        openKkiapayWidget({
            amount:  MONTANT,
            key:     KKIAPAY_KEY,
            sandbox: KKIAPAY_SANDBOX,
            data:    REFERENCE_INTERNE,
            reason:  "Paiement du cours : {{ addslashes($course->title) }}",
        });
    }

    function afficherStatut(type, message) {
        const box = document.getElementById('status-box');
        const msg = document.getElementById('status-message');
        const ico = document.getElementById('status-icon');
        box.className = 'status-box ' + type;
        box.style.display = 'block';
        if (type === 'loading') {
            ico.innerHTML = '<i class="fas fa-circle-notch spinner me-2"></i>';
        } else if (type === 'success') {
            ico.innerHTML = '<i class="fas fa-check-circle me-2"></i>';
        } else {
            ico.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i>';
        }
        msg.textContent = message;
    }

    addSuccessListener(function(response) {
        const btn = document.getElementById('btn-payer');
        btn.disabled = true;
        afficherStatut('loading', 'Vérification du paiement en cours…');

        fetch(CONFIRM_URL, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'X-CSRF-TOKEN':  CSRF_TOKEN,
            },
            body: JSON.stringify({
                transaction_id:    response.transactionId,
                reference_interne: REFERENCE_INTERNE,
            }),
        })
        .then(res => res.json())
        .then(data => {
            if (data.statut === 'succes' || data.statut === 'deja_confirme') {
                afficherStatut('success', '✅ Paiement confirmé ! Redirection vers le cours…');
                setTimeout(() => { window.location.href = SUCCESS_URL; }, 2000);
            } else {
                btn.disabled = false;
                afficherStatut('error', '❌ ' + (data.raison || 'Le paiement n\'a pas pu être confirmé.'));
            }
        })
        .catch(() => {
            btn.disabled = false;
            afficherStatut('error', 'Erreur réseau. Vérifiez votre connexion et réessayez.');
        });
    });

    
</script>
</body>
</html>
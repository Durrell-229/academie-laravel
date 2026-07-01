<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }} — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family:'Segoe UI',sans-serif; background:#f4f6f9; }
        .course-hero { background:linear-gradient(135deg,#0A2D6E,#1A5FB4); padding:40px 0; }
        .tab-btn { border:none; background:none; padding:12px 20px; color:#6c757d; border-bottom:3px solid transparent; font-weight:500; }
        .tab-btn.active { color:#0A2D6E; border-bottom-color:#0A2D6E; font-weight:700; }
        .lesson-row { cursor:pointer; transition:background 0.2s; border-radius:8px; }
        .lesson-row:hover { background:rgba(10,45,110,0.04); }
        .lesson-content { border-top:1px solid #e9ecef; }
        .locked-msg { background:rgba(255,193,7,0.1); border:1px solid rgba(255,193,7,0.3); border-radius:8px; }
        .live-btn { transition:transform 0.2s; }
        .live-btn:hover { transform:translateY(-2px); }
        video, iframe { border-radius:8px; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
        .pdf-frame { width:100%; height:450px; border:none; border-radius:8px; }
    </style>
</head>
<body>

@include('partials.front.navbar')

{{-- ═══ HERO ═══ --}}
<div class="course-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('site.home') }}" class="text-white-50">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('site.courses') }}" class="text-white-50">Cours</a></li>
                        <li class="breadcrumb-item active text-white">{{ Str::limit($course->title,40) }}</li>
                    </ol>
                </nav>
                <h1 class="fw-bold text-white mb-2">{{ $course->title }}</h1>
                @if($course->details?->description)
                    <p class="text-white-50 mb-3">{{ Str::limit($course->details->description,120) }}</p>
                @endif
                <div class="d-flex flex-wrap gap-2">
                    @if($course->category)
                        <span class="badge px-3 py-2" style="background:rgba(255,255,255,0.2);">
                            <i class="fas fa-tag me-1"></i>{{ $course->category->title }}
                        </span>
                    @endif
                    @if($course->user)
                        <span class="badge px-3 py-2" style="background:rgba(255,255,255,0.2);">
                            <i class="fas fa-chalkboard-teacher me-1"></i>{{ $course->user->firstname }} {{ $course->user->lastname }}
                        </span>
                    @endif
                    <span class="badge px-3 py-2" style="background:rgba(255,255,255,0.2);">
                        <i class="fas fa-list me-1"></i>{{ $course->lessons->count() }} leçons
                    </span>
                    @if($course->details?->duration)
                        <span class="badge px-3 py-2" style="background:rgba(255,255,255,0.2);">
                            <i class="fas fa-clock me-1"></i>{{ $course->details->duration }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                @if($isEnrolled)
                    <span class="badge bg-success px-4 py-3 fs-6">
                        <i class="fas fa-check-circle me-2"></i>Inscrit — Accès complet
                    </span>
                @elseif($hasPendingPayment)
                    <div class="card border-0 d-inline-block p-3 text-start" style="background:rgba(255,255,255,0.1);border-radius:12px;">
                        <div class="text-warning fw-bold mb-1"><i class="fas fa-clock me-2"></i>Paiement en attente</div>
                        <div class="text-white-50 small">L'admin vérifie votre reçu.</div>
                    </div>
                @else
                    <div class="card border-0 d-inline-block p-3 text-center" style="background:rgba(255,255,255,0.12);border-radius:12px;min-width:200px;">
                        <div class="h3 fw-bold text-white mb-1">
                            {{ $course->regular_price > 0 ? number_format($course->offer_price ?? $course->regular_price,0,',',' ').' FCFA' : 'Gratuit' }}
                        </div>
                        @if($course->offer_price && $course->offer_price < $course->regular_price)
                            <div class="text-white-50 text-decoration-line-through small mb-2">{{ number_format($course->regular_price,0) }} FCFA</div>
                        @endif
                        @auth
                            @if($course->regular_price > 0)
                                <a href="{{ route('payment.show',$course->id) }}" class="btn btn-warning btn-lg w-100 fw-bold">
                                    <i class="fas fa-lock-open me-2"></i>Payer pour accéder
                                </a>
                            @else
                                <form method="POST" action="{{ route('enrollment') }}">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <button class="btn btn-success btn-lg w-100 fw-bold">
                                        <i class="fas fa-graduation-cap me-2"></i>S'inscrire gratuitement
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning btn-lg w-100 fw-bold">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter pour payer
                            </a>
                        @endauth
                        <div class="text-white-50 small mt-2">
                            <i class="fas fa-shield-alt me-1"></i>Paiement sécurisé MoMo
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container py-4">

    {{-- ═══ SESSIONS LIVE (visibles par tous) ═══ --}}
    @if($liveSessions->count() > 0)
    <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #E30613!important;">
        <div class="card-body">
            <h5 class="fw-bold mb-3" style="color:#E30613;">
                <span class="badge bg-danger me-2" style="animation:pulse 1.5s infinite;font-size:0.7rem;">LIVE</span>
                Sessions en direct disponibles
            </h5>
            <div class="row g-3">
                @foreach($liveSessions as $session)
                <div class="col-md-4">
                    @if($isEnrolled)
                        <a href="{{ $session->link }}" target="_blank"
                           class="btn live-btn w-100 py-3 d-flex align-items-center justify-content-center gap-2
                           {{ $session->platform==='whatsapp' ? 'btn-success' : ($session->platform==='zoom' ? 'btn-primary' : 'btn-danger') }}">
                            <i class="fa{{ $session->platform==='whatsapp' ? 'b fa-whatsapp' : 's fa-video' }} fa-lg"></i>
                            <div class="text-start">
                                <div class="fw-bold small">{{ $session->title }}</div>
                                <div style="font-size:0.7rem;opacity:0.8;">{{ ucfirst($session->platform) }}
                                    @if($session->scheduled_at) · {{ $session->scheduled_at->format('d/m H:i') }} @endif
                                </div>
                            </div>
                        </a>
                    @else
                        <div class="btn w-100 py-3 d-flex align-items-center justify-content-center gap-2 btn-outline-secondary disabled">
                            <i class="fas fa-lock fa-lg"></i>
                            <div class="text-start">
                                <div class="fw-bold small">{{ $session->title }}</div>
                                <div style="font-size:0.7rem;">Inscrivez-vous pour accéder</div>
                            </div>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="row g-4">

        {{-- ═══ CONTENU PRINCIPAL ═══ --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex border-bottom">
                        <button class="tab-btn active" onclick="switchTab('description',this)">
                            <i class="fas fa-info-circle me-1"></i>Description
                        </button>
                        <button class="tab-btn" onclick="switchTab('content',this)">
                            <i class="fas fa-play-circle me-1"></i>Contenu du cours
                            <span class="badge bg-primary ms-1">{{ $course->lessons->count() }}</span>
                        </button>
                    </div>
                </div>

                {{-- Onglet Description --}}
                <div id="tab-description" class="card-body p-4">
                    <h5 class="fw-bold mb-3">À propos de ce cours</h5>
                    <p class="text-muted" style="line-height:1.8;">
                        {{ $course->details?->description ?? 'Aucune description.' }}
                    </p>
                    @if($course->details?->highlights)
                        <h6 class="fw-bold mt-4 mb-3">
                            <i class="fas fa-check-circle text-success me-2"></i>Ce que vous apprendrez
                        </h6>
                        <div class="row g-2">
                            @foreach(explode("\n", $course->details->highlights) as $point)
                                @if(trim($point))
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-2">
                                        <i class="fas fa-check text-success mt-1 flex-shrink-0"></i>
                                        <span class="small">{{ trim($point) }}</span>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    {{-- Message si non inscrit --}}
                    @if(!$isEnrolled && !$hasPendingPayment)
                    <div class="locked-msg p-4 mt-4 text-center">
                        <i class="fas fa-lock fa-2x text-warning d-block mb-2"></i>
                        <h6 class="fw-bold">Contenu réservé aux apprenants inscrits</h6>
                        <p class="text-muted small mb-3">Payez pour accéder à toutes les leçons, vidéos, PDFs et sessions live.</p>
                        @auth
                            <a href="{{ route('payment.show',$course->id) }}" class="btn btn-warning px-4">
                                <i class="fas fa-lock-open me-2"></i>Accéder au cours — {{ number_format($course->offer_price ?? $course->regular_price,0) }} FCFA
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </a>
                        @endauth
                    </div>
                    @endif
                </div>

                {{-- Onglet Contenu --}}
                <div id="tab-content" class="card-body p-4 d-none">
                    @if($course->lessons->count() > 0)
                        @foreach($course->lessons->where('status',1) as $i => $lesson)
                        <div class="mb-3 border rounded-3 overflow-hidden">

                            {{-- En-tête leçon (cliquable) --}}
                            <div class="lesson-row p-3 d-flex align-items-center gap-3 bg-white"
                                 @if($isEnrolled) onclick="toggleLesson({{ $lesson->id }})" @endif>
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 fw-bold text-white"
                                     style="width:36px;height:36px;background:#0A2D6E;">{{ $i+1 }}</div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $lesson->title }}</div>
                                    <div class="d-flex gap-3 small text-muted mt-1">
                                        @if($lesson->duration)
                                            <span><i class="fas fa-clock me-1"></i>{{ $lesson->duration }}</span>
                                        @endif
                                        @if($lesson->youtube_link || $lesson->video_file)
                                            <span><i class="fas fa-video text-danger me-1"></i>Vidéo</span>
                                        @endif
                                        @if($lesson->pdf_file)
                                            <span><i class="fas fa-file-pdf text-danger me-1"></i>PDF</span>
                                        @endif
                                        @if($lesson->external_link)
                                            <span><i class="fas fa-external-link-alt text-info me-1"></i>Lien</span>
                                        @endif
                                    </div>
                                </div>
                                @if($isEnrolled)
                                    <i class="fas fa-chevron-down text-muted" id="icon-{{ $lesson->id }}"></i>
                                @else
                                    <i class="fas fa-lock text-muted"></i>
                                @endif
                            </div>

                            {{-- Contenu leçon (visible si inscrit) --}}
                            @if($isEnrolled)
                            <div id="lesson-{{ $lesson->id }}" class="lesson-content d-none p-4"
                                 style="background:#fafafa;">

                                {{-- Description --}}
                                @if($lesson->description)
                                    <p class="text-muted mb-4">{{ $lesson->description }}</p>
                                @endif

                                {{-- Vidéo YouTube --}}
                                @if($lesson->youtube_link)
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $lesson->youtube_link, $ytm);
                                        $ytId = $ytm[1] ?? null;
                                    @endphp
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fab fa-youtube text-danger me-2"></i>Vidéo du cours
                                        </h6>
                                        @if($ytId)
                                            <div class="ratio ratio-16x9">
                                                <iframe src="https://www.youtube.com/embed/{{ $ytId }}"
                                                        allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
                                                        allowfullscreen></iframe>
                                            </div>
                                        @else
                                            <a href="{{ $lesson->youtube_link }}" target="_blank" class="btn btn-outline-danger">
                                                <i class="fab fa-youtube me-2"></i>Voir sur YouTube
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                {{-- Vidéo uploadée --}}
                                @if($lesson->video_file)
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fas fa-video text-primary me-2"></i>Vidéo uploadée
                                        </h6>
                                        <video controls class="w-100 rounded" style="max-height:380px;background:#000;">
                                            <source src="{{ asset('storage/'.$lesson->video_file) }}" type="video/mp4">
                                            Votre navigateur ne supporte pas la vidéo.
                                        </video>
                                    </div>
                                @endif

                                {{-- PDF --}}
                                @if($lesson->pdf_file)
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fas fa-file-pdf text-danger me-2"></i>Document de cours
                                        </h6>
                                        <iframe src="{{ asset('storage/'.$lesson->pdf_file) }}"
                                                class="pdf-frame shadow-sm"></iframe>
                                        <div class="mt-2 d-flex gap-2">
                                            <a href="{{ asset('storage/'.$lesson->pdf_file) }}"
                                               target="_blank" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-eye me-1"></i>Voir en plein écran
                                            </a>
                                            <a href="{{ asset('storage/'.$lesson->pdf_file) }}"
                                               download class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-download me-1"></i>Télécharger
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                {{-- Lien externe --}}
                                @if($lesson->external_link)
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-2">
                                            <i class="fas fa-external-link-alt text-info me-2"></i>Ressource / Lien live
                                        </h6>
                                        <a href="{{ $lesson->external_link }}" target="_blank"
                                           class="btn btn-outline-info">
                                            <i class="fas fa-external-link-alt me-2"></i>Ouvrir le lien
                                        </a>
                                    </div>
                                @endif

                                {{-- Aucun contenu --}}
                                @if(!$lesson->youtube_link && !$lesson->video_file && !$lesson->pdf_file && !$lesson->external_link)
                                    <div class="text-center py-3 text-muted">
                                        <i class="fas fa-hourglass-half fa-2x d-block mb-2"></i>
                                        <small>Le professeur n'a pas encore ajouté de contenu à cette leçon.</small>
                                    </div>
                                @endif

                            </div>
                            @else
                            {{-- Message verrouillé --}}
                            <div class="p-3 text-center" style="background:#fafafa;">
                                <i class="fas fa-lock text-warning me-2"></i>
                                <span class="text-muted small">
                                    @if($hasPendingPayment)
                                        Votre paiement est en attente de validation.
                                    @else
                                        <a href="{{ auth()->check() ? route('payment.show',$course->id) : route('login') }}" class="text-primary fw-semibold">
                                            Payez pour accéder à cette leçon
                                        </a>
                                    @endif
                                </span>
                            </div>
                            @endif

                        </div>
                        @endforeach

                        @if($course->lessons->where('status',1)->count() === 0)
                            <div class="text-center py-4 text-muted">
                                <i class="fas fa-clock fa-2x d-block mb-2"></i>
                                <p>Les leçons seront disponibles prochainement.</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-list fa-3x d-block mb-2"></i>
                            <p>Aucune leçon pour ce cours.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ═══ SIDEBAR ═══ --}}
        <div class="col-lg-4">

            {{-- Statut accès --}}
            @if($isEnrolled)
            <div class="card border-0 shadow-sm mb-4" style="border-top:3px solid #0F6E56!important;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-check-circle fa-3x text-success d-block mb-2"></i>
                    <h6 class="fw-bold text-success mb-1">Vous avez accès à ce cours</h6>
                    <p class="text-muted small mb-3">Toutes les leçons, vidéos et PDFs sont disponibles.</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-home me-2"></i>Mon tableau de bord
                    </a>
                </div>
            </div>
            @elseif($hasPendingPayment)
            <div class="card border-0 shadow-sm mb-4" style="border-top:3px solid #F57F17!important;">
                <div class="card-body text-center p-4">
                    <i class="fas fa-clock fa-3x text-warning d-block mb-2"></i>
                    <h6 class="fw-bold text-warning mb-1">Paiement en attente</h6>
                    <p class="text-muted small">L'administrateur vérifie votre reçu. Accès activé sous peu.</p>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm mb-4" style="border-top:3px solid #0A2D6E!important;">
                <div class="card-body text-center p-4">
                    @if($course->regular_price > 0)
                        <div class="h2 fw-bold mb-1" style="color:#0A2D6E;">
                            {{ number_format($course->offer_price ?? $course->regular_price,0,',',' ') }} FCFA
                        </div>
                        @if($course->offer_price && $course->offer_price < $course->regular_price)
                            <div class="text-muted text-decoration-line-through mb-2 small">
                                {{ number_format($course->regular_price,0) }} FCFA
                            </div>
                        @endif
                        @auth
                            <a href="{{ route('payment.show',$course->id) }}" class="btn btn-primary btn-lg w-100 mb-2"
                               style="background:#0A2D6E;border-color:#0A2D6E;">
                                <i class="fas fa-lock-open me-2"></i>Payer et accéder
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                            </a>
                        @endauth
                    @else
                        <span class="badge bg-success fs-6 mb-3">Gratuit</span>
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-success btn-lg w-100 mb-2">
                                <i class="fas fa-graduation-cap me-2"></i>S'inscrire gratuitement
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-success btn-lg w-100 mb-2">
                                <i class="fas fa-user-plus me-2"></i>S'inscrire
                            </a>
                        @endauth
                    @endif
                    <small class="text-muted"><i class="fas fa-shield-alt me-1 text-success"></i>Paiement via Mobile Money</small>
                </div>
            </div>
            @endif

            {{-- Ce que contient le cours --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold" style="color:#0A2D6E;">
                        <i class="fas fa-list me-2"></i>Ce cours contient
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @php
                            $publishedLessons = $course->lessons->where('status',1);
                            $hasYT    = $publishedLessons->whereNotNull('youtube_link')->count();
                            $hasVid   = $publishedLessons->whereNotNull('video_file')->count();
                            $hasPdf   = $publishedLessons->whereNotNull('pdf_file')->count();
                            $hasLinks = $publishedLessons->whereNotNull('external_link')->count();
                        @endphp
                        <li class="py-2 border-bottom d-flex align-items-center gap-2">
                            <i class="fas fa-list text-primary" style="width:16px;"></i>
                            <span>{{ $publishedLessons->count() }} leçon(s)</span>
                        </li>
                        @if($course->details?->duration)
                        <li class="py-2 border-bottom d-flex align-items-center gap-2">
                            <i class="fas fa-clock text-warning" style="width:16px;"></i>
                            <span>{{ $course->details->duration }} de contenu</span>
                        </li>
                        @endif
                        @if($hasYT + $hasVid > 0)
                        <li class="py-2 border-bottom d-flex align-items-center gap-2">
                            <i class="fas fa-video text-danger" style="width:16px;"></i>
                            <span>{{ $hasYT + $hasVid }} vidéo(s)</span>
                        </li>
                        @endif
                        @if($hasPdf > 0)
                        <li class="py-2 border-bottom d-flex align-items-center gap-2">
                            <i class="fas fa-file-pdf text-danger" style="width:16px;"></i>
                            <span>{{ $hasPdf }} document(s) PDF</span>
                        </li>
                        @endif
                        @if($liveSessions->count() > 0)
                        <li class="py-2 d-flex align-items-center gap-2">
                            <i class="fas fa-broadcast-tower text-danger" style="width:16px;"></i>
                            <span>{{ $liveSessions->count() }} session(s) live</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Professeur --}}
            @if($course->user)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold" style="color:#0A2D6E;">
                        <i class="fas fa-chalkboard-teacher me-2"></i>Votre professeur
                    </h6>
                </div>
                <div class="card-body text-center p-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width:64px;height:64px;background:linear-gradient(135deg,#0A2D6E,#1A5FB4);color:white;font-size:1.4rem;font-weight:700;">
                        {{ strtoupper(substr($course->user->firstname,0,1)) }}{{ strtoupper(substr($course->user->lastname,0,1)) }}
                    </div>
                    <h6 class="fw-bold mb-0">{{ $course->user->firstname }} {{ $course->user->lastname }}</h6>
                    <div class="text-muted small mt-1">Professeur</div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@include('partials.front.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function switchTab(tab, btn) {
        document.querySelectorAll('[id^="tab-"]').forEach(t => t.classList.add('d-none'));
        document.getElementById('tab-'+tab).classList.remove('d-none');
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function toggleLesson(id) {
        const content = document.getElementById('lesson-'+id);
        const icon    = document.getElementById('icon-'+id);
        const isHidden = content.classList.contains('d-none');
        // Ferme toutes les leçons ouvertes
        document.querySelectorAll('[id^="lesson-"]').forEach(el => {
            if (!el.id.includes('icon')) {
                el.classList.add('d-none');
            }
        });
        document.querySelectorAll('[id^="icon-"]').forEach(el => {
            el.classList.remove('fa-chevron-up');
            el.classList.add('fa-chevron-down');
        });
        // Ouvre ou ferme celle cliquée
        if (isHidden) {
            content.classList.remove('d-none');
            icon.classList.replace('fa-chevron-down','fa-chevron-up');
            content.scrollIntoView({behavior:'smooth', block:'nearest'});
        }
    }
</script>
</body>
</html>
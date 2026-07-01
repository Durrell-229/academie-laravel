<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A propos - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family:'Segoe UI',sans-serif; }

        .about-hero {
            min-height: 400px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .about-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('/img/OIP (2).webp');
            background-size: cover;
            background-position: center top;
            filter: brightness(0.4);
            z-index: 0;
        }
        .about-hero .hero-content {
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .stat-card { border-top: 4px solid; border-radius: 12px; transition: transform 0.3s, box-shadow 0.3s; }
        .stat-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important; }

        .value-card { border-left: 5px solid; border-radius: 12px; transition: transform 0.3s; }
        .value-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.08) !important; }

        .niveau-card { border-radius: 16px; overflow: hidden; transition: transform 0.3s; }
        .niveau-card:hover { transform: translateY(-6px); box-shadow: 0 12px 30px rgba(0,0,0,0.1) !important; }

        .team-card { border-radius: 16px; overflow: hidden; transition: transform 0.3s; }
        .team-card:hover { transform: translateY(-6px); }

        .section-badge {
            display: inline-block;
            background: rgba(10,45,110,0.1);
            color: #0A2D6E;
            padding: 6px 20px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        .counter { font-size: 2.5rem; font-weight: 800; }
        .feature-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; }
        .feature-icon {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; background: rgba(10,45,110,0.1);
        }
        .cta-section { background: linear-gradient(135deg, #0A2D6E, #1A5FB4); border-radius: 20px; }

        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-left { opacity: 0; transform: translateX(-40px); transition: all 0.6s ease; }
        .reveal-left.visible { opacity: 1; transform: translateX(0); }
        .reveal-right { opacity: 0; transform: translateX(40px); transition: all 0.6s ease; }
        .reveal-right.visible { opacity: 1; transform: translateX(0); }
    </style>
</head>
<body>

@include('partials.front.navbar')

{{-- HERO avec image OIP (2) - lycéens africains --}}
<div class="about-hero text-white">
    <div class="container text-center hero-content py-5">
        <span class="badge px-4 py-2 mb-3" style="background:rgba(255,255,255,0.2);font-size:0.9rem;">
            <i class="fas fa-university me-2"></i>Notre academie
        </span>
        <h1 class="display-4 fw-bold text-white mb-3">A propos de nous</h1>
        <p class="fs-5 mb-4" style="color:rgba(255,255,255,0.85);">
            La premiere academie numerique dediee aux eleves beninois
        </p>
        <nav aria-label="breadcrumb" class="d-flex justify-content-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('site.home') }}" style="color:rgba(255,255,255,0.6);">Accueil</a>
                </li>
                <li class="breadcrumb-item active text-white">A propos</li>
            </ol>
        </nav>
    </div>
</div>

{{-- STATS --}}
@php
    $totalCours  = App\Models\Course::where('status',1)->count();
    $totalEleves = App\Models\User::whereHas('role', fn($q) => $q->where('slug','apprenant'))->count();
    $totalProfs  = App\Models\User::whereHas('role', fn($q) => $q->where('slug','professeur'))->where('status',1)->count();
@endphp
<div class="container py-5">
    <div class="row g-4 justify-content-center">
        <div class="col-6 col-md-3 reveal">
            <div class="card border-0 shadow-sm text-center py-4 stat-card h-100" style="border-top-color:#0A2D6E!important;">
                <i class="fas fa-book-open fa-2x mb-3" style="color:#0A2D6E;"></i>
                <div class="counter" style="color:#0A2D6E;" data-target="{{ $totalCours }}" data-suffix="+">0+</div>
                <div class="text-muted small mt-1">Cours disponibles</div>
            </div>
        </div>
        <div class="col-6 col-md-3 reveal" style="transition-delay:0.1s;">
            <div class="card border-0 shadow-sm text-center py-4 stat-card h-100" style="border-top-color:#0F6E56!important;">
                <i class="fas fa-users fa-2x mb-3" style="color:#0F6E56;"></i>
                <div class="counter" style="color:#0F6E56;" data-target="{{ $totalEleves }}" data-suffix="+">0+</div>
                <div class="text-muted small mt-1">Apprenants inscrits</div>
            </div>
        </div>
        <div class="col-6 col-md-3 reveal" style="transition-delay:0.2s;">
            <div class="card border-0 shadow-sm text-center py-4 stat-card h-100" style="border-top-color:#F57F17!important;">
                <i class="fas fa-chalkboard-teacher fa-2x mb-3" style="color:#F57F17;"></i>
                <div class="counter" style="color:#F57F17;" data-target="{{ $totalProfs }}" data-suffix="+">0+</div>
                <div class="text-muted small mt-1">Professeurs experts</div>
            </div>
        </div>
        <div class="col-6 col-md-3 reveal" style="transition-delay:0.3s;">
            <div class="card border-0 shadow-sm text-center py-4 stat-card h-100" style="border-top-color:#C62828!important;">
                <i class="fas fa-mobile-alt fa-2x mb-3" style="color:#C62828;"></i>
                <div class="counter" style="color:#C62828;" data-target="100" data-suffix="%">0%</div>
                <div class="text-muted small mt-1">% En ligne</div>
            </div>
        </div>
    </div>
</div>

{{-- QUI SOMMES-NOUS avec image telecharger (2) - eleve qui ecrit --}}
<div class="py-5" style="background:#f8f9fa;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 reveal-left">
                <div class="position-relative">
                    <img src="/img/t%C3%A9l%C3%A9charger%20(2).webp"
                         class="img-fluid rounded-3 shadow-lg w-100"
                         style="height:420px;object-fit:cover;" alt="Eleve qui apprend"
                         onerror="this.src='/img/about.jpg'" />
                    <div class="position-absolute bottom-0 start-0 m-3 p-3 rounded-3 shadow"
                         style="background:rgba(10,45,110,0.92);">
                        <div class="text-white fw-bold small">
                            <i class="fas fa-graduation-cap me-2 text-warning"></i>
                            Formation de qualite au Benin
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 reveal-right">
                <span class="section-badge">Qui sommes-nous ?</span>
                <h2 class="fw-bold mb-4" style="color:#0A2D6E;font-size:2rem;">
                    La premiere academie numerique du Benin
                </h2>
                <p class="text-muted mb-4" style="line-height:1.9;">
                    Academie Numerique est une plateforme d'apprentissage en ligne fondee au Benin,
                    dediee a la formation des apprenants de tous niveaux, de la maternelle au lycee.
                    Nous proposons des cours de qualite adaptes au programme officiel beninois.
                </p>
                <p class="text-muted mb-4" style="line-height:1.9;">
                    Nos cours sont dispenses par des professeurs certifies et incluent des videos,
                    des documents PDF et des sessions live interactives accessibles depuis
                    n'importe quel telephone ou ordinateur.
                </p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check text-success"></i></div>
                            <span class="small fw-semibold">Professeurs certifies</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check text-success"></i></div>
                            <span class="small fw-semibold">Cours en video et PDF</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check text-success"></i></div>
                            <span class="small fw-semibold">Sessions live interactives</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check text-success"></i></div>
                            <span class="small fw-semibold">Paiement Mobile Money</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check text-success"></i></div>
                            <span class="small fw-semibold">Programme beninois officiel</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="feature-item">
                            <div class="feature-icon"><i class="fas fa-check text-success"></i></div>
                            <span class="small fw-semibold">Accessible 24h/24</span>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('register') }}" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-user-plus me-2"></i>S'inscrire gratuitement
                    </a>
                    <a href="{{ route('site.contact') }}" class="btn btn-outline-primary px-4 py-2">
                        <i class="fas fa-envelope me-2"></i>Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MISSION VISION VALEURS --}}
<div class="py-5">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="section-badge">Ce qui nous guide</span>
            <h2 class="fw-bold mt-2" style="color:#0A2D6E;">Notre Mission, Vision et Valeurs</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4 reveal" style="transition-delay:0.1s;">
                <div class="card border-0 shadow-sm h-100 p-4 value-card" style="border-left-color:#0A2D6E!important;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-4"
                         style="width:64px;height:64px;background:linear-gradient(135deg,#0A2D6E,#1A5FB4);">
                        <i class="fas fa-bullseye fa-lg text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color:#0A2D6E;">Notre Mission</h4>
                    <p class="text-muted" style="line-height:1.9;">
                        Democratiser l'acces a une education numerique de qualite pour tous les Beninois,
                        en proposant des formations pratiques et adaptees aux realites locales,
                        pour contribuer au developpement du capital humain en Afrique.
                    </p>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:0.2s;">
                <div class="card border-0 shadow-sm h-100 p-4 value-card" style="border-left-color:#1A5FB4!important;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-4"
                         style="width:64px;height:64px;background:linear-gradient(135deg,#1A5FB4,#2563EB);">
                        <i class="fas fa-eye fa-lg text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color:#1A5FB4;">Notre Vision</h4>
                    <p class="text-muted" style="line-height:1.9;">
                        Devenir la reference en matiere d'education numerique en Afrique de l'Ouest,
                        en formant une generation de jeunes competents et innovants,
                        capables de relever les defis technologiques du 21e siecle.
                    </p>
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:0.3s;">
                <div class="card border-0 shadow-sm h-100 p-4 value-card" style="border-left-color:#0F6E56!important;">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-4"
                         style="width:64px;height:64px;background:linear-gradient(135deg,#0F6E56,#1A9B76);">
                        <i class="fas fa-heart fa-lg text-white"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color:#0F6E56;">Nos Valeurs</h4>
                    <ul class="list-unstyled text-muted mb-0" style="line-height:2.2;">
                        <li class="feature-item">
                            <i class="fas fa-star text-warning flex-shrink-0"></i>
                            <span><strong style="color:#0F6E56;">Excellence</strong> - Formations de haute qualite</span>
                        </li>
                        <li class="feature-item">
                            <i class="fas fa-globe text-warning flex-shrink-0"></i>
                            <span><strong style="color:#0F6E56;">Accessibilite</strong> - Pour tous au Benin</span>
                        </li>
                        <li class="feature-item">
                            <i class="fas fa-lightbulb text-warning flex-shrink-0"></i>
                            <span><strong style="color:#0F6E56;">Innovation</strong> - Methodes modernes</span>
                        </li>
                        <li class="feature-item">
                            <i class="fas fa-handshake text-warning flex-shrink-0"></i>
                            <span><strong style="color:#0F6E56;">Integrite</strong> - Transparence et confiance</span>
                        </li>
                        <li class="feature-item">
                            <i class="fas fa-users text-warning flex-shrink-0"></i>
                            <span><strong style="color:#0F6E56;">Communaute</strong> - Apprendre ensemble</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- NIVEAUX SCOLAIRES --}}
<div class="py-5" style="background:#f8f9fa;">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="section-badge">Nos niveaux</span>
            <h2 class="fw-bold mt-2" style="color:#0A2D6E;">Pour tous les niveaux scolaires</h2>
        </div>
        <div class="row g-4">
            @php
                $niveaux = [
                    [
                        'icon'  => 'fas fa-child',
                        'color' => '#F57F17',
                        'bg'    => 'rgba(245,127,23,0.1)',
                        'titre' => 'Maternelle',
                        'desc'  => 'Eveil, comptage, lecture, dessin, chant - des bases solides pour les tout-petits.',
                        'img'   => '/img/t%C3%A9l%C3%A9charger.webp',
                        'fallback' => '/img/ecolier.png',
                    ],
                    [
                        'icon'  => 'fas fa-pencil-alt',
                        'color' => '#0F6E56',
                        'bg'    => 'rgba(15,110,86,0.1)',
                        'titre' => 'Primaire',
                        'desc'  => 'Francais, Maths, Sciences, Histoire-Geo, Anglais - le programme officiel du primaire.',
                        'img'   => '/img/OIP%20(1).webp',
                        'fallback' => '/img/about.jpg',
                    ],
                    [
                        'icon'  => 'fas fa-book',
                        'color' => '#1A5FB4',
                        'bg'    => 'rgba(26,95,180,0.1)',
                        'titre' => 'College (CEG)',
                        'desc'  => 'SVT, Physique-Chimie, Maths, Francais - preparation au BEPC et aux examens.',
                        'img'   => '/img/OIP%20(4).webp',
                        'fallback' => '/img/cat-1.jpg',
                    ],
                    [
                        'icon'  => 'fas fa-graduation-cap',
                        'color' => '#0A2D6E',
                        'bg'    => 'rgba(10,45,110,0.1)',
                        'titre' => 'Lycee',
                        'desc'  => 'Philosophie, Economie, Sciences - preparation au BAC toutes series confondues.',
                        'img'   => '/img/OIP%20(3).webp',
                        'fallback' => '/img/cat-2.jpg',
                    ],
                ];
            @endphp
            @foreach($niveaux as $i => $n)
            <div class="col-md-6 col-lg-3 reveal" style="transition-delay:{{ $i*0.1 }}s;">
                <div class="card border-0 shadow-sm h-100 niveau-card">
                    <div style="height:180px;overflow:hidden;position:relative;">
                        <img src="{{ $n['img'] }}"
                             class="w-100 h-100"
                             style="object-fit:cover;transition:transform 0.4s;"
                             onmouseover="this.style.transform='scale(1.08)'"
                             onmouseout="this.style.transform='scale(1)'"
                             onerror="this.src='{{ $n['fallback'] }}'" />
                        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.3),transparent);"></div>
                    </div>
                    <div class="p-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                             style="width:48px;height:48px;background:{{ $n['bg'] }};">
                            <i class="{{ $n['icon'] }}" style="color:{{ $n['color'] }};"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="color:{{ $n['color'] }};">{{ $n['titre'] }}</h5>
                        <p class="text-muted small mb-3" style="line-height:1.7;">{{ $n['desc'] }}</p>
                        <a href="{{ route('site.courses') }}" class="btn btn-sm btn-outline-primary w-100">
                            Voir les cours
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- GALERIE PHOTOS --}}
<div class="py-5">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="section-badge">Notre communaute</span>
            <h2 class="fw-bold mt-2" style="color:#0A2D6E;">Nos apprenants en action</h2>
        </div>
        <div class="row g-3">
            <div class="col-md-4 reveal" style="transition-delay:0.1s;">
                <div class="overflow-hidden rounded-3 shadow-sm" style="height:250px;">
                    <img src="/img/OIP.webp" class="w-100 h-100"
                         style="object-fit:cover;transition:transform 0.4s;"
                         onmouseover="this.style.transform='scale(1.06)'"
                         onmouseout="this.style.transform='scale(1)'"
                         onerror="this.src='/img/team-1.jpg'" />
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:0.2s;">
                <div class="overflow-hidden rounded-3 shadow-sm" style="height:250px;">
                    <img src="/img/OIP%20(2).webp" class="w-100 h-100"
                         style="object-fit:cover;transition:transform 0.4s;"
                         onmouseover="this.style.transform='scale(1.06)'"
                         onmouseout="this.style.transform='scale(1)'"
                         onerror="this.src='/img/team-2.jpg'" />
                </div>
            </div>
            <div class="col-md-4 reveal" style="transition-delay:0.3s;">
                <div class="overflow-hidden rounded-3 shadow-sm" style="height:250px;">
                    <img src="/img/t%C3%A9l%C3%A9charger%20(1).webp" class="w-100 h-100"
                         style="object-fit:cover;transition:transform 0.4s;"
                         onmouseover="this.style.transform='scale(1.06)'"
                         onmouseout="this.style.transform='scale(1)'"
                         onerror="this.src='/img/team-3.jpg'" />
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PROFESSEURS --}}
@php
    $profs = App\Models\User::whereHas('role', fn($q) => $q->where('slug','professeur'))
        ->where('status',1)->take(4)->get();
@endphp
@if($profs->count() > 0)
<div class="py-5" style="background:#f8f9fa;">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="section-badge">Notre equipe</span>
            <h2 class="fw-bold mt-2" style="color:#0A2D6E;">Nos professeurs experts</h2>
        </div>
        <div class="row g-4 justify-content-center">
            @foreach($profs as $i => $prof)
            <div class="col-6 col-md-3 reveal" style="transition-delay:{{ $i*0.1 }}s;">
                <div class="card border-0 shadow-sm h-100 team-card text-center">
                    <div style="height:180px;background:linear-gradient(135deg,#0A2D6E,#1A5FB4);
                                display:flex;align-items:center;justify-content:center;">
                        @if($prof->profile?->avatar)
                            <img src="{{ asset('storage/'.$prof->profile->avatar) }}"
                                 class="w-100 h-100" style="object-fit:cover;" />
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:80px;height:80px;background:rgba(255,255,255,0.2);">
                                <span class="text-white fw-bold" style="font-size:1.8rem;">
                                    {{ strtoupper(substr($prof->firstname,0,1)) }}{{ strtoupper(substr($prof->lastname,0,1)) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="p-3">
                        <h6 class="fw-bold mb-1">{{ $prof->firstname }} {{ $prof->lastname }}</h6>
                        <div class="text-muted small mb-2">Professeur</div>
                        <span class="badge" style="background:rgba(10,45,110,0.1);color:#0A2D6E;">
                            <i class="fas fa-book me-1"></i>
                            {{ App\Models\Course::where('user_id',$prof->id)->where('status',1)->count() }} cours
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- CTA --}}
<div class="py-5">
    <div class="container">
        <div class="cta-section text-center py-5 px-4 reveal">
            <h2 class="fw-bold text-white mb-3">Pret a commencer votre apprentissage ?</h2>
            <p class="text-white-50 mb-4 fs-5">
                Rejoignez des centaines d'apprenants qui font confiance a Academie Numerique.
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-5 fw-bold">
                    <i class="fas fa-user-plus me-2"></i>S'inscrire gratuitement
                </a>
                <a href="{{ route('site.contact') }}" class="btn btn-outline-light btn-lg px-5">
                    <i class="fas fa-envelope me-2"></i>Nous contacter
                </a>
            </div>
        </div>
    </div>
</div>

@include('partials.front.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal,.reveal-left,.reveal-right').forEach(el => observer.observe(el));

    document.querySelectorAll('.counter[data-target]').forEach(counter => {
        const target = parseInt(counter.dataset.target);
        const suffix = counter.dataset.suffix || '+';
        if (!target) return;
        let current = 0;
        const step = Math.max(1, Math.ceil(target / 40));
        const timer = setInterval(() => {
            current = Math.min(current + step, target);
            counter.textContent = current + suffix;
            if (current >= target) clearInterval(timer);
        }, 40);
    });
</script>
</body>
</html>
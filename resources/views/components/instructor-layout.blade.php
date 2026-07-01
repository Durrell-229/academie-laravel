<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Espace Professeur' }} — {{ config('app.name') }}</title>
    <link href="{{ asset('img/icons/icon-48x48.png') }}" rel="shortcut icon">
    <link href="{{ asset('plugins/font-awesome-6.4.0/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* ════════════════════════════════════════
           VARIABLES BLEU PROFESSIONNEL
        ════════════════════════════════════════ */
        :root {
            --prof-bg:       #0A2D6E;
            --prof-bg-dark:  #061B45;
            --prof-accent:   #60B4FF;
            --prof-hover:    rgba(96,180,255,0.1);
            --prof-border:   rgba(255,255,255,0.08);
        }

        /* ════════════════════════════════════════
           ANIMATIONS
        ════════════════════════════════════════ */
        @keyframes fadeInLeft {
            from { opacity:0; transform:translateX(-24px); }
            to   { opacity:1; transform:translateX(0); }
        }
        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes pulse {
            0%,100% { opacity:1; transform:scale(1); }
            50%     { opacity:0.5; transform:scale(0.92); }
        }
        @keyframes slideRight {
            from { transform:translateX(-6px); opacity:0; }
            to   { transform:translateX(0);    opacity:1; }
        }
        @keyframes shimmer {
            0%   { background-position:-400px 0; }
            100% { background-position:400px 0; }
        }
        @keyframes bounceIn {
            0%   { transform:scale(0.7); opacity:0; }
            60%  { transform:scale(1.1); }
            100% { transform:scale(1);   opacity:1; }
        }

        /* ════════════════════════════════════════
           SIDEBAR
        ════════════════════════════════════════ */
        .sidebar {
            background: linear-gradient(180deg, var(--prof-bg) 0%, var(--prof-bg-dark) 100%) !important;
            animation: fadeInLeft 0.4s ease !important;
        }

        .sidebar-brand {
            color: white !important;
            border-bottom: 1px solid var(--prof-border) !important;
            transition: all 0.2s !important;
        }
        .sidebar-brand:hover { color: var(--prof-accent) !important; }

        /* Avatar professeur */
        .prof-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 2px solid var(--prof-accent);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 14px;
            flex-shrink: 0;
            animation: bounceIn 0.5s ease 0.3s both;
        }

        /* Info prof dans sidebar */
        .sidebar-prof-info {
            padding: 14px 20px;
            border-bottom: 1px solid var(--prof-border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-prof-name { color: white; font-size: 0.88rem; font-weight: 600; }
        .sidebar-prof-role {
            color: var(--prof-accent); font-size: 0.7rem;
            background: rgba(96,180,255,0.15);
            padding: 2px 8px; border-radius: 10px;
            display: inline-block; margin-top: 2px;
        }

        /* Liens sidebar */
        .sidebar-header {
            color: rgba(255,255,255,0.3) !important;
            font-size: 0.65rem !important;
            letter-spacing: 1.2px !important;
        }

        .sidebar-link {
            color: rgba(255,255,255,0.65) !important;
            border-left: 3px solid transparent !important;
            transition: all 0.2s !important;
            position: relative;
        }
        .sidebar-link:hover {
            color: white !important;
            background: var(--prof-hover) !important;
            border-left-color: var(--prof-accent) !important;
            transform: translateX(2px);
        }
        .sidebar-item.active .sidebar-link {
            color: white !important;
            background: rgba(96,180,255,0.15) !important;
            border-left-color: var(--prof-accent) !important;
        }

        /* Badge sidebar */
        .sidebar-badge {
            margin-left: auto;
            background: rgba(96,180,255,0.2);
            color: var(--prof-accent);
            font-size: 0.65rem;
            padding: 2px 7px;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Bouton live */
        .live-badge {
            background: #ff4444;
            color: white;
            font-size: 0.55rem;
            padding: 2px 5px;
            border-radius: 4px;
            animation: pulse 1.5s infinite;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Bouton déconnexion */
        .sidebar-link.logout-link {
            color: rgba(255,100,100,0.75) !important;
        }
        .sidebar-link.logout-link:hover {
            color: #ff6b6b !important;
            background: rgba(255,100,100,0.1) !important;
            border-left-color: #ff4444 !important;
        }

        /* ════════════════════════════════════════
           NAVBAR (topbar)
        ════════════════════════════════════════ */
        .navbar-bg {
            border-bottom: 2px solid #1A5FB4 !important;
        }

        /* ════════════════════════════════════════
           CONTENU PRINCIPAL — Animations entrée
        ════════════════════════════════════════ */
        .content .container-fluid > * {
            animation: fadeInUp 0.4s ease both;
        }

        /* Cards avec effet hover */
        .card {
            transition: transform 0.2s, box-shadow 0.2s !important;
        }
        .card:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(26,95,180,0.12) !important;
        }

        /* Boutons bleus */
        .btn-primary {
            background: #1A5FB4 !important;
            border-color: #1A5FB4 !important;
            transition: all 0.2s !important;
        }
        .btn-primary:hover {
            background: #0D47A1 !important;
            border-color: #0D47A1 !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 4px 12px rgba(26,95,180,0.3) !important;
        }
        .btn-outline-primary {
            color: #1A5FB4 !important;
            border-color: #1A5FB4 !important;
        }
        .btn-outline-primary:hover {
            background: #1A5FB4 !important;
            color: white !important;
        }

        /* Stats cards */
        .stat-card {
            border-left: 4px solid #1A5FB4 !important;
            animation: fadeInUp 0.4s ease both;
        }
        .stat-card:nth-child(1) { animation-delay: 0.1s; border-left-color: #1A5FB4 !important; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; border-left-color: #0F6E56 !important; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; border-left-color: #F57F17 !important; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; border-left-color: #C62828 !important; }

        /* Tables */
        .table tr { transition: background 0.15s !important; }
        .table tbody tr:hover { background: rgba(26,95,180,0.04) !important; }

        /* Badges */
        .badge.bg-success { background: #1B5E20 !important; }
        .badge.bg-info    { background: #0D47A1 !important; }

        /* Lien examens */
        .exam-link {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            padding: 10px 20px !important;
            color: rgba(255,220,100,0.85) !important;
            border-left: 3px solid transparent !important;
            transition: all 0.2s !important;
            text-decoration: none !important;
            font-size: 0.875rem !important;
        }
        .exam-link:hover {
            color: #FFD700 !important;
            background: rgba(255,215,0,0.1) !important;
            border-left-color: #FFD700 !important;
            transform: translateX(2px);
        }

        /* Animation des chiffres stats */
        .stat-number {
            animation: bounceIn 0.6s ease both;
        }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ══ SIDEBAR PROFESSEUR ══ --}}
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">

            {{-- Logo --}}
            <a href="{{ route('instructor.dashboard') }}" class="sidebar-brand d-flex align-items-center gap-2 px-4 py-3">
                <i class="fas fa-chalkboard-teacher" style="font-size:1.2rem; color: var(--prof-accent);"></i>
                <span style="font-weight:700; letter-spacing:0.5px;">{{ config('app.name') }}</span>
            </a>

            {{-- Info professeur --}}
            <div class="sidebar-prof-info">
                <div class="prof-avatar">
                    {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}
                </div>
                <div>
                    <div class="sidebar-prof-name">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                    <div class="sidebar-prof-role">Professeur</div>
                </div>
            </div>

            <ul class="sidebar-nav">

                {{-- Dashboard --}}
                <li class="sidebar-item {{ request()->routeIs('instructor.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('instructor.dashboard') }}" class="sidebar-link">
                        <i class="fas fa-gauge-high align-middle me-2"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                {{-- ── Mes cours ── --}}
                <li class="sidebar-header px-4 py-2">MES COURS</li>

                <li class="sidebar-item {{ request()->routeIs('instructor.courses.index') ? 'active' : '' }}">
                    <a href="{{ route('instructor.courses.index') }}" class="sidebar-link">
                        <i class="fas fa-book align-middle me-2"></i>
                        <span>Mes cours</span>
                        @php $nbCours = \App\Models\Course::where('user_id', Auth::id())->count(); @endphp
                        @if($nbCours > 0)
                            <span class="sidebar-badge">{{ $nbCours }}</span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('instructor.courses.create') ? 'active' : '' }}">
                    <a href="{{ route('instructor.courses.create') }}" class="sidebar-link">
                        <i class="fas fa-plus-circle align-middle me-2"></i>
                        <span>Créer un cours</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('instructor.lessons.*') ? 'active' : '' }}">
                    <a href="{{ route('instructor.lessons.index') }}" class="sidebar-link">
                        <i class="fas fa-list align-middle me-2"></i>
                        <span>Mes leçons</span>
                        @php $nbLecons = \App\Models\Lesson::whereIn('course_id', \App\Models\Course::where('user_id', Auth::id())->pluck('id'))->count(); @endphp
                        @if($nbLecons > 0)
                            <span class="sidebar-badge">{{ $nbLecons }}</span>
                        @endif
                    </a>
                </li>

                {{-- ── Apprenants ── --}}
                <li class="sidebar-header px-4 py-2">MES APPRENANTS</li>

                <li class="sidebar-item {{ request()->routeIs('instructor.apprenants') ? 'active' : '' }}">
                    <a href="{{ route('instructor.apprenants') }}" class="sidebar-link">
                        <i class="fas fa-users align-middle me-2"></i>
                        <span>Apprenants inscrits</span>
                        @php $nbApprenants = \App\Models\Enrollment::whereIn('course_id', \App\Models\Course::where('user_id', Auth::id())->pluck('id'))->count(); @endphp
                        @if($nbApprenants > 0)
                            <span class="sidebar-badge" style="background:rgba(245,127,23,0.2);color:#F57F17;">{{ $nbApprenants }}</span>
                        @endif
                    </a>
                </li>

                {{-- ── Examens ── --}}
                <li class="sidebar-header px-4 py-2">ÉVALUATIONS</li>

                <li class="sidebar-item">
                    <a href="https://academie-numerique-n4du.onrender.com/"
                       target="_blank"
                       class="sidebar-link exam-link">
                        <i class="fas fa-file-alt align-middle me-2"></i>
                        <span>Examens & Évaluations</span>
                        <i class="fas fa-external-link-alt ms-auto" style="font-size:0.65rem; opacity:0.6;"></i>
                    </a>
                </li>

                {{-- ── Cours en live ── --}}
                <li class="sidebar-header px-4 py-2">
                    <span>COURS EN LIVE</span>
                    <span class="live-badge ms-2">LIVE</span>
                </li>

                <li class="sidebar-item">
                    <a href="https://wa.me/?text=Rejoignez+mon+cours+en+direct+sur+{{ urlencode(config('app.name')) }}"
                       target="_blank" class="sidebar-link">
                        <i class="fab fa-whatsapp align-middle me-2" style="color:#25D366;"></i>
                        <span>WhatsApp</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="https://zoom.us/start/videomeeting" target="_blank" class="sidebar-link">
                        <i class="fas fa-video align-middle me-2" style="color:#2D8CFF;"></i>
                        <span>Zoom</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="https://meet.google.com/new" target="_blank" class="sidebar-link">
                        <i class="fas fa-desktop align-middle me-2" style="color:#EA4335;"></i>
                        <span>Google Meet</span>
                    </a>
                </li>

                {{-- ── Paramètres ── --}}
                <li class="sidebar-header px-4 py-2">PARAMÈTRES</li>

                <li class="sidebar-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <i class="fas fa-user-edit align-middle me-2"></i>
                        <span>Mon profil</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('site.home') }}" class="sidebar-link">
                        <i class="fas fa-globe align-middle me-2"></i>
                        <span>Voir le site</span>
                    </a>
                </li>

                {{-- Déconnexion --}}
                <li class="sidebar-item mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link logout-link w-100 text-start border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt align-middle me-2"></i>
                            <span>Se déconnecter</span>
                        </button>
                    </form>
                </li>

            </ul>
        </div>
    </nav>

    {{-- ══ CONTENU PRINCIPAL ══ --}}
    <div class="main">
        <x-admin-navbar />
        <main class="content">
            <div class="container-fluid p-0">
                @isset($header){{ $header }}@endisset
                {{ $slot }}
            </div>
        </main>
        <x-admin-footer />
    </div>
</div>

<script src="{{ asset('plugins/jquery/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('plugins/font-awesome-6.4.0/js/all.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
@isset($script){{ $script }}@endisset
</body>
</html>

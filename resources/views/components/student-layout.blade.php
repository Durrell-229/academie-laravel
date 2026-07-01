<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Mon Espace' }} — {{ config('app.name') }}</title>
    <link href="{{ asset('img/icons/icon-48x48.png') }}" rel="shortcut icon">
    <link href="{{ asset('plugins/font-awesome-6.4.0/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --stu-bg:     #3B1F8C;
            --stu-dark:   #220D5E;
            --stu-accent: #A78BFA;
            --stu-hover:  rgba(167,139,250,0.1);
            --stu-border: rgba(255,255,255,0.08);
        }

        @keyframes fadeInLeft { from{opacity:0;transform:translateX(-20px)} to{opacity:1;transform:translateX(0)} }
        @keyframes fadeInUp   { from{opacity:0;transform:translateY(16px)}  to{opacity:1;transform:translateY(0)} }
        @keyframes bounceIn   { 0%{transform:scale(0.7);opacity:0} 60%{transform:scale(1.1)} 100%{transform:scale(1);opacity:1} }
        @keyframes pulse      { 0%,100%{opacity:1} 50%{opacity:0.4} }
        @keyframes shimmer    { 0%{background-position:-400px 0} 100%{background-position:400px 0} }

        /* ── Sidebar ── */
        .sidebar {
            background: linear-gradient(180deg, var(--stu-bg) 0%, var(--stu-dark) 100%) !important;
            animation: fadeInLeft 0.4s ease !important;
        }
        .sidebar-brand { color: white !important; border-bottom: 1px solid var(--stu-border) !important; }
        .sidebar-brand:hover { color: var(--stu-accent) !important; }

        .stu-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 2px solid var(--stu-accent);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 15px;
            flex-shrink: 0;
            animation: bounceIn 0.5s ease 0.3s both;
        }
        .sidebar-prof-info {
            padding: 14px 20px;
            border-bottom: 1px solid var(--stu-border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-prof-name  { color: white; font-size: 0.88rem; font-weight: 600; }
        .sidebar-prof-role  {
            color: var(--stu-accent); font-size: 0.7rem;
            background: rgba(167,139,250,0.15);
            padding: 2px 8px; border-radius: 10px;
            display: inline-block; margin-top: 2px;
        }

        .sidebar-header { color: rgba(255,255,255,0.3) !important; font-size: 0.65rem !important; letter-spacing: 1.2px !important; }

        .sidebar-link {
            color: rgba(255,255,255,0.65) !important;
            border-left: 3px solid transparent !important;
            transition: all 0.2s !important;
        }
        .sidebar-link:hover, .sidebar-item.active .sidebar-link {
            color: white !important;
            background: var(--stu-hover) !important;
            border-left-color: var(--stu-accent) !important;
            transform: translateX(2px);
        }

        .stu-badge {
            margin-left: auto;
            background: rgba(167,139,250,0.2);
            color: var(--stu-accent);
            font-size: 0.65rem; padding: 2px 7px; border-radius: 10px; font-weight: 600;
        }

        .sidebar-link.logout-link { color: rgba(255,100,100,0.75) !important; }
        .sidebar-link.logout-link:hover { color: #ff6b6b !important; background: rgba(255,100,100,0.1) !important; border-left-color: #ff4444 !important; }

        /* ── Contenu ── */
        .card { transition: transform 0.2s, box-shadow 0.2s !important; }
        .card:hover { transform: translateY(-2px) !important; box-shadow: 0 6px 20px rgba(59,31,140,0.12) !important; }

        .btn-primary { background: #3B1F8C !important; border-color: #3B1F8C !important; transition: all 0.2s !important; }
        .btn-primary:hover { background: #220D5E !important; transform: translateY(-1px) !important; box-shadow: 0 4px 12px rgba(59,31,140,0.3) !important; }
        .btn-outline-primary { color: #3B1F8C !important; border-color: #3B1F8C !important; }
        .btn-outline-primary:hover { background: #3B1F8C !important; color: white !important; }

        .stat-card { animation: fadeInUp 0.4s ease both; }
        .stat-card:nth-child(1) { animation-delay:0.1s; border-left: 4px solid #3B1F8C !important; }
        .stat-card:nth-child(2) { animation-delay:0.2s; border-left: 4px solid #F57F17 !important; }
        .stat-card:nth-child(3) { animation-delay:0.3s; border-left: 4px solid #0D4F3C !important; }
        .stat-card:nth-child(4) { animation-delay:0.4s; border-left: 4px solid #1A5FB4 !important; }

        .stat-number { animation: bounceIn 0.6s ease both; }

        /* Progression bar */
        .progress { height: 8px; border-radius: 10px; }
        .progress-bar { background: linear-gradient(90deg, #3B1F8C, #7C3AED) !important; border-radius: 10px; }

        /* Course card */
        .course-card { border-radius: 12px; overflow: hidden; transition: all 0.2s; }
        .course-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(59,31,140,0.15) !important; }
        .course-thumb { height: 140px; background: linear-gradient(135deg, #3B1F8C, #7C3AED); display:flex; align-items:center; justify-content:center; color:white; font-size:2.5rem; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- ══ SIDEBAR APPRENANT ══ --}}
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">

            <a href="{{ route('dashboard') }}" class="sidebar-brand d-flex align-items-center gap-2 px-4 py-3">
                <i class="fas fa-graduation-cap" style="font-size:1.2rem;color:var(--stu-accent);"></i>
                <span style="font-weight:700;letter-spacing:0.5px;">{{ config('app.name') }}</span>
            </a>

            {{-- Info apprenant --}}
            <div class="sidebar-prof-info">
                <div class="stu-avatar">
                    {{ strtoupper(substr(Auth::user()->firstname,0,1)) }}{{ strtoupper(substr(Auth::user()->lastname,0,1)) }}
                </div>
                <div>
                    <div class="sidebar-prof-name">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                    <div class="sidebar-prof-role">
                        {{ Auth::user()->profile?->niveau_scolaire
                            ? ucfirst(str_replace('_',' ', Auth::user()->profile->niveau_scolaire))
                            : 'Apprenant' }}
                    </div>
                </div>
            </div>

            <ul class="sidebar-nav">

                <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="sidebar-link">
                        <i class="fas fa-gauge-high align-middle me-2"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                {{-- ── Mes apprentissages ── --}}
                <li class="sidebar-header px-4 py-2">MES APPRENTISSAGES</li>

                <li class="sidebar-item {{ request()->routeIs('student.mes-cours') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}#mes-cours" class="sidebar-link">
                        <i class="fas fa-book-open align-middle me-2"></i>
                        <span>Mes cours</span>
                        @if(isset($totalCours) && $totalCours > 0)
                            <span class="stu-badge">{{ $totalCours }}</span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('dashboard') }}#progression" class="sidebar-link">
                        <i class="fas fa-chart-line align-middle me-2"></i>
                        <span>Ma progression</span>
                        @if(isset($progression) && $progression > 0)
                            <span class="stu-badge">{{ $progression }}%</span>
                        @endif
                    </a>
                </li>

                {{-- ── Catalogue ── --}}
                <li class="sidebar-header px-4 py-2">CATALOGUE</li>

                <li class="sidebar-item">
                    <a href="{{ route('site.courses') }}" class="sidebar-link">
                        <i class="fas fa-search align-middle me-2"></i>
                        <span>Découvrir les cours</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="{{ route('dashboard') }}#catalogue" class="sidebar-link">
                        <i class="fas fa-star align-middle me-2"></i>
                        <span>Cours recommandés</span>
                    </a>
                </li>

                {{-- ── Examens ── --}}
                <li class="sidebar-header px-4 py-2">ÉVALUATIONS</li>

                <li class="sidebar-item">
                    <a href="https://academie-numerique-n4du.onrender.com/" target="_blank" class="sidebar-link">
                        <i class="fas fa-file-alt align-middle me-2"></i>
                        <span>Mes examens</span>
                        <i class="fas fa-external-link-alt ms-auto" style="font-size:0.65rem;opacity:0.6;"></i>
                    </a>
                </li>

                {{-- ── Paramètres ── --}}
                <li class="sidebar-header px-4 py-2">MON COMPTE</li>

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
<script src="{{ asset('js/app.js') }}"></script>
@isset($script){{ $script }}@endisset
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Espace Conseiller' }} — {{ config('app.name') }}</title>
    <link href="{{ asset('img/icons/icon-48x48.png') }}" rel="shortcut icon">
    <link href="{{ asset('plugins/font-awesome-6.4.0/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root {
            --cons-bg:     #0D4F3C;
            --cons-dark:   #072C21;
            --cons-accent: #4ECBA0;
            --cons-hover:  rgba(78,203,160,0.1);
            --cons-border: rgba(255,255,255,0.08);
        }

        @keyframes fadeInLeft { from{opacity:0;transform:translateX(-20px)} to{opacity:1;transform:translateX(0)} }
        @keyframes fadeInUp   { from{opacity:0;transform:translateY(16px)}  to{opacity:1;transform:translateY(0)} }
        @keyframes bounceIn   { 0%{transform:scale(0.7);opacity:0} 60%{transform:scale(1.1)} 100%{transform:scale(1);opacity:1} }
        @keyframes pulse      { 0%,100%{opacity:1} 50%{opacity:0.4} }

        .sidebar {
            background: linear-gradient(180deg, var(--cons-bg) 0%, var(--cons-dark) 100%) !important;
            animation: fadeInLeft 0.4s ease !important;
        }
        .sidebar-brand { color: white !important; border-bottom: 1px solid var(--cons-border) !important; }
        .sidebar-brand:hover { color: var(--cons-accent) !important; }

        .cons-avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 2px solid var(--cons-accent);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 14px;
            animation: bounceIn 0.5s ease 0.3s both;
        }
        .sidebar-prof-info {
            padding: 14px 20px;
            border-bottom: 1px solid var(--cons-border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-prof-name { color: white; font-size: 0.88rem; font-weight: 600; }
        .sidebar-prof-role {
            color: var(--cons-accent); font-size: 0.7rem;
            background: rgba(78,203,160,0.15);
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
            background: var(--cons-hover) !important;
            border-left-color: var(--cons-accent) !important;
            transform: translateX(2px);
        }

        .cons-badge {
            margin-left: auto;
            background: rgba(78,203,160,0.2);
            color: var(--cons-accent);
            font-size: 0.65rem; padding: 2px 7px; border-radius: 10px; font-weight: 600;
        }

        .sidebar-link.logout-link { color: rgba(255,100,100,0.75) !important; }
        .sidebar-link.logout-link:hover { color: #ff6b6b !important; background: rgba(255,100,100,0.1) !important; border-left-color: #ff4444 !important; }

        /* Cards animées */
        .card { transition: transform 0.2s, box-shadow 0.2s !important; }
        .card:hover { transform: translateY(-2px) !important; box-shadow: 0 6px 20px rgba(13,79,60,0.12) !important; }

        /* Couleurs teal */
        .btn-primary { background: #0D4F3C !important; border-color: #0D4F3C !important; }
        .btn-primary:hover { background: #072C21 !important; transform: translateY(-1px) !important; }
        .btn-outline-primary { color: #0D4F3C !important; border-color: #0D4F3C !important; }
        .btn-outline-primary:hover { background: #0D4F3C !important; color: white !important; }

        .stat-card { border-left: 4px solid #0D4F3C !important; animation: fadeInUp 0.4s ease both; }
        .stat-card:nth-child(1) { animation-delay:0.1s; border-left-color:#0D4F3C !important; }
        .stat-card:nth-child(2) { animation-delay:0.2s; border-left-color:#1A5FB4 !important; }
        .stat-card:nth-child(3) { animation-delay:0.3s; border-left-color:#F57F17 !important; }
        .stat-card:nth-child(4) { animation-delay:0.4s; border-left-color:#C62828 !important; }

        .table tr { transition: background 0.15s !important; }
        .table tbody tr:hover { background: rgba(13,79,60,0.04) !important; }

        .stat-number { animation: bounceIn 0.6s ease both; }
    </style>
</head>
<body>
<div class="wrapper">

    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">

            <a href="{{ route('conseiller.dashboard') }}" class="sidebar-brand d-flex align-items-center gap-2 px-4 py-3">
                <i class="fas fa-compass" style="font-size:1.2rem; color:var(--cons-accent);"></i>
                <span style="font-weight:700; letter-spacing:0.5px;">{{ config('app.name') }}</span>
            </a>

            <div class="sidebar-prof-info">
                <div class="cons-avatar">
                    {{ strtoupper(substr(Auth::user()->firstname, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname, 0, 1)) }}
                </div>
                <div>
                    <div class="sidebar-prof-name">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                    <div class="sidebar-prof-role">Conseiller Pédagogique</div>
                </div>
            </div>

            <ul class="sidebar-nav">

                <li class="sidebar-item {{ request()->routeIs('conseiller.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('conseiller.dashboard') }}" class="sidebar-link">
                        <i class="fas fa-gauge-high align-middle me-2"></i>
                        <span>Tableau de bord</span>
                    </a>
                </li>

                <li class="sidebar-header px-4 py-2">APPRENANTS</li>

                <li class="sidebar-item {{ request()->routeIs('conseiller.apprenants') ? 'active' : '' }}">
                    <a href="{{ route('conseiller.apprenants') }}" class="sidebar-link">
                        <i class="fas fa-users align-middle me-2"></i>
                        <span>Tous les apprenants</span>
                        @php $nbApp = \App\Models\User::whereHas('role', fn($q) => $q->where('slug','apprenant'))->count(); @endphp
                        @if($nbApp > 0)
                            <span class="cons-badge">{{ $nbApp }}</span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-item {{ request()->routeIs('conseiller.progression') ? 'active' : '' }}">
                    <a href="{{ route('conseiller.progression') }}" class="sidebar-link">
                        <i class="fas fa-chart-line align-middle me-2"></i>
                        <span>Progression</span>
                    </a>
                </li>

                <li class="sidebar-header px-4 py-2">COURS</li>

                <li class="sidebar-item {{ request()->routeIs('conseiller.cours') ? 'active' : '' }}">
                    <a href="{{ route('conseiller.cours') }}" class="sidebar-link">
                        <i class="fas fa-book align-middle me-2"></i>
                        <span>Cours disponibles</span>
                        @php $nbCours = \App\Models\Course::where('status',1)->count(); @endphp
                        @if($nbCours > 0)
                            <span class="cons-badge">{{ $nbCours }}</span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-header px-4 py-2">ÉVALUATIONS</li>

                <li class="sidebar-item {{ request()->routeIs('conseiller.evaluations') ? 'active' : '' }}">
                    <a href="{{ route('conseiller.evaluations') }}" class="sidebar-link">
                        <i class="fas fa-clipboard-check align-middle me-2"></i>
                        <span>Résultats & Évaluations</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="https://academie-numerique-n4du.onrender.com/" target="_blank" class="sidebar-link">
                        <i class="fas fa-file-alt align-middle me-2"></i>
                        <span>Examens</span>
                        <i class="fas fa-external-link-alt ms-auto" style="font-size:0.65rem;opacity:0.6;"></i>
                    </a>
                </li>

                <li class="sidebar-header px-4 py-2">CONTACTS</li>

                <li class="sidebar-item {{ request()->routeIs('conseiller.contacts') ? 'active' : '' }}">
                    <a href="{{ route('conseiller.contacts') }}" class="sidebar-link">
                        <i class="fas fa-envelope align-middle me-2"></i>
                        <span>Contacter</span>
                    </a>
                </li>

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
<script src="{{ asset('js/app.js') }}"></script>
@isset($script){{ $script }}@endisset
</body>
</html>

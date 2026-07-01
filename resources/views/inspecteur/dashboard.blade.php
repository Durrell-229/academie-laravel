<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Inspecteur — {{ config('app.name') }}</title>
    <link href="{{ asset('plugins/font-awesome-6.4.0/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        :root { --ins-bg:#7B2D00; --ins-dark:#4A1A00; --ins-accent:#FFAB76; }
        .sidebar { background: linear-gradient(180deg, var(--ins-bg) 0%, var(--ins-dark) 100%) !important; }
        .sidebar-brand { color:white !important; border-bottom:1px solid rgba(255,255,255,0.08) !important; }
        .sidebar-link { color:rgba(255,255,255,0.65) !important; border-left:3px solid transparent !important; transition:all 0.2s !important; }
        .sidebar-link:hover, .sidebar-item.active .sidebar-link { color:white !important; background:rgba(255,171,118,0.1) !important; border-left-color:var(--ins-accent) !important; }
        .sidebar-header { color:rgba(255,255,255,0.3) !important; font-size:0.65rem !important; letter-spacing:1.2px !important; }
        .ins-avatar { width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,0.15);border:2px solid var(--ins-accent);display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px; }
        .card { transition:transform 0.2s, box-shadow 0.2s !important; }
        .card:hover { transform:translateY(-2px) !important; box-shadow:0 6px 20px rgba(123,45,0,0.12) !important; }
        .logout-link { color:rgba(255,100,100,0.75) !important; }
        .logout-link:hover { color:#ff6b6b !important; background:rgba(255,100,100,0.1) !important; border-left-color:#ff4444 !important; }
    </style>
</head>
<body>
<div class="wrapper">
    <nav id="sidebar" class="sidebar js-sidebar">
        <div class="sidebar-content js-simplebar">
            <a href="{{ route('inspecteur.dashboard') }}" class="sidebar-brand d-flex align-items-center gap-2 px-4 py-3">
                <i class="fas fa-search" style="font-size:1.2rem;color:var(--ins-accent);"></i>
                <span style="font-weight:700;">{{ config('app.name') }}</span>
            </a>
            <div class="p-3 d-flex align-items-center gap-2" style="border-bottom:1px solid rgba(255,255,255,0.08);">
                <div class="ins-avatar">{{ strtoupper(substr(Auth::user()->firstname,0,1)) }}{{ strtoupper(substr(Auth::user()->lastname,0,1)) }}</div>
                <div>
                    <div style="color:white;font-size:0.88rem;font-weight:600;">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</div>
                    <div style="color:var(--ins-accent);font-size:0.7rem;background:rgba(255,171,118,0.15);padding:1px 8px;border-radius:10px;display:inline-block;">Inspecteur</div>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item active">
                    <a href="{{ route('inspecteur.dashboard') }}" class="sidebar-link">
                        <i class="fas fa-gauge-high align-middle me-2"></i>Tableau de bord
                    </a>
                </li>
                <li class="sidebar-header px-4 py-2">INSPECTION</li>
                <li class="sidebar-item">
                    <a href="{{ route('site.courses') }}" class="sidebar-link">
                        <i class="fas fa-book align-middle me-2"></i>Cours publiés
                    </a>
                </li>
                <li class="sidebar-header px-4 py-2">ÉVALUATIONS</li>
                <li class="sidebar-item">
                    <a href="https://academie-numerique-n4du.onrender.com/" target="_blank" class="sidebar-link">
                        <i class="fas fa-file-alt align-middle me-2"></i>Examens
                        <i class="fas fa-external-link-alt ms-auto" style="font-size:0.65rem;opacity:0.6;"></i>
                    </a>
                </li>
                <li class="sidebar-header px-4 py-2">MON COMPTE</li>
                <li class="sidebar-item">
                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <i class="fas fa-user-edit align-middle me-2"></i>Mon profil
                    </a>
                </li>
                <li class="sidebar-item mt-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link logout-link w-100 text-start border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt align-middle me-2"></i>Se déconnecter
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h1 class="h3 mb-1 fw-bold" style="color:#7B2D00;">
                            🔍 Bonjour, <strong>{{ Auth::user()->firstname }}</strong> !
                        </h1>
                        <p class="text-muted small mb-0">
                            <i class="fas fa-calendar me-1"></i>
                            {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                        </p>
                    </div>
                    <span class="badge fs-6" style="background:#7B2D00;">Inspecteur</span>
                </div>

                {{-- Stats --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center py-3" style="border-left:4px solid #7B2D00!important;">
                            <div class="h3 fw-bold mb-0" style="color:#7B2D00;">{{ \App\Models\Course::where('status',1)->count() }}</div>
                            <div class="small text-muted">Cours publiés</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center py-3" style="border-left:4px solid #1A5FB4!important;">
                            <div class="h3 fw-bold mb-0" style="color:#1A5FB4;">{{ \App\Models\User::count() }}</div>
                            <div class="small text-muted">Utilisateurs</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center py-3" style="border-left:4px solid #0D4F3C!important;">
                            <div class="h3 fw-bold mb-0" style="color:#0D4F3C;">{{ \App\Models\Enrollment::count() }}</div>
                            <div class="small text-muted">Inscriptions</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card border-0 shadow-sm text-center py-3" style="border-left:4px solid #F57F17!important;">
                            <div class="h3 fw-bold mb-0" style="color:#F57F17;">{{ \App\Models\Lesson::count() }}</div>
                            <div class="small text-muted">Leçons</div>
                        </div>
                    </div>
                </div>

                {{-- Cours récents --}}
                <div class="card border-0 shadow-sm" style="border-top:3px solid #7B2D00!important;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold" style="color:#7B2D00;">
                            <i class="fas fa-book me-2"></i>Cours publiés
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @php $courses = \App\Models\Course::where('status',1)->with(['user','category'])->latest()->take(10)->get(); @endphp
                        @if($courses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr><th>Titre</th><th>Professeur</th><th>Catégorie</th><th>Inscrits</th><th>Prix</th></tr>
                                    </thead>
                                    <tbody>
                                        @foreach($courses as $course)
                                        <tr>
                                            <td class="fw-semibold">{{ Str::limit($course->title, 40) }}</td>
                                            <td><small>{{ $course->user?->firstname }} {{ $course->user?->lastname }}</small></td>
                                            <td><span class="badge bg-warning bg-opacity-15 text-warning">{{ $course->category?->title ?? '—' }}</span></td>
                                            <td><span class="badge bg-primary bg-opacity-15 text-primary">{{ \App\Models\Enrollment::where('course_id',$course->id)->count() }}</span></td>
                                            <td class="fw-semibold" style="color:#7B2D00;">{{ number_format($course->regular_price,0) }} FCFA</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5"><p class="text-muted">Aucun cours publié.</p></div>
                        @endif
                    </div>
                </div>
            </div>
        </main>
        <x-admin-footer />
    </div>
</div>
<script src="{{ asset('plugins/jquery/jquery-3.7.0.min.js') }}"></script>
<script src="{{ asset('plugins/font-awesome-6.4.0/js/all.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue des cours — {{ config('app.name') }}</title>
    <link href="{{ asset('plugins/font-awesome-6.4.0/css/all.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; font-family:'Segoe UI',sans-serif; }
        .navbar-brand { font-weight:700; color:#0A2D6E !important; }
        .course-card { border-radius:12px; transition:transform 0.2s, box-shadow 0.2s; }
        .course-card:hover { transform:translateY(-4px); box-shadow:0 8px 25px rgba(0,0,0,0.12) !important; }
        .course-thumb { height:180px; object-fit:cover; border-radius:12px 12px 0 0; }
        .course-thumb-placeholder { height:180px; background:linear-gradient(135deg,#0A2D6E,#1A5FB4); display:flex; align-items:center; justify-content:center; border-radius:12px 12px 0 0; }
        .price { font-size:1.2rem; font-weight:700; color:#0A2D6E; }
        .btn-primary { background:#0A2D6E; border-color:#0A2D6E; }
        .btn-primary:hover { background:#061B45; border-color:#061B45; }
        .search-box { max-width:500px; }
        .filter-badge { cursor:pointer; transition:all 0.2s; }
        .filter-badge:hover { background:#0A2D6E !important; color:white !important; }
        .filter-badge.active { background:#0A2D6E !important; color:white !important; }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('site.home') }}">
            <i class="fas fa-graduation-cap me-2 text-primary"></i>{{ config('app.name') }}
        </a>
        <div class="d-flex gap-2">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-user me-1"></i>Mon espace
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">S'inscrire</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Header --}}
<div style="background:linear-gradient(135deg,#0A2D6E,#1A5FB4);padding:60px 0;">
    <div class="container text-center text-white">
        <h1 class="fw-bold mb-2">📚 Catalogue des cours</h1>
        <p class="mb-4 opacity-75">Découvrez tous les cours publiés par nos professeurs</p>
        {{-- Recherche --}}
        <div class="d-flex justify-content-center">
            <div class="input-group search-box">
                <input type="text" id="searchInput" class="form-control form-control-lg"
                       placeholder="Rechercher un cours...">
                <button class="btn btn-warning"><i class="fas fa-search"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">

    {{-- Filtres catégories --}}
    @if(isset($categories) && $categories->count() > 0)
    <div class="d-flex gap-2 flex-wrap mb-4">
        <span class="badge rounded-pill filter-badge active py-2 px-3"
              style="background:#0A2D6E;color:white;font-size:0.85rem;"
              onclick="filterCategory('all', this)">
            Toutes les catégories
        </span>
        @foreach($categories as $cat)
        <span class="badge rounded-pill filter-badge py-2 px-3"
              style="background:#e9ecef;color:#495057;font-size:0.85rem;"
              onclick="filterCategory('{{ strtolower($cat->title) }}', this)">
            {{ $cat->title }}
        </span>
        @endforeach
    </div>
    @endif

    {{-- Compteur --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0"><span id="countDisplay">{{ $courses->count() }}</span> cours disponibles</p>
    </div>

    {{-- Grille des cours --}}
    @if($courses->count() > 0)
        <div class="row g-4" id="coursesGrid">
            @foreach($courses as $course)
            <div class="col-md-6 col-xl-4 course-item"
                 data-category="{{ strtolower($course->category?->title ?? '') }}"
                 data-title="{{ strtolower($course->title) }}">
                <div class="card course-card border-0 shadow-sm h-100">
                    {{-- Thumbnail --}}
                    @if($course->details?->thumbnail)
                        <img src="{{ asset('storage/'.$course->details->thumbnail) }}"
                             class="course-thumb" alt="{{ $course->title }}">
                    @else
                        <div class="course-thumb-placeholder">
                            <i class="fas fa-graduation-cap fa-3x text-white opacity-75"></i>
                        </div>
                    @endif

                    <div class="card-body">
                        {{-- Catégorie --}}
                        @if($course->category)
                            <span class="badge mb-2" style="background:rgba(10,45,110,0.1);color:#0A2D6E;">
                                {{ $course->category->title }}
                            </span>
                        @endif

                        {{-- Titre --}}
                        <h5 class="fw-bold mb-2">{{ Str::limit($course->title, 50) }}</h5>

                        {{-- Description --}}
                        @if($course->details?->description)
                            <p class="text-muted small mb-3">
                                {{ Str::limit($course->details->description, 100) }}
                            </p>
                        @endif

                        {{-- Infos --}}
                        <div class="d-flex gap-3 small text-muted mb-2">
                            @if($course->details?->duration)
                                <span><i class="fas fa-clock me-1"></i>{{ $course->details->duration }}</span>
                            @endif
                            <span>
                                <i class="fas fa-list me-1"></i>
                                {{ $course->lessons?->count() ?? 0 }} leçons
                            </span>
                            @if($course->user)
                                <span>
                                    <i class="fas fa-chalkboard-teacher me-1"></i>
                                    {{ $course->user->firstname }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 d-flex justify-content-between align-items-center pt-0 px-3 pb-3">
                        {{-- Prix --}}
                        @if($course->regular_price > 0)
                            <div>
                                <div class="price">
                                    {{ number_format($course->offer_price ?? $course->regular_price, 0, ',', ' ') }} FCFA
                                </div>
                                @if($course->offer_price && $course->offer_price < $course->regular_price)
                                    <small class="text-muted text-decoration-line-through">
                                        {{ number_format($course->regular_price, 0, ',', ' ') }} FCFA
                                    </small>
                                @endif
                            </div>
                        @else
                            <span class="badge bg-success fs-6">Gratuit</span>
                        @endif

                        <a href="{{ route('course.display', ['slug' => $course->slug]) }}"
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Voir le cours
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-graduation-cap fa-4x text-muted d-block mb-3"></i>
            <h4 class="text-muted">Aucun cours disponible pour le moment.</h4>
            <p class="text-muted">Revenez bientôt !</p>
        </div>
    @endif

</div>

{{-- Footer --}}
<footer class="bg-white border-top py-4 mt-5">
    <div class="container text-center text-muted small">
        {{ config('app.name') }} © {{ date('Y') }} — Tous droits réservés
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Recherche en temps réel
    document.getElementById('searchInput').addEventListener('input', function() {
        filterCourses();
    });

    // Filtre par catégorie
    function filterCategory(cat, el) {
        document.querySelectorAll('.filter-badge').forEach(b => {
            b.style.background = '#e9ecef';
            b.style.color = '#495057';
        });
        el.style.background = '#0A2D6E';
        el.style.color = 'white';
        window.currentCategory = cat;
        filterCourses();
    }

    window.currentCategory = 'all';

    function filterCourses() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const cat = window.currentCategory;
        let count = 0;

        document.querySelectorAll('.course-item').forEach(item => {
            const title = item.dataset.title;
            const category = item.dataset.category;
            const matchSearch = title.includes(search);
            const matchCat = cat === 'all' || category.includes(cat);
            const show = matchSearch && matchCat;
            item.style.display = show ? '' : 'none';
            if (show) count++;
        });

        document.getElementById('countDisplay').textContent = count;
    }
</script>
</body>
</html>
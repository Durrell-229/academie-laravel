<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">

  <a href="{{ route('site.home') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
    <h2 class="m-0 text-primary"><i class="fa fa-book me-3"></i>{{ config('app.name') }}</h2>
  </a>

  <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarCollapse">

    <div class="navbar-nav ms-auto p-4 p-lg-0">
      <a href="{{ route('site.home') }}"    class="nav-item nav-link {{ request()->routeIs('site.home')    ? 'active' : '' }}">Accueil</a>
      <a href="{{ route('site.about') }}"   class="nav-item nav-link {{ request()->routeIs('site.about')   ? 'active' : '' }}">A propos</a>
      <a href="{{ route('site.contact') }}" class="nav-item nav-link {{ request()->routeIs('site.contact') ? 'active' : '' }}">Contact</a>
    </div>

    @guest
      <a href="{{ route('login') }}" class="btn btn-primary py-4 px-lg-4 d-none d-lg-block me-2">
        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
      </a>
      <a href="{{ route('register') }}" class="btn btn-success py-4 px-lg-4 d-none d-lg-block">
        S'inscrire<i class="fas fa-arrow-right ms-2"></i>
      </a>
    @else
      @php $role = Auth::user()->role?->slug ?? ''; @endphp

      @if(in_array($role, ['super-admin', 'administrator'], true))
        <a href="{{ route('admin.dashboard') }}" class="btn btn-success py-4 px-lg-4 d-none d-lg-block me-2">
          <i class="fas fa-tachometer-alt me-2"></i>Mon espace
        </a>
      @elseif($role === 'professeur')
        <a href="{{ route('instructor.dashboard') }}" class="btn btn-success py-4 px-lg-4 d-none d-lg-block me-2">
          <i class="fas fa-chalkboard-teacher me-2"></i>Mon espace
        </a>
      @elseif($role === 'conseiller-pedagogique')
        <a href="{{ route('conseiller.dashboard') }}" class="btn btn-success py-4 px-lg-4 d-none d-lg-block me-2">
          <i class="fas fa-compass me-2"></i>Mon espace
        </a>
      @elseif($role === 'inspecteur')
        <a href="{{ route('inspecteur.dashboard') }}" class="btn btn-success py-4 px-lg-4 d-none d-lg-block me-2">
          <i class="fas fa-search me-2"></i>Mon espace
        </a>
      @else
        <a href="{{ route('dashboard') }}" class="btn btn-success py-4 px-lg-4 d-none d-lg-block me-2">
          <i class="fas fa-graduation-cap me-2"></i>Mon espace
        </a>
      @endif

      <form method="POST" action="{{ route('logout') }}" class="d-none d-lg-block">
        @csrf
        <button type="submit" class="btn btn-outline-danger py-4 px-3">
          <i class="fas fa-sign-out-alt"></i>
        </button>
      </form>

      <div class="d-lg-none p-3 border-top">
        <span class="text-muted small">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-danger w-100">
            <i class="fas fa-sign-out-alt me-2"></i>Se deconnecter
          </button>
        </form>
      </div>
    @endguest

  </div>
</nav>
<!-- Navbar End -->
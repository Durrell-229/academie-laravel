<nav class="navbar navbar-expand navbar-light navbar-bg">
  <a class="sidebar-toggle js-sidebar-toggle">
    <i class="hamburger align-self-center"></i>
  </a>
  <ul class="navbar-nav navbar-align">
    <li class="nav-item">
      <a href="{{ route('site.home') }}" class="nav-link">
        <i class="fas fa-house align-middle pe-1"></i>
        <span class="align-middle">{{ __('Home') }}</span>
      </a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-icon nav-link dropdown-toggle" href="javascript:void(0)" id="itemsDropdown" data-bs-toggle="dropdown">
        <i class="align-middle" data-feather="plus"></i>
        <span class="align-middle" style="font-size: 0.85rem;">New Items</span>
      </a>
      <div class="dropdown-menu py-0" aria-labelledby="itemsDropdown">
        <div class="dropdown-menu-header">{{ __('Add New Items') }}</div>
        <div class="list-group">
          @if (Auth::check() && (Auth::user()->role->slug === 'super-admin' || Auth::user()->role->slug === 'administrator'))
            <a href="{{ route('category.create') }}" class="list-group-item">
              <i class="fas fa-plus align-middle"></i>
              <span class="text-dark ps-2">{{ __('Category') }}</span>
            </a>
          @endif
          @if (Auth::check() && (Auth::user()->role->slug === 'super-admin' || Auth::user()->role->slug === 'administrator' || Auth::user()->role->slug === 'instructor'))
            <a href="{{ route('courses.create') }}" class="list-group-item">
              <i class="fas fa-plus align-middle"></i>
              <span class="text-dark ps-2">{{ __('Course') }}</span>
            </a>
          @endif
          @if (Auth::check() && (Auth::user()->role->slug === 'super-admin' || Auth::user()->role->slug === 'administrator' || Auth::user()->role->slug === 'instructor'))
            <a href="{{ route('lessons.create') }}" class="list-group-item">
              <i class="fas fa-plus align-middle"></i>
              <span class="text-dark ps-2">{{ __('Lesson') }}</span>
            </a>
          @endif
          @if (Auth::check() && (Auth::user()->role->slug === 'super-admin' || Auth::user()->role->slug === 'administrator'))
            <a href="{{ route('topics.create') }}" class="list-group-item">
              <i class="fas fa-plus align-middle"></i>
              <span class="text-dark ps-2">{{ __('Topics') }}</span>
            </a>
          @endif
          @if (Auth::check() && (Auth::user()->role->slug === 'super-admin' || Auth::user()->role->slug === 'administrator'))
            <a href="{{ route('users.create') }}" class="list-group-item">
              <i class="fas fa-plus align-middle"></i>
              <span class="text-dark ps-2">{{ __('User') }}</span>
            </a>
          @endif
        </div>
      </div>
    </li>
  </ul>
  <div class="navbar-collapse collapse">
    <ul class="navbar-nav navbar-align">
      <li class="nav-item dropdown">
        <a class="nav-icon nav-link dropdown-toggle d-none d-sm-inline-block" href="javascript:void(0)" data-bs-toggle="dropdown">
          @if(Auth::guard('admin')->check())
            <span class="text-dark">{{ Auth::guard('admin')->user()->firstname . ' ' . Auth::guard('admin')->user()->lastname }}</span>
          @elseif(Auth::check())
            <span class="text-dark">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-end">
          <div class="dropdown-divider"></div>
          <!-- Logout Admin -->
          @if(Auth::guard('admin')->check())
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">
              <i class="align-middle me-1" data-feather="log-out"></i>
              <span>{{ __('Log Out') }}</span>
            </button>
          </form>
          @elseif(Auth::check())
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dropdown-item">
              <i class="align-middle me-1" data-feather="log-out"></i>
              <span>{{ __('Log Out') }}</span>
            </button>
          </form>
          @endif
        </div>
      </li>
    </ul>
  </div>
</nav>
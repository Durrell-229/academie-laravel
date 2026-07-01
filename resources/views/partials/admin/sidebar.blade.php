<aside id="sidebar" class="sidebar js-sidebar">
  <div class="sidebar-content js-simplebar">

    <a class="sidebar-brand" href="{{ route('admin.dashboard') }}">
      <i class="fas fa-graduation-cap me-2"></i>
      <span class="align-middle">{{ config('app.name') }}</span>
    </a>

    <ul class="sidebar-nav">

      <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('admin.dashboard') }}">
          <i class="fas fa-gauge-high align-middle me-2"></i>
          <span>Tableau de bord</span>
        </a>
      </li>

      <li class="sidebar-header">GESTION DES COURS</li>

      <li class="sidebar-item {{ request()->routeIs('courses.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('courses.index') }}">
          <i class="fas fa-graduation-cap align-middle me-2"></i>
          <span>Cours</span>
        </a>
      </li>

      <li class="sidebar-item {{ request()->routeIs('lessons.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('lessons.index') }}">
          <i class="fas fa-chalkboard-user align-middle me-2"></i>
          <span>Lecons</span>
        </a>
      </li>

      <li class="sidebar-item {{ request()->routeIs('category.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('category.index') }}">
          <i class="fas fa-code-branch align-middle me-2"></i>
          <span>Categories</span>
        </a>
      </li>

      <li class="sidebar-item {{ request()->routeIs('topics.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('topics.index') }}">
          <i class="fas fa-tags align-middle me-2"></i>
          <span>Topics</span>
        </a>
      </li>

      <li class="sidebar-header">INSCRIPTIONS</li>

      <li class="sidebar-item {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('enrollments.index') }}">
          <i class="fas fa-user-check align-middle me-2"></i>
          <span>Inscriptions</span>
        </a>
      </li>

      <li class="sidebar-item {{ request()->routeIs('payments.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('payments.index') }}">
          <i class="fas fa-money-bill align-middle me-2"></i>
          <span>Paiements</span>
          @php
            $nbPay = 0;
            if(\Illuminate\Support\Facades\Schema::hasTable('course_payments')) {
              $nbPay = \App\Models\CoursePayment::where('status','pending')->count();
            }
          @endphp
          @if($nbPay > 0)
            <span class="badge bg-warning text-dark ms-auto">{{ $nbPay }}</span>
          @endif
        </a>
      </li>

      <li class="sidebar-header">UTILISATEURS</li>

      <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('users.index') }}">
          <i class="fas fa-users align-middle me-2"></i>
          <span>Tous les utilisateurs</span>
          @php $nbWait = \App\Models\User::where('status',0)->count(); @endphp
          @if($nbWait > 0)
            <span class="badge bg-warning text-dark ms-auto" style="animation:pulse 1.5s infinite;">{{ $nbWait }}</span>
          @endif
        </a>
      </li>

      <li class="sidebar-item {{ request()->routeIs('instructor.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('instructor.index') }}">
          <i class="fas fa-user-tie align-middle me-2"></i>
          <span>Instructeurs</span>
        </a>
      </li>

      <li class="sidebar-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('roles.index') }}">
          <i class="fas fa-user-shield align-middle me-2"></i>
          <span>Roles</span>
        </a>
      </li>

      <li class="sidebar-header">EVALUATIONS</li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="https://academie-numerique-n4du.onrender.com/" target="_blank">
          <i class="fas fa-file-alt align-middle me-2"></i>
          <span>Examens</span>
          <i class="fas fa-external-link-alt ms-auto" style="font-size:0.6rem;opacity:0.5;"></i>
        </a>
      </li>

      <li class="sidebar-header">PARAMETRES</li>

      <li class="sidebar-item {{ request()->routeIs('show.profile') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('show.profile', Auth::guard('admin')->user()->username ?? 'admin') }}">
          <i class="fas fa-user-circle align-middle me-2"></i>
          <span>Mon profil</span>
        </a>
      </li>

      <li class="sidebar-item {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
        <a class="sidebar-link" href="{{ route('admin.settings.index') }}">
          <i class="fas fa-cog align-middle me-2"></i>
          <span>Parametres generaux</span>
        </a>
      </li>

      <li class="sidebar-item">
        <a class="sidebar-link" href="{{ route('site.home') }}">
          <i class="fas fa-globe align-middle me-2"></i>
          <span>Voir le site</span>
        </a>
      </li>

      <li class="sidebar-item mt-3">
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="sidebar-link btn btn-link text-start w-100 border-0 bg-transparent" style="color:rgba(255,100,100,0.8)!important;">
            <i class="fas fa-sign-out-alt align-middle me-2"></i>
            <span>Se deconnecter</span>
          </button>
        </form>
      </li>

    </ul>
  </div>
</aside>

<style>
@keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.5} }
</style>
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a href="{{ route('inspecteur.dashboard') }}" class="sidebar-brand d-flex align-items-center gap-2">
            <i class="fas fa-search"></i>
            <span>Espace Inspecteur</span>
        </a>
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="{{ route('inspecteur.dashboard') }}" class="sidebar-link">
                    <i class="fas fa-home me-2"></i>Tableau de bord
                </a>
            </li>
            <li class="sidebar-header">Mon compte</li>
            <li class="sidebar-item active">
                <a href="{{ route('profile.edit') }}" class="sidebar-link">
                    <i class="fas fa-user-edit me-2"></i>Mon profil
                </a>
            </li>
            <li class="sidebar-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-link w-100 text-start border-0 bg-transparent text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

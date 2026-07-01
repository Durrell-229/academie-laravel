<x-admin-layout>
  <x-slot name="title">Mon Profil</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#0A2D6E;">
      <i class="fas fa-user-shield me-2"></i>Mon profil administrateur
    </h1>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @php $admin = Auth::guard('admin')->user(); @endphp

  <div class="row g-4">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm text-center py-4">
        <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
             style="width:80px;height:80px;background:linear-gradient(135deg,#0A2D6E,#1A5FB4);color:white;font-size:2rem;font-weight:700;">
          {{ strtoupper(substr($admin->firstname ?? 'A', 0, 1)) }}
        </div>
        <h5 class="fw-bold">{{ $admin->firstname }} {{ $admin->lastname }}</h5>
        <span class="badge bg-danger mb-2">Administrateur</span>
        <p class="text-muted small mb-1"><i class="fas fa-envelope me-1"></i>{{ $admin->email }}</p>
        <p class="text-muted small mb-1"><i class="fas fa-phone me-1"></i>{{ $admin->phone }}</p>
        <p class="text-muted small"><i class="fas fa-at me-1"></i>{{ $admin->username }}</p>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">Modifier le profil</h5>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('admin.profile.update', $admin->id) }}">
            @csrf @method('PUT')
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">Prenom</label>
                <input type="text" name="firstname" class="form-control"
                       value="{{ old('firstname', $admin->firstname) }}" required />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nom</label>
                <input type="text" name="lastname" class="form-control"
                       value="{{ old('lastname', $admin->lastname) }}" required />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $admin->email) }}" required />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Telephone</label>
                <input type="text" name="phone" class="form-control"
                       value="{{ old('phone', $admin->phone) }}" />
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Nouveau mot de passe</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Laisser vide pour ne pas changer" />
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-save me-2"></i>Enregistrer
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-admin-layout>
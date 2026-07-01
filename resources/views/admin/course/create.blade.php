<x-admin-layout>
  <x-slot name="title">Nouveau cours</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold" style="color:#0A2D6E;">
        <i class="fas fa-plus me-2"></i>Nouveau cours
      </h1>
      <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
      </a>
    </div>
  </x-slot>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form method="POST" action="{{ route('courses.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">

      <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">
              <i class="fas fa-info-circle me-2"></i>Informations générales
            </h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-lg"
                     value="{{ old('title') }}" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Points clés</label>
              <textarea name="highlights" class="form-control" rows="3">{{ old('highlights') }}</textarea>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">
              <i class="fas fa-image me-2"></i>Image de couverture
            </h5>
          </div>
          <div class="card-body">
            <input type="file" name="thumbnail" class="form-control" accept="image/*"
                   onchange="previewThumb(this)" />
            <div id="thumbPreview" class="mt-3 d-none">
              <img id="thumbImg" src="" class="img-fluid rounded" style="max-height:180px;">
            </div>
          </div>
        </div>
      </div>

      <div class="col-xl-4">

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">
              <i class="fas fa-cog me-2"></i>Paramètres
            </h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Code du cours <span class="text-danger">*</span></label>
              <input type="text" name="course_code" class="form-control"
                     value="{{ old('course_code') }}" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Professeur <span class="text-danger">*</span></label>
              <select name="user_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                @foreach($users as $user)
                  <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Durée</label>
              <input type="text" name="duration" class="form-control"
                     value="{{ old('duration') }}" placeholder="Ex: 12h30" />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Niveau</label>
              <select name="difficulty" class="form-select">
                <option value="">-- Niveau --</option>
                @for($i=1; $i<=5; $i++)
                  <option value="{{ $i }}">{{ str_repeat('⭐', $i) }}</option>
                @endfor
              </select>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0F6E56;">
              <i class="fas fa-tag me-2"></i>Prix (FCFA)
            </h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Prix normal <span class="text-danger">*</span></label>
              <input type="number" name="regular_price" class="form-control"
                     value="{{ old('regular_price', 0) }}" min="0" step="100" required />
              <small class="text-muted">Mettez 0 pour un cours gratuit</small>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Prix promotionnel</label>
              <input type="number" name="offer_price" class="form-control"
                     value="{{ old('offer_price') }}" min="0" step="100" />
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#1A5FB4;">
              <i class="fas fa-eye me-2"></i>Publication
            </h5>
          </div>
          <div class="card-body">
            <select name="status" class="form-select">
              <option value="0">📝 Brouillon</option>
              <option value="1">✅ Publier maintenant</option>
            </select>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
          <i class="fas fa-save me-2"></i>Créer le cours
        </button>
      </div>
    </div>
  </form>

  <x-slot name="script">
  <script>
    function previewThumb(input) {
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
          document.getElementById('thumbImg').src = e.target.result;
          document.getElementById('thumbPreview').classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
  </x-slot>
</x-admin-layout>

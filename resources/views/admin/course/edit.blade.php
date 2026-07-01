<x-admin-layout>
  <x-slot name="title">Modifier le cours</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold" style="color:#0A2D6E;">
        <i class="fas fa-edit me-2"></i>Modifier le cours
      </h1>
      <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
      </a>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form method="POST" action="{{ route('courses.update', $course->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">

      <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">Informations générales</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-lg"
                     value="{{ old('title', $course->title) }}" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control" rows="5" required>{{ old('description', $course->details?->description) }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Points clés</label>
              <textarea name="highlights" class="form-control" rows="3">{{ old('highlights', $course->details?->highlights) }}</textarea>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">Image de couverture</h5>
          </div>
          <div class="card-body">
            @if($course->details?->thumbnail)
              <div class="mb-2">
                <img src="{{ asset('storage/'.$course->details->thumbnail) }}"
                     class="img-fluid rounded" style="max-height:120px;">
                <small class="text-muted d-block mt-1">Image actuelle</small>
              </div>
            @endif
            <input type="file" name="thumbnail" class="form-control" accept="image/*" />
          </div>
        </div>
      </div>

      <div class="col-xl-4">

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0A2D6E;">Paramètres</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Professeur</label>
              <select name="user_id" class="form-select">
                @foreach($users as $user)
                  <option value="{{ $user->id }}" {{ $course->user_id == $user->id ? 'selected' : '' }}>
                    {{ $user->firstname }} {{ $user->lastname }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Catégorie</label>
              <select name="category_id" class="form-select">
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ $course->category_id == $cat->id ? 'selected' : '' }}>
                    {{ $cat->title }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Durée</label>
              <input type="text" name="duration" class="form-control"
                     value="{{ old('duration', $course->details?->duration) }}" />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Niveau</label>
              <select name="difficulty" class="form-select">
                @for($i=1; $i<=5; $i++)
                  <option value="{{ $i }}" {{ ($course->details?->difficulty ?? 0) == $i ? 'selected' : '' }}>
                    {{ str_repeat('⭐', $i) }}
                  </option>
                @endfor
              </select>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0F6E56;">💰 Prix (FCFA)</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Prix normal <span class="text-danger">*</span></label>
              <input type="number" name="regular_price" class="form-control"
                     value="{{ old('regular_price', $course->regular_price) }}" min="0" step="100" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Prix promotionnel</label>
              <input type="number" name="offer_price" class="form-control"
                     value="{{ old('offer_price', $course->offer_price) }}" min="0" step="100" />
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#1A5FB4;">Publication</h5>
          </div>
          <div class="card-body">
            <select name="status" class="form-select">
              <option value="0" {{ $course->status == 0 ? 'selected' : '' }}>📝 Brouillon</option>
              <option value="1" {{ $course->status == 1 ? 'selected' : '' }}>✅ Publié</option>
            </select>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">
          <i class="fas fa-save me-2"></i>Enregistrer les modifications
        </button>
        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
      </div>
    </div>
  </form>
</x-admin-layout>

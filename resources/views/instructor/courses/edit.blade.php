<x-instructor-layout>
  <x-slot name="title">{{ __('Modifier le cours') }}</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0">📖 <strong>Modifier le cours</strong></h1>
      <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Mes cours
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

  <form action="{{ route('instructor.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
      <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Informations générales</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-lg"
                     value="{{ old('title', $course->title) }}" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Points clés</label>
              <textarea name="highlights" class="form-control" rows="3">{{ old('highlights', $course->details?->highlights) }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control" rows="6" required>{{ old('description', $course->details?->description) }}</textarea>
            </div>
          </div>
        </div>
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-file-pdf me-2 text-danger"></i>Mettre à jour le PDF</h5>
          </div>
          <div class="card-body">
            <input type="file" name="pdf_file" class="form-control" accept=".pdf" />
            @if($course->details?->pdf_file)
              <small class="text-success mt-1 d-block">
                <i class="fas fa-check me-1"></i>PDF actuel disponible —
                <a href="{{ asset('storage/'.$course->details->pdf_file) }}" target="_blank">Voir</a>
              </small>
            @endif
          </div>
        </div>
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-video me-2 text-primary"></i>Mettre à jour la vidéo</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label small fw-semibold">Uploader une nouvelle vidéo</label>
              <input type="file" name="video_file" class="form-control" accept="video/*" />
            </div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">Ou lien YouTube / Vimeo</label>
              <input type="url" name="video_url" class="form-control"
                     value="{{ old('video_url', $course->details?->video_url) }}"
                     placeholder="https://www.youtube.com/watch?v=..." />
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-cog me-2 text-secondary"></i>Paramètres</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select" required>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ $course->category_id == $cat->id ? 'selected' : '' }}>
                    {{ $cat->title }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Durée <span class="text-danger">*</span></label>
              <input type="text" name="duration" class="form-control"
                     value="{{ old('duration', $course->details?->duration) }}" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Niveau</label>
              <select name="difficulty" class="form-select">
                <option value="">-- Niveau --</option>
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
            <h5 class="mb-0"><i class="fas fa-tag me-2 text-success"></i>Prix</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Prix normal (FCFA)</label>
              <input type="number" name="regular_price" class="form-control"
                     value="{{ old('regular_price', $course->regular_price) }}" min="0" />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Prix promo (FCFA)</label>
              <input type="number" name="offer_price" class="form-control"
                     value="{{ old('offer_price', $course->offer_price) }}" min="0" />
            </div>
          </div>
        </div>
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-eye me-2 text-info"></i>Publication</h5>
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
        <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
      </div>
    </div>
  </form>
</x-instructor-layout>

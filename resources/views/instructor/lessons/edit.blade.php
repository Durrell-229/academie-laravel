<x-instructor-layout>
  <x-slot name="title">Modifier la leçon</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold">✏️ Modifier la leçon</h1>
      <a href="{{ route('instructor.lessons.index') }}" class="btn btn-outline-secondary">
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

  <form method="POST" action="{{ route('instructor.lessons.update', $lesson->id) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">

      <div class="col-xl-8">

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Informations</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-lg"
                     value="{{ old('title', $lesson->title) }}" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control" rows="4">{{ old('description', $lesson->description) }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Points clés</label>
              <textarea name="highlights" class="form-control" rows="3">{{ old('highlights', $lesson->highlights) }}</textarea>
            </div>
          </div>
        </div>

        {{-- Vidéo --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #E30613!important;">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#E30613;"><i class="fas fa-video me-2"></i>Vidéo</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="fab fa-youtube text-danger me-1"></i>Lien YouTube</label>
              <input type="url" name="youtube_link" class="form-control"
                     value="{{ old('youtube_link', $lesson->youtube_link) }}"
                     placeholder="https://www.youtube.com/watch?v=..." />
              @if($lesson->youtube_link)
                <div class="mt-2">
                  <a href="{{ $lesson->youtube_link }}" target="_blank" class="btn btn-sm btn-outline-danger">
                    <i class="fab fa-youtube me-1"></i>Voir la vidéo actuelle
                  </a>
                </div>
              @endif
            </div>
            <hr>
            <div class="mb-3">
              <label class="form-label fw-semibold"><i class="fas fa-upload me-1"></i>Uploader une nouvelle vidéo</label>
              @if($lesson->video_file)
                <div class="alert alert-info py-2 small">
                  <i class="fas fa-video me-1"></i>Vidéo actuelle : {{ basename($lesson->video_file) }}
                  <br><small class="text-muted">Uploader une nouvelle vidéo remplacera l'ancienne.</small>
                </div>
              @endif
              <input type="file" name="video_file" class="form-control" accept="video/*" />
            </div>
          </div>
        </div>

        {{-- PDF --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #C62828!important;">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#C62828;"><i class="fas fa-file-pdf me-2"></i>Document PDF</h5>
          </div>
          <div class="card-body">
            @if($lesson->pdf_file)
              <div class="d-flex align-items-center gap-3 mb-3 p-3 rounded" style="background:rgba(198,40,40,0.05);">
                <i class="fas fa-file-pdf fa-2x" style="color:#C62828;"></i>
                <div>
                  <div class="fw-semibold small">PDF actuel</div>
                  <a href="{{ asset('storage/'.$lesson->pdf_file) }}" target="_blank" class="btn btn-sm btn-outline-danger mt-1">
                    <i class="fas fa-eye me-1"></i>Voir le PDF
                  </a>
                  <a href="{{ asset('storage/'.$lesson->pdf_file) }}" download class="btn btn-sm btn-outline-secondary mt-1 ms-1">
                    <i class="fas fa-download me-1"></i>Télécharger
                  </a>
                </div>
              </div>
            @endif
            <label class="form-label fw-semibold">{{ $lesson->pdf_file ? 'Remplacer le PDF' : 'Uploader un PDF' }}</label>
            <input type="file" name="pdf_file" class="form-control" accept=".pdf" />
          </div>
        </div>

        {{-- Lien externe --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #1A5FB4!important;">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#1A5FB4;"><i class="fas fa-external-link-alt me-2"></i>Lien externe</h5>
          </div>
          <div class="card-body">
            <input type="url" name="external_link" class="form-control"
                   value="{{ old('external_link', $lesson->external_link) }}"
                   placeholder="https://..." />
          </div>
        </div>

        {{-- Live --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #E30613!important;">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#E30613;">
              <span class="badge bg-danger me-2" style="font-size:0.7rem;">LIVE</span>Cours en direct
            </h5>
          </div>
          <div class="card-body">
            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <a href="https://wa.me/?text={{ urlencode('Cours en direct ! Rejoignez: ') }}"
                   target="_blank" class="btn btn-success w-100">
                  <i class="fab fa-whatsapp me-2"></i>WhatsApp
                </a>
              </div>
              <div class="col-md-4">
                <a href="https://zoom.us/start/videomeeting" target="_blank"
                   class="btn btn-primary w-100">
                  <i class="fas fa-video me-2"></i>Zoom
                </a>
              </div>
              <div class="col-md-4">
                <a href="https://meet.google.com/new" target="_blank"
                   class="btn btn-danger w-100">
                  <i class="fas fa-desktop me-2"></i>Meet
                </a>
              </div>
            </div>
            <input type="url" name="live_link" class="form-control"
                   value="{{ old('live_link', $lesson->external_link) }}"
                   placeholder="Collez votre lien live ici..." />
          </div>
        </div>

      </div>

      <div class="col-xl-4">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-cog me-2"></i>Paramètres</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Cours associé</label>
              <select name="course_id" class="form-select">
                @foreach($courses as $course)
                  <option value="{{ $course->id }}" {{ $lesson->course_id == $course->id ? 'selected' : '' }}>
                    {{ $course->title }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Durée</label>
              <input type="text" name="duration" class="form-control"
                     value="{{ old('duration', $lesson->duration) }}" placeholder="Ex: 1h30" />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Statut</label>
              <select name="status" class="form-select">
                <option value="0" {{ $lesson->status == 0 ? 'selected' : '' }}>📝 Brouillon</option>
                <option value="1" {{ $lesson->status == 1 ? 'selected' : '' }}>✅ Publié</option>
              </select>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 mb-2">
          <i class="fas fa-save me-2"></i>Enregistrer
        </button>
        <a href="{{ route('instructor.lessons.index') }}" class="btn btn-outline-secondary w-100">Annuler</a>
      </div>
    </div>
  </form>
</x-instructor-layout>

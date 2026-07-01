<x-instructor-layout>
  <x-slot name="title">Nouvelle leçon</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold">📝 Nouvelle leçon</h1>
      <a href="{{ route('instructor.lessons.index') }}" class="btn btn-outline-secondary">
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

  <form method="POST" action="{{ route('instructor.lessons.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">

      {{-- Colonne principale --}}
      <div class="col-xl-8">

        {{-- Infos de base --}}
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Informations de la leçon</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-lg"
                     value="{{ old('title') }}" placeholder="Ex: Chapitre 1 — La cellule" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description</label>
              <textarea name="description" class="form-control" rows="4"
                        placeholder="Décrivez le contenu de cette leçon...">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Points clés</label>
              <textarea name="highlights" class="form-control" rows="3"
                        placeholder="Un point par ligne...">{{ old('highlights') }}</textarea>
            </div>
          </div>
        </div>

        {{-- Vidéo --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #E30613!important;">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#E30613;">
              <i class="fas fa-video me-2"></i>Vidéo du cours
            </h5>
          </div>
          <div class="card-body">

            {{-- Option 1: YouTube --}}
            <div class="mb-4">
              <label class="form-label fw-semibold">
                <i class="fab fa-youtube text-danger me-1"></i>Lien YouTube
              </label>
              <input type="url" name="youtube_link" class="form-control"
                     value="{{ old('youtube_link') }}"
                     placeholder="https://www.youtube.com/watch?v=..." />
              <small class="text-muted">Collez le lien de votre vidéo YouTube</small>
            </div>

            <div class="text-center text-muted mb-4">— OU —</div>

            {{-- Option 2: Upload vidéo --}}
            <div class="mb-3">
              <label class="form-label fw-semibold">
                <i class="fas fa-upload text-primary me-1"></i>Uploader une vidéo
              </label>
              <div class="border-2 rounded-3 p-3 text-center"
                   style="border:2px dashed rgba(227,6,19,0.2);cursor:pointer;"
                   onclick="document.getElementById('videoInput').click()"
                   id="videoZone">
                <div id="videoPlaceholder">
                  <i class="fas fa-film fa-2x d-block mb-1" style="color:#E30613;"></i>
                  <p class="small fw-semibold mb-0" style="color:#E30613;">Cliquez pour uploader</p>
                  <p class="text-muted" style="font-size:0.75rem;">MP4, AVI, MOV — max 100MB</p>
                </div>
                <div id="videoPreview" class="d-none">
                  <i class="fas fa-check-circle fa-2x text-success d-block mb-1"></i>
                  <p class="text-success fw-semibold small mb-0" id="videoName"></p>
                </div>
              </div>
              <input type="file" name="video_file" id="videoInput" class="d-none"
                     accept="video/*" onchange="previewFile(this,'videoName','videoPlaceholder','videoPreview','videoZone')" />
            </div>
          </div>
        </div>

        {{-- PDF --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #C62828!important;">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#C62828;">
              <i class="fas fa-file-pdf me-2"></i>Document PDF
            </h5>
          </div>
          <div class="card-body">
            <div class="border-2 rounded-3 p-3 text-center"
                 style="border:2px dashed rgba(198,40,40,0.2);cursor:pointer;"
                 onclick="document.getElementById('pdfInput').click()"
                 id="pdfZone">
              <div id="pdfPlaceholder">
                <i class="fas fa-file-pdf fa-2x d-block mb-1" style="color:#C62828;"></i>
                <p class="small fw-semibold mb-0" style="color:#C62828;">Cliquez pour uploader</p>
                <p class="text-muted" style="font-size:0.75rem;">PDF — max 20MB</p>
              </div>
              <div id="pdfPreview" class="d-none">
                <i class="fas fa-check-circle fa-2x text-success d-block mb-1"></i>
                <p class="text-success fw-semibold small mb-0" id="pdfName"></p>
              </div>
            </div>
            <input type="file" name="pdf_file" id="pdfInput" class="d-none"
                   accept=".pdf" onchange="previewFile(this,'pdfName','pdfPlaceholder','pdfPreview','pdfZone')" />
          </div>
        </div>

        {{-- Lien externe --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #1A5FB4!important;">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#1A5FB4;">
              <i class="fas fa-external-link-alt me-2"></i>Lien externe
            </h5>
          </div>
          <div class="card-body">
            <input type="url" name="external_link" class="form-control"
                   value="{{ old('external_link') }}"
                   placeholder="https://..." />
            <small class="text-muted">Lien vers une ressource externe (exercice, site, document...)</small>
          </div>
        </div>

        {{-- Sessions Live --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #E30613!important;">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#E30613;">
              <span class="badge bg-danger me-2" style="font-size:0.7rem;">LIVE</span>
              Cours en direct
            </h5>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-4">
                <a href="https://wa.me/?text={{ urlencode('Cours en direct ! Rejoignez maintenant.') }}"
                   target="_blank" class="btn btn-success w-100 py-3 d-flex flex-column align-items-center gap-1">
                  <i class="fab fa-whatsapp fa-2x"></i>
                  <span>Lancer WhatsApp</span>
                </a>
              </div>
              <div class="col-md-4">
                <a href="https://zoom.us/start/videomeeting" target="_blank"
                   class="btn btn-primary w-100 py-3 d-flex flex-column align-items-center gap-1">
                  <i class="fas fa-video fa-2x"></i>
                  <span>Lancer Zoom</span>
                </a>
              </div>
              <div class="col-md-4">
                <a href="https://meet.google.com/new" target="_blank"
                   class="btn btn-danger w-100 py-3 d-flex flex-column align-items-center gap-1">
                  <i class="fas fa-desktop fa-2x"></i>
                  <span>Google Meet</span>
                </a>
              </div>
            </div>
            <div class="mt-3">
              <label class="form-label fw-semibold small">Ou copiez votre lien live ici pour le partager :</label>
              <input type="url" name="live_link" class="form-control"
                     placeholder="https://meet.google.com/xxx-xxxx-xxx"
                     value="{{ old('live_link') }}" />
              <small class="text-muted">Ce lien sera visible par les apprenants inscrits.</small>
            </div>
          </div>
        </div>

      </div>

      {{-- Colonne droite --}}
      <div class="col-xl-4">

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-cog me-2"></i>Paramètres</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Cours associé <span class="text-danger">*</span></label>
              <select name="course_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                @foreach($courses as $course)
                  <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                    {{ $course->title }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Durée</label>
              <input type="text" name="duration" class="form-control"
                     value="{{ old('duration') }}" placeholder="Ex: 1h30" />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Statut</label>
              <select name="status" class="form-select">
                <option value="0" {{ old('status')==0 ? 'selected' : '' }}>📝 Brouillon</option>
                <option value="1" {{ old('status')==1 ? 'selected' : '' }}>✅ Publier</option>
              </select>
            </div>
          </div>
        </div>

        {{-- Résumé des médias --}}
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold">📋 Médias ajoutés</h6>
          </div>
          <div class="card-body">
            <ul class="list-unstyled mb-0 small" id="mediaList">
              <li class="text-muted py-1" id="mediaEmpty">Aucun média ajouté</li>
              <li class="py-1 d-none" id="mediaYoutube"><i class="fab fa-youtube text-danger me-2"></i>Vidéo YouTube</li>
              <li class="py-1 d-none" id="mediaVideo"><i class="fas fa-video text-primary me-2"></i>Vidéo uploadée</li>
              <li class="py-1 d-none" id="mediaPdf"><i class="fas fa-file-pdf text-danger me-2"></i>Document PDF</li>
            </ul>
          </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100">
          <i class="fas fa-save me-2"></i>Créer la leçon
        </button>

      </div>
    </div>
  </form>

  <x-slot name="script">
  <script>
    function previewFile(input, nameId, placeholderId, previewId, zoneId) {
      if (input.files && input.files[0]) {
        document.getElementById(nameId).textContent = input.files[0].name;
        document.getElementById(placeholderId).classList.add('d-none');
        document.getElementById(previewId).classList.remove('d-none');
        updateMediaList();
      }
    }

    document.querySelector('[name="youtube_link"]').addEventListener('input', function() {
      updateMediaList();
    });

    function updateMediaList() {
      const hasYT    = document.querySelector('[name="youtube_link"]').value.length > 0;
      const hasVideo = document.getElementById('videoInput').files.length > 0;
      const hasPdf   = document.getElementById('pdfInput').files.length > 0;
      const hasAny   = hasYT || hasVideo || hasPdf;

      document.getElementById('mediaEmpty').classList.toggle('d-none', hasAny);
      document.getElementById('mediaYoutube').classList.toggle('d-none', !hasYT);
      document.getElementById('mediaVideo').classList.toggle('d-none', !hasVideo);
      document.getElementById('mediaPdf').classList.toggle('d-none', !hasPdf);
    }
  </script>
  </x-slot>
</x-instructor-layout>

<x-instructor-layout>
  <x-slot name="title">Nouveau cours</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold">📚 Nouveau cours</h1>
      <a href="{{ route('instructor.courses.index') }}" class="btn btn-outline-secondary">
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

  <form method="POST" action="{{ route('instructor.courses.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">

      {{-- Colonne principale --}}
      <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Informations générales</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Titre du cours <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-lg"
                     value="{{ old('title') }}" placeholder="Ex: SVT — Classe de 3ème" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control" rows="5" required
                        placeholder="Décrivez le contenu de ce cours...">{{ old('description') }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Ce que les apprenants vont apprendre</label>
              <textarea name="highlights" class="form-control" rows="4"
                        placeholder="Un point par ligne. Ex:&#10;Comprendre la cellule&#10;Maîtriser la photosynthèse">{{ old('highlights') }}</textarea>
              <small class="text-muted">Un point par ligne</small>
            </div>
          </div>
        </div>

        {{-- Image de couverture --}}
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-image me-2"></i>Image de couverture du cours</h5>
          </div>
          <div class="card-body">
            <div class="border-2 rounded-3 p-4 text-center"
                 style="border:2px dashed rgba(26,95,180,0.3);cursor:pointer;"
                 onclick="document.getElementById('thumbInput').click()"
                 id="thumbZone">
              <div id="thumbPlaceholder">
                <i class="fas fa-image fa-3x d-block mb-2 text-primary"></i>
                <p class="fw-semibold mb-1 text-primary">Cliquez pour uploader l'image</p>
                <p class="text-muted small mb-0">JPG, PNG, WEBP — max 2MB — Recommandé: 1280×720px</p>
              </div>
              <div id="thumbPreview" class="d-none">
                <img id="thumbImg" src="" class="img-fluid rounded" style="max-height:200px;">
                <p class="text-success fw-semibold mt-2 mb-0" id="thumbName"></p>
              </div>
            </div>
            <input type="file" name="thumbnail" id="thumbInput" class="d-none"
                   accept="image/*" onchange="previewThumb(this)" />
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
              <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select" required>
                <option value="">-- Choisir --</option>
                @foreach($categories as $cat)
                  @if(!$cat->parent_id)
                    <optgroup label="{{ $cat->title }}">
                      @foreach($categories->where('parent_id', $cat->id) as $sub)
                        <option value="{{ $sub->id }}" {{ old('category_id') == $sub->id ? 'selected' : '' }}>
                          {{ $sub->title }}
                        </option>
                      @endforeach
                    </optgroup>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Durée totale</label>
              <input type="text" name="duration" class="form-control"
                     value="{{ old('duration') }}" placeholder="Ex: 3h30" />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Niveau de difficulté</label>
              <select name="difficulty" class="form-select">
                <option value="">-- Niveau --</option>
                <option value="1">⭐ Débutant</option>
                <option value="2">⭐⭐ Élémentaire</option>
                <option value="3">⭐⭐⭐ Intermédiaire</option>
                <option value="4">⭐⭐⭐⭐ Avancé</option>
                <option value="5">⭐⭐⭐⭐⭐ Expert</option>
              </select>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold" style="color:#0F6E56;"><i class="fas fa-tag me-2"></i>Prix</h5>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Prix (FCFA)</label>
              <div class="input-group">
                <input type="number" name="regular_price" class="form-control"
                       value="{{ old('regular_price', 0) }}" min="0" step="100" />
                <span class="input-group-text">FCFA</span>
              </div>
              <small class="text-muted">Mettez 0 pour un cours gratuit. L'admin fixera le prix final.</small>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Prix promotionnel</label>
              <div class="input-group">
                <input type="number" name="offer_price" class="form-control"
                       value="{{ old('offer_price') }}" min="0" step="100" />
                <span class="input-group-text">FCFA</span>
              </div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-eye me-2"></i>Publication</h5>
          </div>
          <div class="card-body">
            <select name="status" class="form-select">
              <option value="0">📝 Brouillon (l'admin publiera)</option>
              <option value="1">✅ Prêt à publier</option>
            </select>
            <small class="text-muted mt-1 d-block">L'admin fixe le prix final avant publication.</small>
          </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg w-100">
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
          document.getElementById('thumbName').textContent = input.files[0].name;
          document.getElementById('thumbPlaceholder').classList.add('d-none');
          document.getElementById('thumbPreview').classList.remove('d-none');
          document.getElementById('thumbZone').style.background = 'rgba(26,95,180,0.03)';
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
  </x-slot>
</x-instructor-layout>

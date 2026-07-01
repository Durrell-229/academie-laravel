<x-admin-layout>
  <x-slot name="title">Modifier la leçon</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3 mb-0 fw-bold" style="color:#0A2D6E;">
        <i class="fas fa-edit me-2"></i>Modifier la leçon
      </h1>
      <a href="{{ route('lessons.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Retour
      </a>
    </div>
  </x-slot>

  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  <form method="POST" action="{{ route('lessons.update', $lesson->id) }}">
    @csrf @method('PUT')
    <div class="row g-4">
      <div class="col-xl-8">
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control form-control-lg"
                     value="{{ old('title', $lesson->title) }}" required />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
              <textarea name="description" class="form-control" rows="6" required>{{ old('description', $lesson->description) }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Points clés</label>
              <textarea name="highlights" class="form-control" rows="3">{{ old('highlights', $lesson->highlights) }}</textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="col-xl-4">
        <div class="card border-0 shadow-sm mb-4">
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
                     value="{{ old('duration', $lesson->duration) }}" />
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Statut</label>
              <select name="status" class="form-select">
                <option value="0" {{ $lesson->status == 0 ? 'selected' : '' }}>📝 Brouillon</option>
                <option value="1" {{ $lesson->status == 1 ? 'selected' : '' }}>✅ Publier</option>
              </select>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg w-100">
          <i class="fas fa-save me-2"></i>Enregistrer
        </button>
        <a href="{{ route('lessons.index') }}" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
      </div>
    </div>
  </form>
</x-admin-layout>

<x-admin-layout>
  <x-slot name="title">Nouvelle catégorie</x-slot>
  <x-slot name="header"><h1 class="h3 mb-3 fw-bold" style="color:#0A2D6E;"><i class="fas fa-plus me-2"></i>Nouvelle catégorie</h1></x-slot>
  <div class="card border-0 shadow-sm" style="max-width:600px;">
    <div class="card-body">
      <form method="POST" action="{{ route('category.store') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}" required />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Catégorie parente</label>
          <select name="parent_id" class="form-select">
            <option value="">— Aucune (catégorie principale) —</option>
            @foreach($mainCat as $cat)
              <option value="{{ $cat->id }}">{{ $cat->title }}</option>
            @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Créer</button>
        <a href="{{ route('category.index') }}" class="btn btn-outline-secondary ms-2">Annuler</a>
      </form>
    </div>
  </div>
</x-admin-layout>

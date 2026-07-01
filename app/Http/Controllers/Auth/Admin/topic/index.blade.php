<x-admin-layout>
  <x-slot name="title">Topics</x-slot>
  <x-slot name="header"><h1 class="h3 mb-3 fw-bold" style="color:#0A2D6E;"><i class="fas fa-tags me-2"></i>Topics</h1></x-slot>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($topics->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>#</th><th>Titre</th><th>Slug</th></tr></thead>
            <tbody>
              @foreach($topics as $i => $topic)
              <tr><td>{{ $i+1 }}</td><td class="fw-semibold">{{ $topic->title }}</td><td><code>{{ $topic->slug }}</code></td></tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-5"><i class="fas fa-tags fa-3x d-block mb-3" style="color:rgba(10,45,110,0.2);"></i><p class="text-muted">Aucun topic.</p></div>
      @endif
    </div>
  </div>
</x-admin-layout>

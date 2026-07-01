<x-admin-layout>
  <x-slot name="title">Topics</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-0 fw-bold">Topics</h1>
  </x-slot>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($topics->count() > 0)
        <table class="table table-hover mb-0">
          <thead class="table-light">
            <tr><th>#</th><th>Titre</th><th>Slug</th></tr>
          </thead>
          <tbody>
            @foreach($topics as $i => $topic)
            <tr>
              <td>{{ $i+1 }}</td>
              <td class="fw-semibold">{{ $topic->title }}</td>
              <td><code>{{ $topic->slug }}</code></td>
            </tr>
            @endforeach
          </tbody>
        </table>
      @else
        <div class="text-center py-5">
          <p class="text-muted">Aucun topic.</p>
        </div>
      @endif
    </div>
  </div>
</x-admin-layout>
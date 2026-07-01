<x-conseiller-layout>
  <x-slot name="title">Tous les apprenants</x-slot>
  <x-slot name="header">
    <h1 class="h3 mb-3 fw-bold" style="color:#0D4F3C;">
      👥 <strong>Tous les apprenants</strong>
    </h1>
  </x-slot>

  {{-- Filtres --}}
  <div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
      <div class="row g-2">
        <div class="col-md-4">
          <div class="input-group">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="search" class="form-control" placeholder="Rechercher...">
          </div>
        </div>
        <div class="col-md-3">
          <select id="filterNiveau" class="form-select">
            <option value="">Tous les niveaux</option>
            <option value="maternelle">Maternelle</option>
            <option value="primaire">Primaire</option>
            <option value="college">Collège</option>
            <option value="lycee">Lycée</option>
            <option value="universite">Université</option>
            <option value="formation">Formation pro</option>
            <option value="adulte">Adulte</option>
          </select>
        </div>
        <div class="col-md-2">
          <select id="filterStatut" class="form-select">
            <option value="">Tous les statuts</option>
            <option value="actif">Actif</option>
            <option value="inactif">Inactif</option>
          </select>
        </div>
        <div class="col-md-3 text-muted small d-flex align-items-center">
          <span id="countDisplay">{{ $apprenants->count() }}</span> apprenant(s)
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      @if($apprenants->count() > 0)
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="apprenantsTable">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Apprenant</th>
                <th>Email / Téléphone</th>
                <th>Niveau scolaire</th>
                <th>Cours inscrits</th>
                <th>Profil complété</th>
                <th>Statut</th>
                <th>Inscrit le</th>
              </tr>
            </thead>
            <tbody>
              @foreach($apprenants as $i => $apprenant)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  <div class="fw-semibold">{{ $apprenant->firstname }} {{ $apprenant->lastname }}</div>
                  <small class="text-muted">@{{ $apprenant->username }}</small>
                </td>
                <td>
                  <div class="small">{{ $apprenant->email }}</div>
                  <div class="small text-muted">{{ $apprenant->phone }}</div>
                </td>
                <td>
                  <span class="badge" style="background:rgba(13,79,60,0.1);color:#0D4F3C;">
                    {{ ucfirst(str_replace('_',' ', $apprenant->profile?->niveau_scolaire ?? 'Non défini')) }}
                  </span>
                </td>
                <td>
                  <span class="badge" style="background:rgba(26,95,180,0.1);color:#1A5FB4;">
                    {{ $apprenant->enrollments?->count() ?? 0 }} cours
                  </span>
                </td>
                <td>
                  @php $completion = $apprenant->profile?->completionPercentage() ?? 0; @endphp
                  <div class="d-flex align-items-center gap-2">
                    <div class="progress flex-grow-1" style="height:6px;">
                      <div class="progress-bar {{ $completion >= 80 ? 'bg-success' : ($completion >= 50 ? 'bg-warning' : 'bg-danger') }}"
                           style="width:{{ $completion }}%"></div>
                    </div>
                    <small class="text-muted">{{ $completion }}%</small>
                  </div>
                </td>
                <td>
                  @if($apprenant->status == 1)
                    <span class="badge bg-success">Actif</span>
                  @else
                    <span class="badge bg-danger">Inactif</span>
                  @endif
                </td>
                <td><small>{{ $apprenant->created_at->format('d/m/Y') }}</small></td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="text-center py-5">
          <i class="fas fa-users fa-4x d-block mb-3" style="color:rgba(13,79,60,0.2);"></i>
          <p class="text-muted">Aucun apprenant inscrit pour le moment.</p>
        </div>
      @endif
    </div>
  </div>

  <x-slot name="script">
  <script>
    document.getElementById('search').addEventListener('input', filterTable);
    document.getElementById('filterNiveau').addEventListener('change', filterTable);
    document.getElementById('filterStatut').addEventListener('change', filterTable);
    function filterTable() {
      const s = document.getElementById('search').value.toLowerCase();
      const n = document.getElementById('filterNiveau').value.toLowerCase();
      const st = document.getElementById('filterStatut').value.toLowerCase();
      let count = 0;
      document.querySelectorAll('#apprenantsTable tbody tr').forEach(row => {
        const t = row.textContent.toLowerCase();
        const ok = t.includes(s) && (!n || t.includes(n)) && (!st || t.includes(st));
        row.style.display = ok ? '' : 'none';
        if (ok) count++;
      });
      document.getElementById('countDisplay').textContent = count;
    }
  </script>
  </x-slot>
</x-conseiller-layout>

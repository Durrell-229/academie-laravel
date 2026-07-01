<x-instructor-layout>
  <x-slot name="title">Tableau de bord</x-slot>
  <x-slot name="header">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h1 class="h3 mb-1">
          👨‍🏫 Bonjour, <strong>{{ Auth::user()->firstname }}</strong> !
        </h1>
        <p class="text-muted mb-0 small">
          <i class="fas fa-calendar me-1"></i>
          {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
        </p>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-success">
          <i class="fas fa-plus me-2"></i>Nouveau cours
        </a>
        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#liveModal">
          <span class="badge bg-light text-danger me-1" style="animation:pulse 1.5s infinite;">LIVE</span>
          Lancer un cours en direct
        </button>
      </div>
    </div>
  </x-slot>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  {{-- ══ STATISTIQUES ══ --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle bg-success bg-opacity-10">
            <i class="fas fa-book fa-2x text-success"></i>
          </div>
          <div>
            <div class="h3 fw-bold mb-0">{{ $courses->count() }}</div>
            <div class="small text-muted">Mes cours</div>
            <div class="small text-success">{{ $courses->where('status', 1)->count() }} publiés</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <a href="{{ route('instructor.courses.index') }}" class="btn btn-sm btn-outline-success w-100">Voir mes cours</a>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle bg-info bg-opacity-10">
            <i class="fas fa-list fa-2x text-info"></i>
          </div>
          <div>
            <div class="h3 fw-bold mb-0">{{ $lessons->count() }}</div>
            <div class="small text-muted">Mes leçons</div>
            <div class="small text-info">{{ $lessons->where('status', 1)->count() }} publiées</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <a href="{{ route('instructor.lessons.index') }}" class="btn btn-sm btn-outline-info w-100">Voir mes leçons</a>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="p-3 rounded-circle bg-warning bg-opacity-10">
            <i class="fas fa-users fa-2x text-warning"></i>
          </div>
          <div>
            <div class="h3 fw-bold mb-0">{{ $students }}</div>
            <div class="small text-muted">Apprenants inscrits</div>
          </div>
        </div>
        <div class="card-footer bg-white border-0 pt-0">
          <a href="{{ route('instructor.apprenants') }}" class="btn btn-sm btn-outline-warning w-100">Voir les apprenants</a>
        </div>
      </div>
    </div>
    <div class="col-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="small text-muted fw-semibold mb-3">
            <span class="badge bg-danger me-1" style="animation:pulse 1.5s infinite;">LIVE</span>
            Lancer rapidement
          </div>
          <div class="d-flex flex-column gap-2">
            <a href="https://wa.me/?text={{ urlencode('📚 Cours en direct — Rejoignez maintenant !') }}"
               target="_blank" class="btn btn-sm btn-success">
              <i class="fab fa-whatsapp me-2"></i>WhatsApp
            </a>
            <a href="https://zoom.us/start/videomeeting" target="_blank"
               class="btn btn-sm btn-primary">
              <i class="fas fa-video me-2"></i>Zoom
            </a>
            <a href="https://meet.google.com/new" target="_blank"
               class="btn btn-sm btn-danger">
              <i class="fas fa-desktop me-2"></i>Google Meet
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    {{-- Mes derniers cours --}}
    <div class="col-xl-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
          <h5 class="mb-0"><i class="fas fa-book me-2 text-success"></i>Mes derniers cours</h5>
          <a href="{{ route('instructor.courses.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus me-1"></i>Nouveau
          </a>
        </div>
        <div class="card-body p-0">
          @if($courses->count() > 0)
            <div class="table-responsive">
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr><th>Titre</th><th>Leçons</th><th>Inscrits</th><th>Prix</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                  @foreach($courses->take(6) as $course)
                  <tr>
                    <td>
                      <div class="fw-semibold">{{ Str::limit($course->title, 30) }}</div>
                      <small class="text-muted">{{ $course->category?->title }}</small>
                    </td>
                    <td><span class="badge bg-info bg-opacity-15 text-info">{{ $course->lessons?->count() ?? 0 }}</span></td>
                    <td><span class="badge bg-warning bg-opacity-15 text-warning">{{ \App\Models\Enrollment::where('course_id', $course->id)->count() }}</span></td>
                    <td><small class="fw-semibold">{{ number_format($course->regular_price, 0) }} F</small></td>
                    <td>
                      @if($course->status == 1)
                        <span class="badge bg-success">Publié</span>
                      @else
                        <span class="badge bg-secondary">Brouillon</span>
                      @endif
                    </td>
                    <td>
                      <div class="d-flex gap-1">
                        <a href="{{ route('instructor.courses.edit', $course->id) }}"
                           class="btn btn-xs btn-outline-primary btn-sm">
                          <i class="fas fa-edit"></i>
                        </a>
                        {{-- Bouton Live pour ce cours --}}
                        <button class="btn btn-xs btn-outline-danger btn-sm"
                                onclick="openLiveModal({{ $course->id }}, '{{ addslashes($course->title) }}')"
                                title="Lancer un live pour ce cours">
                          <i class="fas fa-broadcast-tower"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @else
            <div class="text-center py-5">
              <p class="text-muted">Aucun cours créé.</p>
              <a href="{{ route('instructor.courses.create') }}" class="btn btn-success btn-sm">Créer mon premier cours</a>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Mes dernières leçons --}}
    <div class="col-xl-5">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
          <h5 class="mb-0"><i class="fas fa-list me-2 text-info"></i>Mes dernières leçons</h5>
          <a href="{{ route('instructor.lessons.create') }}" class="btn btn-sm btn-info text-white">
            <i class="fas fa-plus me-1"></i>Nouvelle
          </a>
        </div>
        <div class="card-body p-0">
          @if($lessons->count() > 0)
            <ul class="list-group list-group-flush">
              @foreach($lessons->take(6) as $lesson)
              <li class="list-group-item d-flex justify-content-between align-items-center px-3 py-2">
                <div>
                  <div class="fw-semibold small">{{ Str::limit($lesson->title, 35) }}</div>
                  <small class="text-muted">{{ $lesson->course?->title ?? '—' }}</small>
                </div>
                <div class="d-flex gap-1">
                  @if($lesson->status == 1)
                    <span class="badge bg-success">Publié</span>
                  @else
                    <span class="badge bg-secondary">Brouillon</span>
                  @endif
                  <a href="{{ route('instructor.lessons.edit', $lesson->id) }}"
                     class="btn btn-xs btn-outline-primary btn-sm ms-1">
                    <i class="fas fa-edit"></i>
                  </a>
                </div>
              </li>
              @endforeach
            </ul>
          @else
            <div class="text-center py-5">
              <p class="text-muted">Aucune leçon créée.</p>
              <a href="{{ route('instructor.lessons.create') }}" class="btn btn-info btn-sm text-white">Créer ma première leçon</a>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ══ MODAL LIVE SESSION ══ --}}
  <div class="modal fade" id="liveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background:linear-gradient(135deg,#1A5FB4,#0A2D6E);">
          <h5 class="modal-title text-white">
            <span class="badge bg-danger me-2" style="animation:pulse 1.5s infinite;">LIVE</span>
            Lancer un cours en direct
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">

          {{-- Option 1 : Lancer directement --}}
          <div class="mb-4">
            <h6 class="fw-bold mb-3">⚡ Lancer immédiatement</h6>
            <div class="row g-3">
              <div class="col-md-4">
                <a href="https://wa.me/?text={{ urlencode('📚 Cours en direct — Rejoignez maintenant !') }}"
                   target="_blank"
                   class="btn btn-success w-100 py-3 d-flex flex-column align-items-center gap-2">
                  <i class="fab fa-whatsapp fa-2x"></i>
                  <span>WhatsApp</span>
                </a>
              </div>
              <div class="col-md-4">
                <a href="https://zoom.us/start/videomeeting" target="_blank"
                   class="btn btn-primary w-100 py-3 d-flex flex-column align-items-center gap-2">
                  <i class="fas fa-video fa-2x"></i>
                  <span>Zoom</span>
                </a>
              </div>
              <div class="col-md-4">
                <a href="https://meet.google.com/new" target="_blank"
                   class="btn btn-danger w-100 py-3 d-flex flex-column align-items-center gap-2">
                  <i class="fas fa-desktop fa-2x"></i>
                  <span>Google Meet</span>
                </a>
              </div>
            </div>
          </div>

          <hr>

          {{-- Option 2 : Programmer et partager le lien --}}
          <div>
            <h6 class="fw-bold mb-3">📅 Programmer une session et partager le lien</h6>
            <form id="liveForm">
              @csrf
              <input type="hidden" name="course_id" id="liveCourseId"
                     value="{{ $courses->first()?->id }}">
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Cours concerné</label>
                  <select name="course_id" id="liveCourseSelect" class="form-select">
                    @foreach($courses as $c)
                      <option value="{{ $c->id }}">{{ $c->title }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Titre de la session</label>
                  <input type="text" name="title" class="form-control"
                         placeholder="Ex: Cours du chapitre 3" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label fw-semibold">Plateforme</label>
                  <select name="platform" class="form-select" id="platformSelect">
                    <option value="whatsapp">📱 WhatsApp</option>
                    <option value="zoom">💻 Zoom</option>
                    <option value="meet">🖥️ Google Meet</option>
                  </select>
                </div>
                <div class="col-md-5">
                  <label class="form-label fw-semibold">Lien (optionnel)</label>
                  <input type="url" name="link" id="liveLink" class="form-control"
                         placeholder="https://...">
                  <small class="text-muted">Laissez vide pour générer automatiquement</small>
                </div>
                <div class="col-md-3">
                  <label class="form-label fw-semibold">Date & heure</label>
                  <input type="datetime-local" name="scheduled_at" class="form-control">
                </div>
              </div>
              <div class="mt-3 d-flex gap-2">
                <button type="button" class="btn btn-primary" onclick="createLiveSession()">
                  <i class="fas fa-save me-2"></i>Créer la session
                </button>
              </div>
            </form>

            {{-- Résultat --}}
            <div id="liveResult" class="mt-3 d-none">
              <div class="alert alert-success">
                <strong>✅ Session créée !</strong><br>
                <span class="small">Lien : </span>
                <a id="generatedLink" href="#" target="_blank" class="fw-semibold"></a>
                <button class="btn btn-sm btn-outline-success ms-2" onclick="copyLink()">
                  <i class="fas fa-copy"></i> Copier
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <x-slot name="script">
  <style>
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.4} }
  </style>
  <script>
    function openLiveModal(courseId, courseTitle) {
      document.getElementById('liveCourseSelect').value = courseId;
      document.querySelector('[name="title"]').value = 'Cours en direct — ' + courseTitle;
      new bootstrap.Modal(document.getElementById('liveModal')).show();
    }

    function createLiveSession() {
      const form = document.getElementById('liveForm');
      const data = new FormData(form);
      const token = document.querySelector('meta[name="csrf-token"]').content;

      fetch('{{ route("live.store") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token },
        body: data,
      })
      .then(r => r.json())
      .then(res => {
        if (res.success) {
          document.getElementById('generatedLink').href = res.link;
          document.getElementById('generatedLink').textContent = res.link;
          document.getElementById('liveResult').classList.remove('d-none');
        }
      })
      .catch(() => alert('Erreur lors de la création de la session.'));
    }

    function copyLink() {
      const link = document.getElementById('generatedLink').href;
      navigator.clipboard.writeText(link).then(() => {
        alert('Lien copié !');
      });
    }
  </script>
  </x-slot>
</x-instructor-layout>

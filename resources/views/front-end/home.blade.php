<x-master-layout>
  <x-slot name="title">Accueil - {{ config('app.name') }}</x-slot>
  <x-slot name="hero">
    <div class="container-fluid p-0 mb-5">
      <div class="owl-carousel header-carousel position-relative">
        <div class="owl-carousel-item position-relative">
          <img class="img-fluid" src="{{ asset('img/slide-1.jpeg') }}" alt="" />
          <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background:rgba(24,29,56,.7);">
            <div class="container">
              <div class="row justify-content-start">
                <div class="col-sm-10 col-lg-8">
                  <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Meilleurs cours en ligne</h5>
                  <h1 class="display-3 text-white animated slideInDown">La premiere academie numerique du Benin</h1>
                  <p class="fs-5 text-white mb-4 pb-2">Apprenez a votre rythme avec nos professeurs qualifies. Maths, SVT, Francais, Anglais et bien plus - pour tous les niveaux scolaires.</p>
                  <a href="{{ route('site.courses') }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Voir les cours</a>
                  @guest
                    <a href="{{ route('register') }}" class="btn btn-light py-md-3 px-md-5 animated slideInRight">S'inscrire gratuitement</a>
                  @else
                    <a href="{{ Auth::user()->role?->slug === 'professeur' ? route('instructor.dashboard') : route('dashboard') }}" class="btn btn-light py-md-3 px-md-5 animated slideInRight">Mon espace</a>
                  @endguest
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="owl-carousel-item position-relative">
          <img class="img-fluid" src="{{ asset('img/slide-2.png') }}" alt="" />
          <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center" style="background:rgba(24,29,56,.7);">
            <div class="container">
              <div class="row justify-content-start">
                <div class="col-sm-10 col-lg-8">
                  <h5 class="text-primary text-uppercase mb-3 animated slideInDown">Formez-vous en ligne</h5>
                  <h1 class="display-3 text-white animated slideInDown">Apprenez depuis chez vous, a votre rythme</h1>
                  <p class="fs-5 text-white mb-4 pb-2">Videos, PDFs, cours en direct (live) - tout est disponible sur votre telephone ou ordinateur, 24h/24 et 7j/7.</p>
                  <a href="{{ route('site.courses') }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Decouvrir les cours</a>
                  @guest
                    <a href="{{ route('login') }}" class="btn btn-light py-md-3 px-md-5 animated slideInRight">Se connecter</a>
                  @endguest
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </x-slot>

  @php
    $totalCours  = App\Models\Course::where('status',1)->count();
    $totalEleves = App\Models\User::whereHas('role', fn($q) => $q->where('slug','apprenant'))->count();
    $totalProfs  = App\Models\User::whereHas('role', fn($q) => $q->where('slug','professeur'))->where('status',1)->count();
  @endphp

  <div class="container-xxl py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
          <div class="service-item text-center pt-3">
            <div class="p-4">
              <i class="fas fa-3x fa-chalkboard-teacher text-primary mb-4"></i>
              <h5 class="mb-3">Professeurs qualifies</h5>
              <p class="h3 text-primary fw-bold">{{ $totalProfs }}+</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
          <div class="service-item text-center pt-3">
            <div class="p-4">
              <i class="fas fa-3x fa-book-open text-primary mb-4"></i>
              <h5 class="mb-3">Cours disponibles</h5>
              <p class="h3 text-primary fw-bold">{{ $totalCours }}+</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
          <div class="service-item text-center pt-3">
            <div class="p-4">
              <i class="fas fa-3x fa-users text-primary mb-4"></i>
              <h5 class="mb-3">Apprenants inscrits</h5>
              <p class="h3 text-primary fw-bold">{{ $totalEleves }}+</p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
          <div class="service-item text-center pt-3">
            <div class="p-4">
              <i class="fas fa-3x fa-mobile-alt text-primary mb-4"></i>
              <h5 class="mb-3">100% en ligne</h5>
              <p class="text-muted small">Accessible sur telephone, tablette et ordinateur</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="container-xxl py-5">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s" style="min-height:400px;">
          <div class="position-relative h-100">
            <img class="img-fluid position-absolute w-100 h-100" src="{{ asset('img/about.jpg') }}" alt="A propos" style="object-fit:cover;" />
          </div>
        </div>
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
          <h6 class="section-title bg-white text-start text-primary pe-3">A propos de nous</h6>
          <h1 class="mb-4">Bienvenue a {{ config('app.name') }}</h1>
          <p class="mb-4">Academie Numerique est la premiere plateforme d'apprentissage en ligne dediee aux eleves et etudiants beninois. Nous proposons des cours de qualite pour tous les niveaux : maternelle, primaire, college et lycee.</p>
          <p class="mb-4">Nos cours sont dispenses par des professeurs certifies et couvrent toutes les matieres du programme officiel beninois - avec des videos, des PDFs et des sessions live interactives.</p>
          <div class="row gy-2 gx-4 mb-4">
            <div class="col-sm-6"><p class="mb-0"><i class="fas fa-arrow-right text-primary me-2"></i>Professeurs certifies</p></div>
            <div class="col-sm-6"><p class="mb-0"><i class="fas fa-arrow-right text-primary me-2"></i>Cours en video et PDF</p></div>
            <div class="col-sm-6"><p class="mb-0"><i class="fas fa-arrow-right text-primary me-2"></i>Sessions live interactives</p></div>
            <div class="col-sm-6"><p class="mb-0"><i class="fas fa-arrow-right text-primary me-2"></i>Paiement Mobile Money</p></div>
            <div class="col-sm-6"><p class="mb-0"><i class="fas fa-arrow-right text-primary me-2"></i>Programme beninois officiel</p></div>
            <div class="col-sm-6"><p class="mb-0"><i class="fas fa-arrow-right text-primary me-2"></i>Accessible 24h/24</p></div>
          </div>
          <a class="btn btn-primary py-3 px-5 mt-2" href="{{ route('site.about') }}">En savoir plus</a>
        </div>
      </div>
    </div>
  </div>

  @php $parentCategories = App\Models\Category::whereNull('parent_id')->where('status',1)->get(); @endphp
  <div class="container-xxl py-5 category">
    <div class="container">
      <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="section-title bg-white text-center text-primary px-3">Niveaux scolaires</h6>
        <h1 class="mb-5">Nos categories de cours</h1>
      </div>
      @php
        $catImages = ['maternelle'=>'/img/telecharger.webp','primaire'=>'/img/OIP (1).webp','college'=>'/img/OIP (4).webp','lycee'=>'/img/OIP (3).webp'];
        $catFallback = ['maternelle'=>'/img/ecolier.png','primaire'=>'/img/about.jpg','college'=>'/img/cat-1.jpg','lycee'=>'/img/cat-2.jpg'];
      @endphp
      <div class="row g-3">
        @if($parentCategories->count() > 0)
        <div class="col-lg-7 col-md-6">
          <div class="row g-3">
            @foreach($parentCategories->take(3) as $i => $cat)
            @php $img = $catImages[$cat->slug] ?? '/img/cat-1.jpg'; $fb = $catFallback[$cat->slug] ?? '/img/cat-1.jpg'; $count = App\Models\Course::where('category_id',$cat->id)->count(); @endphp
            <div class="{{ $i === 0 ? 'col-lg-12' : 'col-lg-6' }} col-md-12 wow zoomIn">
              <a class="position-relative d-block overflow-hidden" href="{{ route('site.courses') }}">
                <img class="img-fluid w-100" src="{{ $img }}" onerror="this.src='{{ $fb }}'" style="height:{{ $i===0?'200px':'160px' }};object-fit:cover;" />
                <div class="bg-white text-center position-absolute bottom-0 end-0 py-2 px-3" style="margin:1px;">
                  <h5 class="m-0">{{ $cat->title }}</h5>
                  <small class="text-primary">{{ $count }} cours</small>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
        @if($parentCategories->count() >= 4)
        @php $lastCat = $parentCategories->get(3); $lastImg = $catImages[$lastCat->slug] ?? '/img/cat-4.jpg'; @endphp
        <div class="col-lg-5 col-md-6 wow zoomIn" style="min-height:350px;">
          <a class="position-relative d-block h-100 overflow-hidden" href="{{ route('site.courses') }}">
            <img class="img-fluid position-absolute w-100 h-100" src="{{ $lastImg }}" onerror="this.src='/img/cat-4.jpg'" style="object-fit:cover;" />
            <div class="bg-white text-center position-absolute bottom-0 end-0 py-2 px-3" style="margin:1px;">
              <h5 class="m-0">{{ $lastCat->title }}</h5>
              <small class="text-primary">{{ App\Models\Course::where('category_id',$lastCat->id)->count() }} cours</small>
            </div>
          </a>
        </div>
        @endif
        @endif
      </div>
    </div>
  </div>

  @php $featuredCourses = App\Models\Course::where('status',1)->with(['details','category','user','lessons'])->latest()->take(6)->get(); @endphp
  <div class="container-xxl py-5">
    <div class="container">
      <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="section-title bg-white text-center text-primary px-3">Nos cours</h6>
        <h1 class="mb-5">Cours populaires</h1>
      </div>
      @if($featuredCourses->count() > 0)
      <div class="row g-4 justify-content-center">
        @foreach($featuredCourses as $course)
        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
          <div class="course-item bg-light h-100">
            <div class="position-relative overflow-hidden">
              @if($course->details?->thumbnail)
                <img class="img-fluid w-100" src="{{ asset('storage/'.$course->details->thumbnail) }}" style="height:200px;object-fit:cover;" />
              @else
                <div class="d-flex align-items-center justify-content-center" style="height:200px;background:linear-gradient(135deg,#0A2D6E,#1A5FB4);">
                  <i class="fas fa-graduation-cap fa-4x text-white"></i>
                </div>
              @endif
              <div class="w-100 d-flex justify-content-center position-absolute bottom-0 start-0 mb-3">
                <a href="{{ route('course.display', $course->slug) }}" class="flex-shrink-0 btn btn-sm btn-primary px-3 border-end" style="border-radius:30px 0 0 30px;">Voir le cours</a>
                @auth
                  <a href="{{ route('payment.show', $course->id) }}" class="flex-shrink-0 btn btn-sm btn-primary px-3" style="border-radius:0 30px 30px 0;">S'inscrire</a>
                @else
                  <a href="{{ route('login') }}" class="flex-shrink-0 btn btn-sm btn-primary px-3" style="border-radius:0 30px 30px 0;">S'inscrire</a>
                @endauth
              </div>
            </div>
            <div class="text-center p-4 pb-0">
              @if($course->category)<span class="badge bg-primary mb-2" style="font-size:0.7rem;">{{ $course->category->title }}</span>@endif
              <h3 class="mb-1">{{ $course->regular_price > 0 ? number_format($course->offer_price ?? $course->regular_price,0,',',' ').' FCFA' : 'Gratuit' }}</h3>
              <div class="mb-3">
                <h5 class="mb-1">{{ Str::limit($course->title,40) }}</h5>
                @if($course->user)<small class="text-muted"><i class="fas fa-user-tie text-primary me-1"></i>{{ $course->user->firstname }} {{ $course->user->lastname }}</small>@endif
              </div>
            </div>
            <div class="d-flex border-top">
              <small class="flex-fill text-center border-end py-2"><i class="fas fa-list text-primary me-2"></i>{{ $course->lessons->count() }} lecons</small>
              @if($course->details?->duration)<small class="flex-fill text-center border-end py-2"><i class="fas fa-clock text-primary me-2"></i>{{ $course->details->duration }}</small>@endif
              <small class="flex-fill text-center py-2"><i class="fas fa-users text-primary me-2"></i>{{ App\Models\Enrollment::where('course_id',$course->id)->count() }} inscrits</small>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="text-center mt-4">
        <a href="{{ route('site.courses') }}" class="btn btn-primary py-3 px-5"><i class="fas fa-search me-2"></i>Voir tous les cours</a>
      </div>
      @endif
    </div>
  </div>

  <div class="container-xxl py-5" style="background:#f8f9fa;">
    <div class="container">
      <div class="text-center wow fadeInUp mb-5">
        <h6 class="section-title bg-white text-center text-primary px-3 d-inline-block">Comment ca marche</h6>
        <h1 class="d-block mt-2">3 etapes simples pour apprendre</h1>
      </div>
      <div class="row g-4 text-center">
        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.1s">
          <div class="p-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width:80px;height:80px;background:linear-gradient(135deg,#0A2D6E,#1A5FB4);">
              <span class="text-white h3 fw-bold mb-0">1</span>
            </div>
            <h5 class="fw-bold mb-3">Creez votre compte</h5>
            <p class="text-muted">Inscrivez-vous gratuitement. L'administrateur validera votre compte.</p>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">S'inscrire</a>
          </div>
        </div>
        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.3s">
          <div class="p-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width:80px;height:80px;background:linear-gradient(135deg,#0F6E56,#1A9B76);">
              <span class="text-white h3 fw-bold mb-0">2</span>
            </div>
            <h5 class="fw-bold mb-3">Choisissez un cours</h5>
            <p class="text-muted">Payez via MTN MoMo, Moov Money ou Celtiis Cash.</p>
            <a href="{{ route('site.courses') }}" class="btn btn-outline-success btn-sm">Voir les cours</a>
          </div>
        </div>
        <div class="col-md-4 wow fadeInUp" data-wow-delay="0.5s">
          <div class="p-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width:80px;height:80px;background:linear-gradient(135deg,#F57F17,#FFB300);">
              <span class="text-white h3 fw-bold mb-0">3</span>
            </div>
            <h5 class="fw-bold mb-3">Apprenez et progressez</h5>
            <p class="text-muted">Accedez aux videos, PDFs et sessions live depuis votre espace.</p>
            @auth
              <a href="{{ route('dashboard') }}" class="btn btn-outline-warning btn-sm">Mon espace</a>
            @else
              <a href="{{ route('login') }}" class="btn btn-outline-warning btn-sm">Se connecter</a>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </div>

  @php $instructors = App\Models\User::whereHas('role', fn($q) => $q->where('slug','professeur'))->where('status',1)->take(4)->get(); @endphp
  @if($instructors->count() > 0)
  <div class="container-xxl py-5">
    <div class="container">
      <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
        <h6 class="section-title bg-white text-center text-primary px-3">Nos professeurs</h6>
        <h1 class="mb-5">Professeurs experts</h1>
      </div>
      <div class="row g-4 justify-content-center">
        @foreach($instructors as $instructor)
        <div class="col-lg-3 col-md-6 wow fadeInUp">
          <div class="team-item bg-light">
            <div class="overflow-hidden d-flex align-items-center justify-content-center" style="height:200px;background:linear-gradient(135deg,#0A2D6E,#1A5FB4);">
              @if($instructor->profile?->avatar)
                <img class="img-fluid w-100 h-100" src="{{ asset('storage/'.$instructor->profile->avatar) }}" style="object-fit:cover;" />
              @else
                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:80px;height:80px;background:rgba(255,255,255,0.2);">
                  <span class="text-white fw-bold" style="font-size:1.8rem;">{{ strtoupper(substr($instructor->firstname,0,1)) }}{{ strtoupper(substr($instructor->lastname,0,1)) }}</span>
                </div>
              @endif
            </div>
            <div class="text-center p-4">
              <h5 class="mb-1 fw-bold">{{ $instructor->firstname }} {{ $instructor->lastname }}</h5>
              <small class="text-muted">Professeur</small>
              <div class="mt-2"><small class="text-primary"><i class="fas fa-book me-1"></i>{{ App\Models\Course::where('user_id',$instructor->id)->where('status',1)->count() }} cours</small></div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
  @endif

  <div class="container-xxl py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container">
      <div class="text-center">
        <h6 class="section-title bg-white text-center text-primary px-3">Temoignages</h6>
        <h1 class="mb-5">Ce que disent nos apprenants</h1>
      </div>
      <div class="owl-carousel testimonial-carousel position-relative">
        <div class="testimonial-item text-center">
          <img class="border rounded-circle p-2 mx-auto mb-3" src="{{ asset('img/testimonial-1.jpg') }}" style="width:80px;height:80px;" />
          <h5 class="mb-0">Kofi Mensah</h5><p>Eleve en Terminale</p>
          <div class="testimonial-text bg-light text-center p-4"><p class="mb-0">Grace a Academie Numerique, j'ai pu revoir mes cours de Maths et de SVT a mon rythme. Je comprends mieux qu'en classe !</p></div>
        </div>
        <div class="testimonial-item text-center">
          <img class="border rounded-circle p-2 mx-auto mb-3" src="{{ asset('img/testimonial-2.jpg') }}" style="width:80px;height:80px;" />
          <h5 class="mb-0">Aminata Diallo</h5><p>Eleve en 3eme</p>
          <div class="testimonial-text bg-light text-center p-4"><p class="mb-0">Les videos des professeurs sont tres claires. J'ai eu de bonnes notes au brevet grace aux cours en ligne.</p></div>
        </div>
        <div class="testimonial-item text-center">
          <img class="border rounded-circle p-2 mx-auto mb-3" src="{{ asset('img/testimonial-3.jpg') }}" style="width:80px;height:80px;" />
          <h5 class="mb-0">Jean-Pierre Houngo</h5><p>Parent d'eleve</p>
          <div class="testimonial-text bg-light text-center p-4"><p class="mb-0">Je suis content de voir mon fils suivre ses cours en ligne. Le paiement via MoMo est tres pratique.</p></div>
        </div>
        <div class="testimonial-item text-center">
          <img class="border rounded-circle p-2 mx-auto mb-3" src="{{ asset('img/testimonial-4.jpg') }}" style="width:80px;height:80px;" />
          <h5 class="mb-0">Fatou Coulibaly</h5><p>Eleve en CM2</p>
          <div class="testimonial-text bg-light text-center p-4"><p class="mb-0">J'adore les cours de Francais et de Sciences. Les PDFs m'aident beaucoup pour mes revisions.</p></div>
        </div>
      </div>
    </div>
  </div>

  @guest
  <div class="container-xxl py-5">
    <div class="container">
      <div class="text-center p-5 wow fadeInUp" style="background:linear-gradient(135deg,#0A2D6E,#1A5FB4);border-radius:16px;">
        <h2 class="text-white fw-bold mb-3">Pret a commencer votre apprentissage ?</h2>
        <p class="text-white-50 mb-4">Rejoignez des centaines d'apprenants qui font confiance a Academie Numerique.</p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-5 fw-bold"><i class="fas fa-user-plus me-2"></i>S'inscrire gratuitement</a>
          <a href="{{ route('site.courses') }}" class="btn btn-outline-light btn-lg px-5"><i class="fas fa-search me-2"></i>Voir les cours</a>
        </div>
      </div>
    </div>
  </div>
  @endguest

</x-master-layout>
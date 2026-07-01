<!-- Footer Start -->
<footer class="container-fluid bg-dark text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">
  <section class="container py-5">
    <div class="row g-5">

      {{-- Contact --}}
      <div class="col-lg-3 col-md-6">
        <h4 class="text-white mb-3">Contact</h4>
        <p class="mb-2"><i class="fas fa-map-marker-alt me-3"></i>Cotonou, Benin</p>
        <p class="mb-2"><i class="fas fa-phone-alt me-3"></i>+229 97 65 08 17</p>
        <p class="mb-2"><i class="fas fa-phone-alt me-3"></i>+229 01 49 51 85 65</p>
        <p class="mb-2"><i class="fas fa-envelope me-3"></i>contact@academienumerique.bj</p>
        <div class="d-flex pt-2 gap-1">
          <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-facebook-f"></i></a>
          <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-whatsapp"></i></a>
          <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-twitter"></i></a>
          <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-linkedin-in"></i></a>
          <a class="btn btn-outline-light btn-social" href="#"><i class="fab fa-instagram"></i></a>
        </div>
      </div>

      {{-- Liens rapides --}}
      <div class="col-lg-3 col-md-6">
        <h4 class="text-white mb-3">Liens rapides</h4>
        <a class="btn btn-link" href="{{ route('site.home') }}">Accueil</a>
        <a class="btn btn-link" href="{{ route('site.about') }}">A propos</a>
        <a class="btn btn-link" href="{{ route('site.courses') }}">Nos cours</a>
        <a class="btn btn-link" href="{{ route('site.contact') }}">Contact</a>
        @guest
          <a class="btn btn-link" href="{{ route('login') }}">Se connecter</a>
          <a class="btn btn-link" href="{{ route('register') }}">S'inscrire</a>
        @endguest
      </div>

      {{-- Niveaux --}}
      <div class="col-lg-3 col-md-6">
        <h4 class="text-white mb-3">Niveaux scolaires</h4>
        @php
          $footerCats = App\Models\Category::whereNull('parent_id')->where('status',1)->get();
        @endphp
        @foreach($footerCats as $cat)
          <a class="btn btn-link" href="{{ route('site.courses') }}">{{ $cat->title }}</a>
        @endforeach
        <a class="btn btn-link" href="{{ route('site.courses') }}">Voir tout</a>
      </div>

      {{-- Newsletter --}}
      <div class="col-lg-3 col-md-6">
        <h4 class="text-white mb-3">Newsletter</h4>
        <p class="text-white-50">Recevez les nouveaux cours et actualites directement dans votre boite mail.</p>
        <div class="position-relative mx-auto" style="max-width:400px;">
          <input class="form-control border-0 w-100 py-3 ps-3 pe-5" type="email"
                 placeholder="Votre adresse email" />
          <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2">
            <i class="fas fa-paper-plane"></i>
          </button>
        </div>
        <div class="mt-3">
          <div class="d-flex align-items-center gap-2 text-white-50 small">
            <i class="fas fa-mobile-alt text-primary"></i>
            <span>Paiement accepte : MTN, Moov, Celtiis</span>
          </div>
        </div>
      </div>

    </div>
  </section>

  <section class="container">
    <div class="copyright">
      <div class="row">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          &copy; <a class="border-bottom" href="{{ route('site.home') }}">{{ config('app.name') }}</a>
          {{ now()->year }} â€” Tous droits reserves.
        </div>
        <div class="col-md-6 text-center text-md-end">
          <div class="footer-menu">
            <a href="{{ route('site.home') }}">Accueil</a>
            <a href="{{ route('site.about') }}">A propos</a>
            <a href="{{ route('site.contact') }}">Contact</a>
            <a href="{{ route('site.courses') }}">Cours</a>
          </div>
        </div>
      </div>
    </div>
  </section>
</footer>
<!-- Footer End -->

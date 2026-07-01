<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { font-family:'Segoe UI',sans-serif; }

        .contact-hero {
            min-height: 380px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .contact-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('/img/OIP (4).webp');
            background-size: cover;
            background-position: center;
            filter: brightness(0.35);
            z-index: 0;
        }
        .contact-hero .hero-content {
            position: relative;
            z-index: 1;
            width: 100%;
        }

        .info-card {
            border-left: 4px solid;
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .info-card:hover { transform: translateX(5px); box-shadow: 0 8px 20px rgba(0,0,0,0.08) !important; }

        .form-card { border-top: 4px solid #0F6E56; border-radius: 12px; }

        .btn-send { background: #0F6E56; color: white; border: none; transition: background 0.2s; }
        .btn-send:hover { background: #084d3c; color: white; }

        .social-btn {
            width: 44px; height: 44px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            transition: transform 0.2s, opacity 0.2s;
            text-decoration: none;
        }
        .social-btn:hover { transform: translateY(-3px); opacity: 0.85; }

        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-left { opacity: 0; transform: translateX(-40px); transition: all 0.6s ease; }
        .reveal-left.visible { opacity: 1; transform: translateX(0); }
        .reveal-right { opacity: 0; transform: translateX(40px); transition: all 0.6s ease; }
        .reveal-right.visible { opacity: 1; transform: translateX(0); }

        .map-placeholder {
            background: linear-gradient(135deg, #0A2D6E, #1A5FB4);
            border-radius: 12px;
            min-height: 220px;
            display: flex; align-items: center; justify-content: center;
        }
    </style>
</head>
<body>

@include('partials.front.navbar')

{{-- HERO --}}
<div class="contact-hero text-white">
    <div class="container text-center hero-content py-5">
        <span class="badge px-4 py-2 mb-3" style="background:rgba(255,255,255,0.2);font-size:0.9rem;">
            <i class="fas fa-envelope me-2"></i>Contactez-nous
        </span>
        <h1 class="display-4 fw-bold text-white mb-3">Nous sommes a votre ecoute</h1>
        <p class="fs-5 mb-4" style="color:rgba(255,255,255,0.85);">
            Une question ? Besoin d'aide ? Notre equipe vous repond rapidement.
        </p>
        <nav aria-label="breadcrumb" class="d-flex justify-content-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('site.home') }}" style="color:rgba(255,255,255,0.6);">Accueil</a>
                </li>
                <li class="breadcrumb-item active text-white">Contact</li>
            </ol>
        </nav>
    </div>
</div>

{{-- INFOS RAPIDES --}}
<div class="container py-5">
    <div class="row g-4 justify-content-center mb-2">
        <div class="col-md-4 reveal">
            <div class="card border-0 shadow-sm p-4 text-center h-100 info-card" style="border-left-color:#0A2D6E!important;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:56px;height:56px;background:rgba(10,45,110,0.1);">
                    <i class="fas fa-phone fa-lg" style="color:#0A2D6E;"></i>
                </div>
                <h6 class="fw-bold mb-2">Telephone</h6>
                <a href="tel:+22997650817" class="text-decoration-none fw-semibold" style="color:#0A2D6E;">
                    +229 97 65 08 17
                </a>
                <div class="text-muted small mt-1">Disponible Lun-Sam 8h-18h</div>
            </div>
        </div>
        <div class="col-md-4 reveal" style="transition-delay:0.1s;">
            <div class="card border-0 shadow-sm p-4 text-center h-100 info-card" style="border-left-color:#0F6E56!important;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:56px;height:56px;background:rgba(15,110,86,0.1);">
                    <i class="fas fa-envelope fa-lg" style="color:#0F6E56;"></i>
                </div>
                <h6 class="fw-bold mb-2">Email</h6>
                <a href="mailto:Medarddaboua@gmail.com" class="text-decoration-none fw-semibold" style="color:#0F6E56;">
                    Medarddaboua@gmail.com
                </a>
                <div class="text-muted small mt-1">Reponse sous 24h</div>
            </div>
        </div>
        <div class="col-md-4 reveal" style="transition-delay:0.2s;">
            <div class="card border-0 shadow-sm p-4 text-center h-100 info-card" style="border-left-color:#1877F2!important;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                     style="width:56px;height:56px;background:rgba(24,119,242,0.1);">
                    <i class="fab fa-facebook fa-lg" style="color:#1877F2;"></i>
                </div>
                <h6 class="fw-bold mb-2">Facebook</h6>
                <a href="https://www.facebook.com/medard.daboua" target="_blank"
                   class="text-decoration-none fw-semibold" style="color:#1877F2;">
                    medard.daboua
                </a>
                <div class="text-muted small mt-1">Suivez-nous sur Facebook</div>
            </div>
        </div>
    </div>
</div>

{{-- FORMULAIRE + INFOS --}}
<div style="background:#f8f9fa;" class="py-5">
<div class="container">
    <div class="row g-5">

        {{-- Infos de contact --}}
        <div class="col-lg-4 reveal-left">
            <div class="mb-4">
                <span class="badge px-3 py-2 mb-2" style="background:rgba(15,110,86,0.1);color:#0F6E56;font-size:0.8rem;">
                    NOUS JOINDRE
                </span>
                <h2 class="fw-bold mb-3" style="color:#0A2D6E;">Parlons ensemble</h2>
                <p class="text-muted" style="line-height:1.8;">
                    Vous avez une question sur nos formations, un probleme technique
                    ou souhaitez un partenariat ? Contactez-nous !
                </p>
            </div>

            <div class="d-flex flex-column gap-3">
                <div class="card border-0 shadow-sm p-3 info-card" style="border-left-color:#0A2D6E!important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;background:rgba(10,45,110,0.1);">
                            <i class="fas fa-map-marker-alt" style="color:#0A2D6E;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">Adresse</div>
                            <div class="text-muted small">Cotonou, Benin, Afrique de l'Ouest</div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-3 info-card" style="border-left-color:#0F6E56!important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;background:rgba(15,110,86,0.1);">
                            <i class="fas fa-phone" style="color:#0F6E56;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">Telephone</div>
                            <a href="tel:+22997650817" class="text-muted small text-decoration-none">
                                +229 97 65 08 17
                            </a>
                            <br>
                            <a href="tel:+22901495185650" class="text-muted small text-decoration-none">
                                +229 01 49 51 85 65
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-3 info-card" style="border-left-color:#F57F17!important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;background:rgba(245,127,23,0.1);">
                            <i class="fas fa-envelope" style="color:#F57F17;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">Email</div>
                            <a href="mailto:Medarddaboua@gmail.com"
                               class="text-muted small text-decoration-none">
                                Medarddaboua@gmail.com
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-3 info-card" style="border-left-color:#1A5FB4!important;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;background:rgba(26,95,180,0.1);">
                            <i class="fas fa-clock" style="color:#1A5FB4;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold small">Heures d'ouverture</div>
                            <div class="text-muted small">Lun - Ven : 8h00 - 18h00</div>
                            <div class="text-muted small">Sam : 9h00 - 14h00</div>
                        </div>
                    </div>
                </div>

                {{-- Reseaux sociaux --}}
                <div class="card border-0 shadow-sm p-3">
                    <div class="fw-semibold small mb-3">Suivez-nous</div>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/medard.daboua" target="_blank"
                           class="social-btn" style="background:#1877F2;color:white;">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://wa.me/22997650817" target="_blank"
                           class="social-btn" style="background:#25D366;color:white;">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="mailto:Medarddaboua@gmail.com"
                           class="social-btn" style="background:#EA4335;color:white;">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Formulaire --}}
        <div class="col-lg-8 reveal-right">
            <div class="card border-0 shadow-sm p-4 p-md-5 form-card">
                <h4 class="fw-bold mb-4" style="color:#0A2D6E;">
                    <i class="fas fa-paper-plane me-2"></i>Envoyez-nous un message
                </h4>

                @if(session('contact_success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>
                        Votre message a ete envoye avec succes ! Nous vous repondrons dans les 24h.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('site.contact.send') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Prenom et Nom <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control form-control-lg"
                                   placeholder="Ex: Alliance ODAH"
                                   value="{{ old('name') }}" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control form-control-lg"
                                   placeholder="votre@email.com"
                                   value="{{ old('email') }}" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Telephone</label>
                            <input type="text" name="phone" class="form-control form-control-lg"
                                   placeholder="+229 XX XX XX XX"
                                   value="{{ old('phone') }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Sujet <span class="text-danger">*</span>
                            </label>
                            <select name="subject" class="form-select form-select-lg" required>
                                <option value="">-- Choisir un sujet --</option>
                                <option value="inscription" {{ old('subject')=='inscription' ? 'selected' : '' }}>
                                    Inscription a un cours
                                </option>
                                <option value="paiement" {{ old('subject')=='paiement' ? 'selected' : '' }}>
                                    Question sur le paiement
                                </option>
                                <option value="technique" {{ old('subject')=='technique' ? 'selected' : '' }}>
                                    Probleme technique
                                </option>
                                <option value="partenariat" {{ old('subject')=='partenariat' ? 'selected' : '' }}>
                                    Partenariat
                                </option>
                                <option value="autre" {{ old('subject')=='autre' ? 'selected' : '' }}>
                                    Autre
                                </option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">
                                Message <span class="text-danger">*</span>
                            </label>
                            <textarea name="message" class="form-control" rows="6"
                                      placeholder="Ecrivez votre message ici..."
                                      required>{{ old('message') }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-send btn-lg px-5">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Lien direct WhatsApp --}}
            <div class="mt-4 p-4 rounded-3" style="background:rgba(37,211,102,0.08);border:1px solid rgba(37,211,102,0.2);">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:52px;height:52px;background:#25D366;">
                        <i class="fab fa-whatsapp fa-xl text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold">Discutez directement sur WhatsApp</div>
                        <div class="text-muted small">Reponse rapide garantie</div>
                    </div>
                    <a href="https://wa.me/22997650817?text=Bonjour, je vous contacte depuis Academie Numerique."
                       target="_blank" class="btn btn-success px-4">
                        <i class="fab fa-whatsapp me-2"></i>Ecrire sur WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
</div>

@include('partials.front.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal,.reveal-left,.reveal-right').forEach(el => observer.observe(el));
</script>
</body>
</html>

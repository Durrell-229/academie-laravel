<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — {{ config('app.name') }}</title>
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            min-height: 100vh;
            background: url("{{ asset('img/slide-1.jpeg') }}") center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            position: relative;
            overflow-x: hidden;
        }

        /* Overlay diagonal gauche */
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(0,70,127,0.92) 0%, rgba(0,30,60,0.88) 100%);
            clip-path: polygon(0 0, 55% 0, 38% 100%, 0 100%);
            z-index: 1;
        }

        /* Texte de bienvenue côté gauche */
        .welcome-side {
            position: fixed;
            left: 0; top: 0;
            width: 50%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 2;
            padding: 2rem;
            color: white;
        }

        .welcome-side .logo-text {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            letter-spacing: 2px;
        }

        .welcome-side .tagline {
            font-size: 1rem;
            opacity: 0.85;
            text-align: center;
            margin-top: 0.5rem;
        }

        .roles-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .role-badge {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.78rem;
            backdrop-filter: blur(4px);
        }

        /* Formulaire côté droit */
        .form-side {
            position: relative;
            z-index: 3;
            width: 100%;
            max-width: 480px;
            margin: 2rem;
        }

        .auth-card {
            background: rgba(255,255,255,0.97);
            border-radius: 16px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .auth-card .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .auth-card .brand-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #00467F;
        }

        /* Bouton retour */
        .back-home {
            position: fixed;
            top: 20px; left: 20px;
            z-index: 10;
            background: rgba(255,255,255,0.9);
            padding: 8px 16px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            color: #00467F;
            font-size: 0.85rem;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .back-home:hover { background: #00467F; color: white; transform: scale(1.05); }

        /* Onglets rôle */
        .role-tabs { margin-bottom: 1.5rem; }
        .role-tabs .nav-link {
            color: #666;
            font-size: 0.82rem;
            padding: 6px 12px;
            border-radius: 8px;
        }
        .role-tabs .nav-link.active {
            background: #00467F;
            color: white;
        }

        /* Champs */
        .form-control {
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: #00467F;
            box-shadow: 0 0 0 3px rgba(0,70,127,0.12);
        }

        .input-group-text {
            background: #f8f9fa;
            border-radius: 8px 0 0 8px;
            border: 1.5px solid #dee2e6;
            color: #00467F;
        }

        /* Bouton submit */
        .btn-submit {
            background: linear-gradient(135deg, #00467F, #0069c0);
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            padding: 12px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        .btn-submit::before {
            content: "";
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: rgba(255,255,255,0.2);
            transform: skewX(-45deg);
            transition: all 0.4s;
        }
        .btn-submit:hover::before { left: 100%; }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,70,127,0.35); }

        /* Séparateur admin */
        .admin-link {
            font-size: 0.8rem;
            color: #888;
            text-align: center;
            margin-top: 1rem;
        }
        .admin-link a { color: #00467F; font-weight: 600; }

        @media (max-width: 768px) {
            .overlay, .welcome-side { display: none; }
            .form-side { margin: 1rem auto; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="overlay"></div>

    <!-- Côté gauche : bienvenue -->
    <div class="welcome-side d-none d-lg-flex">
        <i class="bi bi-book-half" style="font-size:3.5rem; opacity:0.9;"></i>
        <div class="logo-text mt-3">{{ config('app.name') }}</div>
        <div class="tagline">Plateforme de gestion de cours en ligne</div>

        <div class="roles-badges mt-4">
            <span class="role-badge"><i class="bi bi-person-fill me-1"></i>Apprenant</span>
            <span class="role-badge"><i class="bi bi-person-workspace me-1"></i>Professeur</span>
            <span class="role-badge"><i class="bi bi-people-fill me-1"></i>Conseiller</span>
            <span class="role-badge"><i class="bi bi-eye-fill me-1"></i>Inspecteur</span>
            <span class="role-badge"><i class="bi bi-shield-fill me-1"></i>Admin</span>
        </div>

        <p class="mt-4 small" style="opacity:0.6; text-align:center;">
            Connectez-vous pour accéder<br>à votre espace personnel
        </p>
    </div>

    <!-- Bouton retour -->
    <a href="{{ route('site.home') }}" class="back-home">
        <i class="bi bi-arrow-left"></i> Accueil
    </a>

    <!-- Formulaire -->
    <div class="form-side">
        <div class="auth-card">

            <div class="brand">
                <i class="bi bi-book-half text-primary" style="font-size:1.8rem;"></i>
                <span class="brand-name">{{ config('app.name') }}</span>
            </div>

            <h5 class="text-center fw-bold mb-1">Connexion</h5>
            <p class="text-center text-muted small mb-4">Entrez vos identifiants pour accéder à votre espace</p>

            {{-- Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        <i class="bi bi-envelope me-1 text-primary"></i>Adresse email <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}"
                               placeholder="votre@email.com" required autofocus />
                        <div class="invalid-feedback">Veuillez entrer votre email.</div>
                    </div>
                </div>

                {{-- Mot de passe --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        <i class="bi bi-lock me-1 text-primary"></i>Mot de passe <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control"
                               id="password" placeholder="Votre mot de passe" required />
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                        <div class="invalid-feedback">Veuillez entrer votre mot de passe.</div>
                    </div>
                </div>

                {{-- Se souvenir + mot de passe oublié --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">Se souvenir de moi</label>
                    </div>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-primary">Mot de passe oublié ?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-submit w-100">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                </button>
            </form>

            <hr class="my-3">
            <p class="text-center small text-muted mb-0">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-primary fw-semibold">S'inscrire</a>
            </p>

            <div class="admin-link">
                Espace administrateur ?
                <a href="{{ route('admin.create') }}">Connexion Admin</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle mot de passe
        document.getElementById('togglePassword').addEventListener('click', function() {
            const f = document.getElementById('password');
            const i = this.querySelector('i');
            f.type = f.type === 'password' ? 'text' : 'password';
            i.classList.toggle('bi-eye');
            i.classList.toggle('bi-eye-slash');
        });

        // Validation Bootstrap
        (function() {
            'use strict';
            document.querySelectorAll('.needs-validation').forEach(form => {
                form.addEventListener('submit', e => {
                    if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
                    form.classList.add('was-validated');
                });
            });
        })();
    </script>
</body>
</html>

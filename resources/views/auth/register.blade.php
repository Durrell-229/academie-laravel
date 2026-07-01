<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription — {{ config('app.name') }}</title>
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { font-family: 'Poppins', sans-serif; }

        body {
            min-height: 100vh;
            background: url("{{ asset('img/slide-2.png') }}") center/cover no-repeat fixed;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            position: relative;
        }

        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: linear-gradient(135deg, rgba(0,70,127,0.92) 0%, rgba(0,30,60,0.88) 100%);
            clip-path: polygon(0 0, 55% 0, 38% 100%, 0 100%);
            z-index: 1;
        }

        .welcome-side {
            position: fixed;
            left: 0; top: 0;
            width: 48%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 2;
            padding: 2rem;
            color: white;
        }

        .welcome-side h2 { font-weight: 700; font-size: 1.6rem; }

        .step-list {
            list-style: none;
            padding: 0;
            margin-top: 1.5rem;
        }
        .step-list li {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .step-num {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

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
        .back-home:hover { background: #00467F; color: white; }

        .form-side {
            position: relative;
            z-index: 3;
            width: 100%;
            max-width: 540px;
            margin: 1.5rem;
        }

        .auth-card {
            background: rgba(255,255,255,0.97);
            border-radius: 16px;
            padding: 2rem 2.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-height: 92vh;
            overflow-y: auto;
        }

        .auth-card::-webkit-scrollbar { width: 4px; }
        .auth-card::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .auth-card::-webkit-scrollbar-thumb { background: #00467F; border-radius: 4px; }

        .brand { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 1rem; }
        .brand-name { font-size: 1.3rem; font-weight: 700; color: #00467F; }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            padding: 9px 13px;
            font-size: 0.88rem;
            transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #00467F;
            box-shadow: 0 0 0 3px rgba(0,70,127,0.12);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 1.5px solid #dee2e6;
            color: #00467F;
            font-size: 0.85rem;
        }

        /* Cartes de rôle */
        .role-card {
            border: 2px solid #dee2e6;
            border-radius: 10px;
            padding: 12px 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: white;
        }
        .role-card:hover { border-color: #00467F; background: #f0f6ff; }
        .role-card.selected { border-color: #00467F; background: #e8f0fe; }
        .role-card .role-icon { font-size: 1.5rem; margin-bottom: 4px; }
        .role-card .role-name { font-size: 0.75rem; font-weight: 600; color: #333; }
        .role-card .role-desc { font-size: 0.65rem; color: #888; }

        /* Niveau pédagogique */
        .niveau-section { display: none; }
        .niveau-section.show { display: block; }

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

        .section-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #00467F;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #e8f0fe;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .overlay, .welcome-side { display: none; }
            .form-side { margin: 1rem auto; max-width: 100%; }
        }
    </style>
</head>
<body>

    <div class="overlay"></div>

    <!-- Côté gauche -->
    <div class="welcome-side d-none d-lg-flex">
        <i class="bi bi-person-plus-fill" style="font-size:3rem; opacity:0.9;"></i>
        <h2 class="mt-3">Rejoignez-nous !</h2>
        <p style="opacity:0.8; text-align:center; font-size:0.9rem;">
            Créez votre compte et accédez à des centaines de cours
        </p>
        <ul class="step-list">
            <li><span class="step-num">1</span>Remplissez vos informations personnelles</li>
            <li><span class="step-num">2</span>Choisissez votre rôle sur la plateforme</li>
            <li><span class="step-num">3</span>Indiquez votre niveau pédagogique</li>
            <li><span class="step-num">4</span>Créez votre mot de passe sécurisé</li>
        </ul>
    </div>

    <a href="{{ route('site.home') }}" class="back-home">
        <i class="bi bi-arrow-left"></i> Accueil
    </a>

    <div class="form-side">
        <div class="auth-card">
            <div class="brand">
                <i class="bi bi-book-half text-primary" style="font-size:1.6rem;"></i>
                <span class="brand-name">{{ config('app.name') }}</span>
            </div>

            <h5 class="text-center fw-bold mb-1">Créer un compte</h5>
            <p class="text-center text-muted small mb-3">Tous les champs marqués <span class="text-danger">*</span> sont obligatoires</p>

            @if(session('success'))
                <div class="alert alert-success py-2">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                @csrf

                {{-- ══ SECTION 1 : Informations personnelles ══ --}}
                <div class="section-title"><i class="bi bi-person me-2"></i>Informations personnelles</div>

                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Prénom <span class="text-danger">*</span></label>
                        <input type="text" name="firstname" class="form-control"
                               value="{{ old('firstname') }}" placeholder="Votre prénom" required />
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="lastname" class="form-control"
                               value="{{ old('lastname') }}" placeholder="Votre nom" required />
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold">Nom d'utilisateur <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="text" name="username" class="form-control"
                               value="{{ old('username') }}" placeholder="identifiant_unique" required />
                    </div>
                </div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email') }}" placeholder="votre@email.com" required />
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Téléphone <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone') }}" placeholder="+229 00 00 00 00" required />
                    </div>
                </div>

                {{-- ══ SECTION 2 : Choix du rôle ══ --}}
                <div class="section-title"><i class="bi bi-person-badge me-2"></i>Votre rôle <span class="text-danger">*</span></div>

                <input type="hidden" name="role_type" id="roleInput" value="{{ old('role_type', 'apprenant') }}" required />

                <div class="row g-2 mb-3" id="roleCards">
                    <div class="col-4">
                        <div class="role-card {{ old('role_type', 'apprenant') === 'apprenant' ? 'selected' : '' }}"
                             onclick="selectRole('apprenant', this)">
                            <div class="role-icon">🎓</div>
                            <div class="role-name">Apprenant</div>
                            <div class="role-desc">Étudiant / Élève</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="role-card {{ old('role_type') === 'professeur' ? 'selected' : '' }}"
                             onclick="selectRole('professeur', this)">
                            <div class="role-icon">👨‍🏫</div>
                            <div class="role-name">Professeur</div>
                            <div class="role-desc">Enseignant / Tuteur</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="role-card {{ old('role_type') === 'conseiller' ? 'selected' : '' }}"
                             onclick="selectRole('conseiller', this)">
                            <div class="role-icon">🧭</div>
                            <div class="role-name">Conseiller</div>
                            <div class="role-desc">Pédagogique</div>
                        </div>
                    </div>
                    <div class="col-6 offset-3">
                        <div class="role-card {{ old('role_type') === 'inspecteur' ? 'selected' : '' }}"
                             onclick="selectRole('inspecteur', this)">
                            <div class="role-icon">🔍</div>
                            <div class="role-name">Inspecteur</div>
                            <div class="role-desc">Supervision / Audit</div>
                        </div>
                    </div>
                </div>

                {{-- ══ SECTION 3 : Profil pédagogique (apprenant uniquement) ══ --}}
                <div id="niveauSection" class="niveau-section {{ old('role_type', 'apprenant') === 'apprenant' ? 'show' : '' }}">
                    <div class="section-title"><i class="bi bi-mortarboard me-2"></i>Profil pédagogique</div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Type d'apprenant <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="type_apprenant" id="t_scolaire"
                                       value="scolaire" {{ old('type_apprenant') === 'scolaire' ? 'checked' : '' }}
                                       onchange="toggleNiveau('scolaire')">
                                <label class="btn btn-outline-primary w-100 py-2 small" for="t_scolaire">
                                    <i class="bi bi-backpack me-1"></i>Scolaire
                                </label>
                            </div>
                            <div class="col-6">
                                <input type="radio" class="btn-check" name="type_apprenant" id="t_nonscolaire"
                                       value="non_scolaire" {{ old('type_apprenant', 'non_scolaire') === 'non_scolaire' ? 'checked' : '' }}
                                       onchange="toggleNiveau('non_scolaire')">
                                <label class="btn btn-outline-success w-100 py-2 small" for="t_nonscolaire">
                                    <i class="bi bi-briefcase me-1"></i>Adulte / Formation
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-semibold">Niveau <span class="text-danger">*</span></label>
                        <select name="niveau_scolaire" id="niveauSelect" class="form-select">
                            <option value="">-- Choisir votre niveau --</option>
                            <optgroup label="🎒 Scolaire" id="grp_scolaire">
                                <option value="maternelle" {{ old('niveau_scolaire') === 'maternelle' ? 'selected' : '' }}>Maternelle</option>
                                <option value="primaire"   {{ old('niveau_scolaire') === 'primaire'   ? 'selected' : '' }}>Primaire (CP → CM2)</option>
                                <option value="college"    {{ old('niveau_scolaire') === 'college'    ? 'selected' : '' }}>Collège (6ème → 3ème)</option>
                                <option value="lycee"      {{ old('niveau_scolaire') === 'lycee'      ? 'selected' : '' }}>Lycée (2nde → Terminale)</option>
                            </optgroup>
                            <optgroup label="🎓 Non scolaire" id="grp_nonscolaire">
                                <option value="universite"               {{ old('niveau_scolaire') === 'universite'               ? 'selected' : '' }}>Université / Supérieur</option>
                                <option value="formation_professionnelle" {{ old('niveau_scolaire') === 'formation_professionnelle' ? 'selected' : '' }}>Formation professionnelle</option>
                                <option value="adulte"                   {{ old('niveau_scolaire') === 'adulte'                   ? 'selected' : '' }}>Adulte (autodidacte)</option>
                                <option value="autre"                    {{ old('niveau_scolaire') === 'autre'                    ? 'selected' : '' }}>Autre</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Classe / Année (optionnel)</label>
                        <input type="text" name="classe" class="form-control"
                               value="{{ old('classe') }}" placeholder="Ex: Terminale S, Licence 2..." />
                    </div>
                </div>

                {{-- ══ SECTION 4 : Mot de passe ══ --}}
                <div class="section-title"><i class="bi bi-lock me-2"></i>Sécurité</div>

                <div class="mb-2">
                    <label class="form-label small fw-semibold">Mot de passe <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control"
                               id="password" placeholder="Minimum 8 caractères" required />
                        <button class="btn btn-outline-secondary" type="button" id="togglePass">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Confirmer le mot de passe <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" class="form-control"
                               id="passConfirm" placeholder="Répétez votre mot de passe" required />
                        <button class="btn btn-outline-secondary" type="button" id="togglePassConfirm">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                {{-- CGU --}}
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label small" for="terms">
                        J'accepte les <a href="{{ route('site.terms') }}" target="_blank" class="text-primary">termes et conditions</a>
                        <span class="text-danger">*</span>
                    </label>
                </div>

                <button type="submit" class="btn btn-submit w-100">
                    <i class="bi bi-person-check me-2"></i>Créer mon compte
                </button>
            </form>

            <p class="text-center small mt-3 mb-0">
                Déjà un compte ?
                <a href="{{ route('login') }}" class="text-primary fw-semibold">Se connecter</a>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sélection du rôle
        function selectRole(role, el) {
            document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            document.getElementById('roleInput').value = role;

            // Afficher profil pédagogique uniquement pour apprenant
            const section = document.getElementById('niveauSection');
            section.classList.toggle('show', role === 'apprenant');
        }

        // Toggle niveaux selon type apprenant
        function toggleNiveau(type) {
            const sel = document.getElementById('niveauSelect');
            const grpS  = document.getElementById('grp_scolaire');
            const grpNS = document.getElementById('grp_nonscolaire');
            sel.value = '';
            if (type === 'scolaire') {
                grpS.style.display = '';
                grpNS.style.display = 'none';
            } else {
                grpS.style.display = 'none';
                grpNS.style.display = '';
            }
        }

        // Toggle visibilité mots de passe
        function togglePwd(inputId, btnId) {
            const f = document.getElementById(inputId);
            const i = document.getElementById(btnId).querySelector('i');
            f.type = f.type === 'password' ? 'text' : 'password';
            i.classList.toggle('bi-eye');
            i.classList.toggle('bi-eye-slash');
        }
        document.getElementById('togglePass').addEventListener('click', () => togglePwd('password', 'togglePass'));
        document.getElementById('togglePassConfirm').addEventListener('click', () => togglePwd('passConfirm', 'togglePassConfirm'));

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

        // Init au chargement
        document.addEventListener('DOMContentLoaded', () => {
            const typeChecked = document.querySelector('input[name="type_apprenant"]:checked');
            if (typeChecked) toggleNiveau(typeChecked.value);
            else toggleNiveau('non_scolaire');
        });
    </script>
</body>
</html>

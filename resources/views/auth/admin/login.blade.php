<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { font-family: 'Segoe UI', sans-serif; }
        body {
            min-height: 100vh;
            background: url("{{ asset('img/slide-1.jpeg') }}") center/cover no-repeat fixed;
            display: flex; align-items: center; justify-content: center;
        }
        .overlay {
            position: fixed; top:0; left:0; width:100%; height:100%;
            background: rgba(0,20,50,0.85); z-index: 1;
        }
        .card {
            position: relative; z-index: 2;
            width: 100%; max-width: 420px;
            border-radius: 16px; border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }
        .btn-admin {
            background: linear-gradient(135deg, #c0392b, #922b21);
            border: none; color: white; font-weight: 600;
            padding: 12px; border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-admin:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(192,57,43,0.4); color: white; }
        .form-control:focus { border-color: #c0392b; box-shadow: 0 0 0 3px rgba(192,57,43,0.15); }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="card p-5">
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock-fill text-danger" style="font-size:3rem;"></i>
            <h4 class="fw-bold mt-2">Espace Administrateur</h4>
            <p class="text-muted small">Accès réservé aux administrateurs</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}" placeholder="admin@email.com" required autofocus />
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold small">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password"
                           class="form-control" placeholder="Votre mot de passe" required />
                    <button class="btn btn-outline-secondary" type="button" id="togglePwd">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-admin w-100">
                <i class="bi bi-shield-check me-2"></i>Connexion Admin
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('site.home') }}" class="small text-muted">
                <i class="bi bi-arrow-left me-1"></i>Retour à l'accueil
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('togglePwd').addEventListener('click', function() {
            const f = document.getElementById('password');
            const i = this.querySelector('i');
            f.type = f.type === 'password' ? 'text' : 'password';
            i.classList.toggle('bi-eye');
            i.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
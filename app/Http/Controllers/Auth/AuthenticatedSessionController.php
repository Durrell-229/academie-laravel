<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // ── Vérifier si le compte est validé par l'admin ─────────
        if ($user->status == 0) {
            // Déconnecter immédiatement
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors([
                    'email' => '⏳ Votre compte est en attente de validation par l\'administrateur. Veuillez patienter.',
                ])
                ->onlyInput('email');
        }

        $role = $user->role?->slug ?? '';

        // ── Redirection selon le rôle ─────────────────────────────

        // Admin / Super Admin
        if (in_array($role, ['super-admin', 'administrator'], true)) {
            return redirect()->intended(RouteServiceProvider::SUPER);
        }

        // Professeur
        if ($role === 'professeur') {
            return redirect()->intended(RouteServiceProvider::INSTRUCTOR);
        }

     // ── 3. Conseiller pédagogique ────────────────────────────
if (in_array($role, ['conseiller-pedagogique', 'support-staff', 'counselor', 'advisor', 'conseiller'], true)) {
    return redirect()->intended('/conseiller');
}

     if (in_array($role, ['inspecteur', 'inspector'], true)) {
    return redirect()->intended('/inspecteur');
}

        // Par défaut → Apprenant (même si rôle non défini)
        return redirect()->intended(RouteServiceProvider::STUDENT);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('site.home');
    }
}

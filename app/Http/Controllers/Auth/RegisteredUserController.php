<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $roleMapping = [
            'apprenant'  => ['apprenant'],
            'professeur' => ['professeur'],
            'conseiller' => ['conseiller-pedagogique'],
            'inspecteur' => ['inspecteur'],
        ];

        $request->validate([
            'firstname'       => ['required', 'string', 'max:50'],
            'lastname'        => ['required', 'string', 'max:50'],
            'username'        => ['required', 'string', 'max:25', 'unique:users,username'],
            'email'           => ['required', 'string', 'email', 'max:100', 'unique:users,email'],
            'phone'           => ['required', 'string', 'max:19', 'unique:users,phone'],
            'password'        => ['required', 'confirmed', Rules\Password::defaults()],
            'role_type'       => ['nullable', 'in:apprenant,professeur,conseiller,inspecteur'],
            'type_apprenant'  => ['nullable', 'in:scolaire,non_scolaire'],
            'niveau_scolaire' => ['nullable', 'string'],
            'classe'          => ['nullable', 'string', 'max:50'],
        ], [
            'firstname.required'  => 'Le prénom est obligatoire.',
            'lastname.required'   => 'Le nom est obligatoire.',
            'username.required'   => 'Le nom d\'utilisateur est obligatoire.',
            'username.unique'     => 'Ce nom d\'utilisateur est déjà pris.',
            'email.required'      => 'L\'email est obligatoire.',
            'email.unique'        => 'Cette adresse email est déjà utilisée.',
            'phone.required'      => 'Le numéro de téléphone est obligatoire.',
            'phone.unique'        => 'Ce numéro est déjà utilisé.',
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.confirmed'  => 'Les mots de passe ne correspondent pas.',
        ]);

        // Rôle apprenant par défaut (peu importe ce que l'utilisateur choisit)
        // L'admin attribuera le bon rôle après validation
        $roleApprenant = Role::where('slug', 'apprenant')->first();
        $roleId        = $roleApprenant?->id ?? 16;

        // status = 0 → en attente de validation par l'admin
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'username'  => $request->username,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role_id'   => $roleId,
            'status'    => 0, // ← EN ATTENTE DE VALIDATION ADMIN
        ]);

        // Créer le profil pédagogique
        Profile::create([
            'user_id'         => $user->id,
            'type_apprenant'  => $request->type_apprenant ?? 'non_scolaire',
            'niveau_scolaire' => $request->niveau_scolaire,
            'classe'          => $request->classe,
        ]);

        event(new Registered($user));

        return redirect()->route('login')
            ->with('success', '🎉 Inscription réussie ! Votre compte est en attente de validation par l\'administrateur. Vous recevrez une confirmation avant de pouvoir vous connecter.');
    }
}

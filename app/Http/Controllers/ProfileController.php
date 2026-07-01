<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil
     */
    public function edit(Request $request): View
    {
        $user    = $request->user();
        $profile = Profile::firstOrCreate(['user_id' => $user->id]);

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Mettre à jour les infos du compte (email, nom…)
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'firstname' => ['required', 'string', 'max:50'],
            'lastname'  => ['required', 'string', 'max:50'],
            'username'  => ['required', 'string', 'max:25', 'unique:users,username,' . $user->id],
            'email'     => ['required', 'email', 'max:100', 'unique:users,email,' . $user->id],
            'phone'     => ['nullable', 'string', 'max:19'],
        ]);

        $user->fill([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'username'  => $request->username,
            'email'     => $request->email,
            'phone'     => $request->phone,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Mettre à jour le profil civil complet
     */
    public function updateFull(Request $request): RedirectResponse
    {
        $user    = $request->user();
        $profile = Profile::firstOrCreate(['user_id' => $user->id]);

        $request->validate([
            'avatar'                   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'date_of_birth'            => ['nullable', 'date', 'before:today'],
            'gender'                   => ['nullable', 'in:male,female,other'],
            'marital'                  => ['nullable', 'in:single,married,divorced,widowed'],
            'nationality'              => ['nullable', 'string', 'max:100'],
            'country_of_birth'         => ['nullable', 'string', 'max:100'],
            'city_of_birth'            => ['nullable', 'string', 'max:100'],
            'country_of_residence'     => ['nullable', 'string', 'max:100'],
            'city_of_residence'        => ['nullable', 'string', 'max:100'],
            'address'                  => ['nullable', 'string', 'max:500'],
            'religion'                 => ['nullable', 'string', 'max:100'],
            'biography'                => ['nullable', 'string', 'max:1000'],
            'birth_certificate_number' => ['nullable', 'string', 'max:100'],
            'birth_certificate_center' => ['nullable', 'string', 'max:150'],
            'birth_certificate_date'   => ['nullable', 'date'],
            'birth_certificate_country'=> ['nullable', 'string', 'max:100'],
            'children_count'           => ['nullable', 'integer', 'min:0'],
            'father_firstname'         => ['nullable', 'string', 'max:100'],
            'father_lastname'          => ['nullable', 'string', 'max:100'],
            'mother_firstname'         => ['nullable', 'string', 'max:100'],
            'mother_lastname'          => ['nullable', 'string', 'max:100'],
            'emergency_contact_name'   => ['nullable', 'string', 'max:150'],
            'emergency_contact_phone'  => ['nullable', 'string', 'max:30'],
            'emergency_contact_relation'=> ['nullable', 'string', 'max:100'],
            'id_type'                  => ['nullable', 'in:cni,passport,permis,autre'],
            'id_number'                => ['nullable', 'string', 'max:100'],
            'id_expiry_date'           => ['nullable', 'date'],
            'id_issuing_country'       => ['nullable', 'string', 'max:100'],
            'id_document'              => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'birth_certificate_file'   => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'occupation'               => ['nullable', 'string', 'max:150'],
            'employer'                 => ['nullable', 'string', 'max:150'],
            'professional_email'       => ['nullable', 'email', 'max:150'],
            'facebook'                 => ['nullable', 'url', 'max:255'],
            'linkedin'                 => ['nullable', 'url', 'max:255'],
            'twitter'                  => ['nullable', 'url', 'max:255'],
            'website'                  => ['nullable', 'url', 'max:255'],
        ]);

        $data = $request->except(['_token', '_method', 'avatar', 'id_document', 'birth_certificate_file']);

        // Photo de profil
        if ($request->hasFile('avatar')) {
            if ($profile->avatar) Storage::disk('public')->delete($profile->avatar);
            $data['avatar'] = $request->file('avatar')->store('profiles/avatars', 'public');
        }

        // Pièce d'identité
        if ($request->hasFile('id_document')) {
            if ($profile->id_document) Storage::disk('public')->delete($profile->id_document);
            $data['id_document'] = $request->file('id_document')->store('profiles/documents', 'public');
        }

        // Acte de naissance
        if ($request->hasFile('birth_certificate_file')) {
            if ($profile->birth_certificate_file) Storage::disk('public')->delete($profile->birth_certificate_file);
            $data['birth_certificate_file'] = $request->file('birth_certificate_file')->store('profiles/documents', 'public');
        }

        // Calcul complétion
        $data['profile_completed'] = $profile->fill($data)->completionPercentage() >= 80;

        $profile->update($data);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Supprimer le compte
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

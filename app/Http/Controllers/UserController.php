<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->latest()->get();
        $roles = Role::where('status', 1)->orderBy('title')->get();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::where('status', 1)->orderBy('title')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:50'],
            'lastname'  => ['required', 'string', 'max:50'],
            'username'  => ['required', 'string', 'max:25', 'unique:users,username'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'phone'     => ['required', 'string', 'max:19', 'unique:users,phone'],
            'role_id'   => ['required', 'exists:roles,id'],
            'password'  => ['required', 'string', 'min:8'],
            'status'    => ['nullable', 'in:0,1'],
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'username'  => $request->username,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'role_id'   => $request->role_id,
            'password'  => Hash::make($request->password),
            'status'    => $request->status ?? 1,
        ]);

        Profile::firstOrCreate(['user_id' => $user->id]);

        return redirect()->route('users.index')
            ->with('success', '✅ Utilisateur créé !');
    }

    public function show(User $user)
    {
        return redirect()->route('users.edit', $user->id);
    }

    public function edit(User $user)
    {
        $roles = Role::where('status', 1)->orderBy('title')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:50'],
            'lastname'  => ['required', 'string', 'max:50'],
            'username'  => ['required', 'string', 'max:25', 'unique:users,username,' . $user->id],
            'email'     => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'     => ['required', 'string', 'max:19', 'unique:users,phone,' . $user->id],
            'role_id'   => ['required', 'exists:roles,id'],
            'status'    => ['nullable', 'in:0,1'],
        ]);

        $user->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'username'  => $request->username,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'role_id'   => $request->role_id,
            'status'    => $request->status ?? $user->status,
        ]);

        return redirect()->route('users.index')
            ->with('success', '✅ ' . $user->firstname . ' mis à jour !');
    }

    /**
     * Validation du compte via formulaire POST classique
     */
   public function validateUser(Request $request, User $user)
{
    $user->update(['status' => 1]);
    return redirect()->route('users.index')
        ->with('success', '✅ Compte de ' . $user->firstname . ' activé !');
}

    /**
     * Attribution rapide du rôle (AJAX)
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role_id' => ['required', 'exists:roles,id']]);
        $user->update(['role_id' => $request->role_id]);

        return response()->json([
            'success' => true,
            'role'    => $user->fresh()->role->title,
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', '🗑️ Utilisateur supprimé.');
    }
}

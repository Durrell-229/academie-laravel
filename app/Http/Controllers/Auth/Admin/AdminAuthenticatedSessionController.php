<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminAuthenticatedSessionController extends Controller
{
    public function index(): View
    {
        $roles             = Role::where('status', 1)->orderBy('title')->get();
        $users             = User::with('role')->latest()->take(10)->get();
        $totalUsers        = User::count();
        $totalCours        = Course::count();
        $totalLecons       = Lesson::count();
        $totalInscriptions = Enrollment::count();

        return view('admin.dashboard', compact(
            'roles', 'users',
            'totalUsers', 'totalCours',
            'totalLecons', 'totalInscriptions'
        ));
    }

    public function create(): View
    {
        return view('auth.admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Ces identifiants ne correspondent à aucun compte administrateur.',
        ])->onlyInput('email');
    }

    public function show(Admin $admin): View
    {
        return view('admin.profiles.show', compact('admin'));
    }

    public function edit(Admin $admin): View
    {
        return view('admin.profiles.edit', compact('admin'));
    }

    public function updateProfile(Request $request, Admin $admin): RedirectResponse
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:50'],
            'lastname'  => ['required', 'string', 'max:50'],
            'email'     => ['required', 'email', 'unique:admins,email,' . $admin->id],
            'phone'     => ['nullable', 'string', 'max:19'],
        ]);

        $admin->update([
            'firstname' => $request->firstname,
            'lastname'  => $request->lastname,
            'email'     => $request->email,
            'phone'     => $request->phone,
        ]);

        if ($request->password) {
            $admin->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->back()->with('success', '✅ Profil mis à jour !');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.create');
    }
}

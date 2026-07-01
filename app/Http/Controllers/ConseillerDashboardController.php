<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ConseillerDashboardController extends Controller
{
    // ── Dashboard ──────────────────────────────────────────────
    public function index()
    {
        $roleApprenant   = Role::where('slug', 'apprenant')->first();
        $roleProfesseur  = Role::where('slug', 'professeur')->first();

        $totalApprenants  = $roleApprenant  ? User::where('role_id', $roleApprenant->id)->count()  : 0;
        $totalProfesseurs = $roleProfesseur ? User::where('role_id', $roleProfesseur->id)->count() : 0;
        $totalCours       = Course::where('status', 1)->count();
        $totalInscriptions = Enrollment::count();

        $recentApprenants = $roleApprenant
            ? User::where('role_id', $roleApprenant->id)
                ->with(['profile', 'enrollments'])
                ->latest()->take(6)->get()
            : collect();

        $recentCours = Course::where('status', 1)
            ->with('category')
            ->latest()->take(5)->get();

        return view('conseiller.dashboard', compact(
            'totalApprenants', 'totalProfesseurs',
            'totalCours', 'totalInscriptions',
            'recentApprenants', 'recentCours'
        ));
    }

    // ── Tous les apprenants ────────────────────────────────────
    public function apprenants()
    {
        $roleApprenant = Role::where('slug', 'apprenant')->first();
        $apprenants = $roleApprenant
            ? User::where('role_id', $roleApprenant->id)
                ->with(['profile', 'enrollments'])
                ->latest()->get()
            : collect();

        return view('conseiller.apprenants', compact('apprenants'));
    }

    // ── Progression ────────────────────────────────────────────
    public function progression()
    {
        $enrollments = Enrollment::with(['user', 'course'])->latest()->get();
        return view('conseiller.pages', compact('enrollments'));
    }

    // ── Cours disponibles ──────────────────────────────────────
    public function cours()
    {
        $cours = Course::where('status', 1)->with(['category', 'details'])->latest()->get();
        return view('conseiller.pages', compact('cours'));
    }

    // ── Évaluations ────────────────────────────────────────────
    public function evaluations()
    {
        return view('conseiller.pages');
    }

    // ── Contacts ───────────────────────────────────────────────
    public function contacts()
    {
        $roleApprenant  = Role::where('slug', 'apprenant')->first();
        $roleProfesseur = Role::where('slug', 'professeur')->first();

        $apprenants   = $roleApprenant  ? User::where('role_id', $roleApprenant->id)->get()  : collect();
        $professeurs  = $roleProfesseur ? User::where('role_id', $roleProfesseur->id)->get() : collect();

        return view('conseiller.pages', compact('apprenants', 'professeurs'));
    }
}

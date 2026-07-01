<?php

// ════════════════════════════════════════════════════════════════
//  REMPLACEZ les routes de dashboard dans routes/web.php
//  par ce bloc complet (5 acteurs)
// ════════════════════════════════════════════════════════════════

use Illuminate\Support\Facades\Auth;

// ── Dashboard Apprenant ──────────────────────────────────────
Route::get('/dashboard', function () {
    return view('student.dashboard');
})->middleware(['auth'])->name('dashboard');

// ── Dashboard Professeur / Tuteur ────────────────────────────
Route::get('/dashboard/instructor', function () {
    $role = Auth::user()->role?->slug ?? '';
    $allowed = ['instructor', 'professeur', 'teacher', 'professor'];
    if (!in_array($role, $allowed, true)) abort(403);
    return view('instructor.dashboard');  // sera amélioré avec InstructeurDashboardController
})->middleware(['auth'])->name('instructor.dashboard');

// ── Dashboard Conseiller pédagogique ────────────────────────
Route::get('/dashboard/conseiller', function () {
    $role = Auth::user()->role?->slug ?? '';
    $allowed = ['conseiller-pedagogique', 'support-staff', 'counselor', 'advisor', 'conseiller'];
    if (!in_array($role, $allowed, true)) abort(403);
    return view('conseiller.dashboard');
})->middleware(['auth'])->name('conseiller.dashboard');

// ── Dashboard Inspecteur ─────────────────────────────────────
Route::get('/dashboard/inspecteur', function () {
    $role = Auth::user()->role?->slug ?? '';
    $allowed = ['inspecteur', 'inspector'];
    if (!in_array($role, $allowed, true)) abort(403);
    return view('inspecteur.dashboard');
})->middleware(['auth'])->name('inspecteur.dashboard');

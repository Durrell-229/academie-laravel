<?php

// ════════════════════════════════════════════════════════════
//  REMPLACEZ la route /dashboard/conseiller dans web.php
//  par ce bloc complet
// ════════════════════════════════════════════════════════════

use App\Http\Controllers\ConseillerDashboardController;

Route::prefix('conseiller')->middleware(['auth'])->group(function () {
    Route::get('/',              [ConseillerDashboardController::class, 'index'])->name('conseiller.dashboard');
    Route::get('/apprenants',    [ConseillerDashboardController::class, 'apprenants'])->name('conseiller.apprenants');
    Route::get('/progression',   [ConseillerDashboardController::class, 'progression'])->name('conseiller.progression');
    Route::get('/cours',         [ConseillerDashboardController::class, 'cours'])->name('conseiller.cours');
    Route::get('/evaluations',   [ConseillerDashboardController::class, 'evaluations'])->name('conseiller.evaluations');
    Route::get('/contacts',      [ConseillerDashboardController::class, 'contacts'])->name('conseiller.contacts');
});

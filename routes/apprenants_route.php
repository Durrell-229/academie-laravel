<?php

// ════════════════════════════════════════════════════════════
//  AJOUTEZ cette ligne dans le bloc Route::prefix('instructor')
//  de votre routes/web.php
// ════════════════════════════════════════════════════════════

Route::get('/apprenants', [InstructeurDashboardController::class, 'apprenants'])
    ->name('instructor.apprenants');

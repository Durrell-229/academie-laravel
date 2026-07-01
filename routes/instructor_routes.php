<?php

// ════════════════════════════════════════════════════════════
//  AJOUTEZ CES ROUTES DANS routes/web.php
//  Remplacez les deux routes dashboard/instructor existantes
//  par ce bloc complet
// ════════════════════════════════════════════════════════════

use App\Http\Controllers\InstructeurDashboardController;

// Dashboard principal professeur
Route::get('/dashboard/instructor', [InstructeurDashboardController::class, 'index'])
    ->middleware(['auth', 'instructor'])
    ->name('instructor.dashboard');

// Gestion des cours (professeur)
Route::prefix('instructor')->middleware(['auth', 'instructor'])->group(function () {

    // Cours
    Route::get('/courses',              [InstructeurDashboardController::class, 'courseIndex'])->name('instructor.courses.index');
    Route::get('/courses/create',       [InstructeurDashboardController::class, 'courseCreate'])->name('instructor.courses.create');
    Route::post('/courses',             [InstructeurDashboardController::class, 'courseStore'])->name('instructor.courses.store');
    Route::get('/courses/{course}/edit',[InstructeurDashboardController::class, 'courseEdit'])->name('instructor.courses.edit');
    Route::put('/courses/{course}',     [InstructeurDashboardController::class, 'courseUpdate'])->name('instructor.courses.update');
    Route::delete('/courses/{course}',  [InstructeurDashboardController::class, 'courseDestroy'])->name('instructor.courses.destroy');

    // Leçons
    Route::get('/lessons',              [InstructeurDashboardController::class, 'lessonIndex'])->name('instructor.lessons.index');
    Route::get('/lessons/create',       [InstructeurDashboardController::class, 'lessonCreate'])->name('instructor.lessons.create');
    Route::post('/lessons',             [InstructeurDashboardController::class, 'lessonStore'])->name('instructor.lessons.store');
    Route::get('/lessons/{lesson}/edit',[InstructeurDashboardController::class, 'lessonEdit'])->name('instructor.lessons.edit');
    Route::put('/lessons/{lesson}',     [InstructeurDashboardController::class, 'lessonUpdate'])->name('instructor.lessons.update');
    Route::delete('/lessons/{lesson}',  [InstructeurDashboardController::class, 'lessonDestroy'])->name('instructor.lessons.destroy');

});

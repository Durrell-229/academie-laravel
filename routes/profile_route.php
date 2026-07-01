<?php

// ════════════════════════════════════════════════════════════
//  AJOUTEZ cette route dans le bloc middleware('auth') de web.php
// ════════════════════════════════════════════════════════════

Route::post('/profile/full', [ProfileController::class, 'updateFull'])
    ->middleware('auth')
    ->name('profile.updateFull');

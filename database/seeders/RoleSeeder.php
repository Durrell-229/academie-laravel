<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Rôles pour les 5 acteurs de la plateforme LMS
     */
    public function run(): void
    {
        $roles = [
            // ── Administrateurs ──────────────────────────────
            ['title' => 'Super Admin',    'status' => 1],
            ['title' => 'Administrator',  'status' => 1],

            // ── Corps enseignant ─────────────────────────────
            ['title' => 'Instructor',     'status' => 1],  // Professeur / Tuteur
            ['title' => 'Professeur',     'status' => 1],  // alias français

            // ── Encadrement pédagogique ──────────────────────
            ['title' => 'Conseiller-Pedagogique', 'status' => 1],  // Conseiller
            ['title' => 'Inspecteur',     'status' => 1],  // Inspecteur

            // ── Apprenants ───────────────────────────────────
            ['title' => 'Student',        'status' => 1],  // Étudiant scolaire
            ['title' => 'Learner',        'status' => 1],  // Apprenant adulte
            ['title' => 'Subscriber',     'status' => 1],  // Abonné (défaut)

            // ── Autres ───────────────────────────────────────
            ['title' => 'Support Staff',  'status' => 1],
            ['title' => 'Moderator',      'status' => 1],
        ];

        foreach ($roles as $role) {
            $role['slug'] = Str::slug($role['title']);
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}

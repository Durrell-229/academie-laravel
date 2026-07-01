<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Ajoute le profil pédagogique à la table profiles.
     *
     * type_apprenant :
     *   scolaire      → maternelle, primaire, collège, lycée  → génère un BULLETIN
     *   non_scolaire  → adulte, université, formation pro     → génère un CERTIFICAT
     *
     * niveau_scolaire : le niveau précis de l'apprenant
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Type principal : scolaire ou non_scolaire
            $table->enum('type_apprenant', ['scolaire', 'non_scolaire'])
                  ->default('non_scolaire')
                  ->after('user_id');

            // Niveau précis
            $table->enum('niveau_scolaire', [
                // Scolaires
                'maternelle',
                'primaire',
                'college',
                'lycee',
                // Non scolaires
                'universite',
                'formation_professionnelle',
                'adulte',
                'autre',
            ])->nullable()->after('type_apprenant');

            // Classe (ex: 6ème, Terminale, Licence 1…)
            $table->string('classe', 50)->nullable()->after('niveau_scolaire');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn(['type_apprenant', 'niveau_scolaire', 'classe']);
        });
    }
};

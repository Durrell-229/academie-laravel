<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kkiapay_payments', function (Blueprint $table) {
            $table->id();

            // Référence interne unique générée par notre système (UUID)
            $table->uuid('reference_interne')->unique();

            // Référence renvoyée par Kkiapay une fois la transaction initiée
            $table->string('reference_kkiapay')->nullable()->unique();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();

            $table->decimal('montant', 10, 2);
            $table->string('moyen_paiement')->nullable(); // MOBILE_MONEY, CARD, WALLET
            $table->enum('statut', ['EN_ATTENTE', 'REUSSI', 'ECHOUE', 'ANNULE'])
                  ->default('EN_ATTENTE');

            $table->string('numero_telephone')->nullable();
            $table->decimal('frais', 10, 2)->default(0);

            $table->timestamp('date_confirmation')->nullable();
            $table->json('donnees_brutes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kkiapay_payments');
    }
};
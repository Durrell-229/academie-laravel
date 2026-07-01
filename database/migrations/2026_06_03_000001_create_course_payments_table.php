<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');

            // Montant
            $table->decimal('amount', 10, 0);
            $table->decimal('amount_super_admin', 10, 0)->default(0); // 20%
            $table->decimal('amount_professor', 10, 0)->default(0);   // 10%
            $table->decimal('amount_admin', 10, 0)->default(0);       // 70%

            // MoMo
            $table->string('payment_method')->default('momo'); // momo
            $table->string('momo_number', 20)->nullable();     // numéro utilisé
            $table->string('receipt_file')->nullable();        // capture d'écran

            // Statut
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('validated_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_payments');
    }
};

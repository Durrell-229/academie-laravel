<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {

            // ── Identité civile ──────────────────────────────────
            $table->string('nationality', 100)->nullable()->after('avatar');
            $table->string('country_of_birth', 100)->nullable()->after('nationality');
            $table->string('city_of_birth', 100)->nullable()->after('country_of_birth');
            $table->string('country_of_residence', 100)->nullable()->after('city_of_birth');
            $table->string('city_of_residence', 100)->nullable()->after('country_of_residence');
            $table->text('address')->nullable()->after('city_of_residence');

            // ── Acte de naissance ────────────────────────────────
            $table->string('birth_certificate_number', 100)->nullable()->after('address');
            $table->string('birth_certificate_center', 150)->nullable()->after('birth_certificate_number');
            $table->date('birth_certificate_date')->nullable()->after('birth_certificate_center');
            $table->string('birth_certificate_country', 100)->nullable()->after('birth_certificate_date');

            // ── Situation familiale ──────────────────────────────
            $table->integer('children_count')->default(0)->after('marital');
            $table->string('father_firstname', 100)->nullable()->after('children_count');
            $table->string('father_lastname', 100)->nullable()->after('father_firstname');
            $table->string('mother_firstname', 100)->nullable()->after('father_lastname');
            $table->string('mother_lastname', 100)->nullable()->after('mother_firstname');
            $table->string('emergency_contact_name', 150)->nullable()->after('mother_lastname');
            $table->string('emergency_contact_phone', 30)->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relation', 100)->nullable()->after('emergency_contact_phone');

            // ── Pièce d'identité ─────────────────────────────────
            $table->enum('id_type', ['cni', 'passport', 'permis', 'autre'])->nullable()->after('emergency_contact_relation');
            $table->string('id_number', 100)->nullable()->after('id_type');
            $table->date('id_expiry_date')->nullable()->after('id_number');
            $table->string('id_issuing_country', 100)->nullable()->after('id_expiry_date');

            // ── Situation professionnelle ────────────────────────
            $table->string('occupation', 150)->nullable()->after('id_issuing_country');
            $table->string('employer', 150)->nullable()->after('occupation');
            $table->string('professional_email', 150)->nullable()->after('employer');

            // ── Réseaux sociaux ──────────────────────────────────
            $table->string('facebook', 255)->nullable()->after('professional_email');
            $table->string('linkedin', 255)->nullable()->after('facebook');
            $table->string('twitter', 255)->nullable()->after('linkedin');
            $table->string('website', 255)->nullable()->after('twitter');

            // ── Documents uploadés ───────────────────────────────
            $table->string('id_document', 255)->nullable()->after('website');
            $table->string('birth_certificate_file', 255)->nullable()->after('id_document');

            // ── Profil complété ──────────────────────────────────
            $table->boolean('profile_completed')->default(false)->after('birth_certificate_file');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'nationality', 'country_of_birth', 'city_of_birth',
                'country_of_residence', 'city_of_residence', 'address',
                'birth_certificate_number', 'birth_certificate_center',
                'birth_certificate_date', 'birth_certificate_country',
                'children_count', 'father_firstname', 'father_lastname',
                'mother_firstname', 'mother_lastname',
                'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
                'id_type', 'id_number', 'id_expiry_date', 'id_issuing_country',
                'occupation', 'employer', 'professional_email',
                'facebook', 'linkedin', 'twitter', 'website',
                'id_document', 'birth_certificate_file', 'profile_completed',
            ]);
        });
    }
};

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        // Pédagogique
        'type_apprenant', 'niveau_scolaire', 'classe',
        // Photo
        'avatar',
        // Biographie
        'biography',
        // Identité
        'date_of_birth', 'gender', 'religion', 'marital',
        'nationality', 'country_of_birth', 'city_of_birth',
        'country_of_residence', 'city_of_residence', 'address',
        // Acte de naissance
        'birth_certificate_number', 'birth_certificate_center',
        'birth_certificate_date', 'birth_certificate_country',
        // Famille
        'children_count', 'father_firstname', 'father_lastname',
        'mother_firstname', 'mother_lastname',
        'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
        // Pièce d'identité
        'id_type', 'id_number', 'id_expiry_date', 'id_issuing_country',
        // Profession
        'occupation', 'employer', 'professional_email',
        // Réseaux sociaux
        'facebook', 'linkedin', 'twitter', 'website',
        // Documents
        'id_document', 'birth_certificate_file',
        // Statut
        'profile_completed',
    ];

    protected $casts = [
        'date_of_birth'          => 'date',
        'birth_certificate_date' => 'date',
        'id_expiry_date'         => 'date',
        'profile_completed'      => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calcule le pourcentage de complétion du profil
     */
    public function completionPercentage(): int
    {
        $fields = [
            'avatar', 'biography', 'date_of_birth', 'gender', 'nationality',
            'country_of_birth', 'city_of_birth', 'country_of_residence',
            'city_of_residence', 'address', 'marital', 'father_firstname',
            'mother_firstname', 'id_type', 'id_number', 'occupation',
        ];

        $filled = collect($fields)->filter(fn($f) => !empty($this->$f))->count();
        return (int) round(($filled / count($fields)) * 100);
    }
}

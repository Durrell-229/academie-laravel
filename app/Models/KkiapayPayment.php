<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class KkiapayPayment extends Model
{
    use HasFactory;

    protected $table = 'kkiapay_payments';

    protected $fillable = [
        'reference_interne',
        'reference_kkiapay',
        'user_id',
        'course_id',
        'montant',
        'moyen_paiement',
        'statut',
        'numero_telephone',
        'frais',
        'date_confirmation',
        'donnees_brutes',
    ];

    protected $casts = [
        'donnees_brutes' => 'array',
        'date_confirmation' => 'datetime',
        'montant' => 'decimal:2',
        'frais' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paiement) {
            if (empty($paiement->reference_interne)) {
                $paiement->reference_interne = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function marquerReussi(array $transactionData): void
    {
        $this->update([
            'statut' => 'REUSSI',
            'reference_kkiapay' => $transactionData['transactionId'] ?? $this->reference_kkiapay,
            'moyen_paiement' => $transactionData['source'] ?? $transactionData['method'] ?? null,
            'numero_telephone' => $transactionData['account'] ?? null,
            'frais' => $transactionData['fees'] ?? 0,
            'donnees_brutes' => $transactionData,
            'date_confirmation' => Carbon::now(),
        ]);
    }

    public function marquerEchoue(?array $transactionData = null): void
    {
        $this->update([
            'statut' => 'ECHOUE',
            'donnees_brutes' => $transactionData,
        ]);
    }
}
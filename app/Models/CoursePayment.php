<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoursePayment extends Model
{
    protected $table = 'course_payments';

    protected $fillable = [
        'user_id', 'course_id',
        'amount', 'amount_super_admin', 'amount_professor', 'amount_admin',
        'payment_method', 'momo_number', 'receipt_file',
        'status', 'admin_note', 'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function user()    { return $this->belongsTo(User::class); }
    public function course()  { return $this->belongsTo(Course::class); }

    /**
     * Calcule la répartition automatique
     */
    public static function calculateSplit(float $amount): array
    {
        $superAdmin = round($amount * 0.20);
        $professor  = round($amount * 0.10);
        $admin      = $amount - $superAdmin - $professor;

        return [
            'amount_super_admin' => $superAdmin,
            'amount_professor'   => $professor,
            'amount_admin'       => $admin,
        ];
    }
}

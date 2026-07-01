<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveSession extends Model
{
    protected $table = 'live_sessions';

    protected $fillable = [
        'course_id', 'user_id', 'title',
        'platform', 'link', 'scheduled_at', 'is_active',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_active'    => 'boolean',
    ];

    public function course()     { return $this->belongsTo(Course::class); }
    public function professor()  { return $this->belongsTo(User::class, 'user_id'); }
}

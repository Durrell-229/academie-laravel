<?php

namespace App\Models;

use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Interest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 'user_id', 'topic_id' ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function topic(): BelongsTo {
        return $this->belongsTo(Topic::class);
    }
}

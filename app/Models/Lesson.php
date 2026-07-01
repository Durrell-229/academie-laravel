<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Lesson extends Model {
    use HasFactory;
    protected $fillable = [
        'course_id', 'title', 'description', 'highlights',
        'duration', 'thumbnail', 'slug', 'status',
        'youtube_link', 'video_file', 'pdf_file', 'external_link'
    ];
    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }
}
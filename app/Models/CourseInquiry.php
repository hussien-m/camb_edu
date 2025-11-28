<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseInquiry extends Model
{
    protected $fillable = [
        'course_id',
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}

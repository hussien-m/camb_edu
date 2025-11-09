<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'country',
        'title',
        'story',
        'image',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Scope a query to only include published stories.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}

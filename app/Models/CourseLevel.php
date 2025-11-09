<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseLevel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    /**
     * Get the courses for the level.
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class, 'level_id');
    }
}

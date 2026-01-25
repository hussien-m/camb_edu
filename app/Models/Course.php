<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'title',
        'slug',
        'category_id',
        'level_id',
        'short_description',
        'description',
        'duration',
        'mode',
        'fee',
        'image',
        'is_featured',
        'status',
        'content_disabled',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'content_disabled' => 'boolean',
        'fee' => 'decimal:2',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get the category that owns the course.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
    }

    /**
     * Get the level that owns the course.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(CourseLevel::class, 'level_id');
    }

    /**
     * Scope a query to only include active courses.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured courses.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Exam System Relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot('status', 'enrolled_at', 'completed_at', 'progress')
            ->withTimestamps();
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'position',
        'image',
        'html_content',
        'link',
        'open_in_new_tab',
        'order',
        'is_active',
        'start_date',
        'end_date',
        'views_count',
        'clicks_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'order' => 'integer',
        'views_count' => 'integer',
        'clicks_count' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Scope a query to only include active ads.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            });
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to filter by position.
     */
    public function scopeAtPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    /**
     * Scope a query to order ads by order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at', 'desc');
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment clicks count
     */
    public function incrementClicks()
    {
        $this->increment('clicks_count');
    }
}

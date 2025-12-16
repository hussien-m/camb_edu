<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'page_type',
        'viewable_id',
        'viewable_type',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'device_type',
        'user_id',
    ];

    /**
     * Get the owning viewable model (Course, Page, etc.)
     */
    public function viewable()
    {
        return $this->morphTo();
    }

    /**
     * Get views count by date range
     */
    public static function getViewsCount($startDate = null, $endDate = null)
    {
        $query = static::query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query->count();
    }

    /**
     * Get unique visitors count
     */
    public static function getUniqueVisitors($startDate = null, $endDate = null)
    {
        $query = static::query();

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('created_at', '<=', $endDate);
        }

        return $query->distinct('ip_address')->count('ip_address');
    }

    /**
     * Get most viewed pages
     */
    public static function getMostViewed($limit = 10, $startDate = null)
    {
        $query = static::query()
            ->select('viewable_id', 'viewable_type', \DB::raw('count(*) as views_count'))
            ->whereNotNull('viewable_id')
            ->whereNotNull('viewable_type')
            ->groupBy('viewable_id', 'viewable_type')
            ->orderBy('views_count', 'desc')
            ->limit($limit);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        return $query->get();
    }

    /**
     * Get views by country
     */
    public static function getViewsByCountry($limit = 10, $startDate = null)
    {
        $query = static::query()
            ->select('country', \DB::raw('count(*) as views_count'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('views_count', 'desc')
            ->limit($limit);

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        return $query->get();
    }

    /**
     * Get views by device type
     */
    public static function getViewsByDevice($startDate = null)
    {
        $query = static::query()
            ->select('device_type', \DB::raw('count(*) as views_count'))
            ->whereNotNull('device_type')
            ->groupBy('device_type');

        if ($startDate) {
            $query->where('created_at', '>=', $startDate);
        }

        return $query->get();
    }

    /**
     * Get daily views for chart
     */
    public static function getDailyViews($days = 30)
    {
        return static::query()
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as views'))
            ->where('created_at', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }
}

<?php

namespace App\Services\Admin;

use App\Models\ActivityLog;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log an activity
     */
    public static function log(string $action, string $model, $modelId = null, array $data = []): void
    {
        try {
            ActivityLog::create([
                'admin_id' => Auth::guard('admin')->id(),
                'action' => $action,
                'model' => $model,
                'model_id' => $modelId,
                'data' => $data,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the main operation
            \Log::warning('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 20)
    {
        return ActivityLog::with('admin')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get activities by model
     */
    public function getActivitiesByModel(string $model, $modelId = null, int $limit = 20)
    {
        $query = ActivityLog::with('admin')
            ->where('model', $model);

        if ($modelId) {
            $query->where('model_id', $modelId);
        }

        return $query->latest()->limit($limit)->get();
    }
}


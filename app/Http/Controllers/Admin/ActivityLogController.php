<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Display activity log
     */
    public function index(Request $request): View
    {
        $activities = $this->activityLogService->getRecentActivities(50);

        // Filter by model if provided
        if ($request->filled('model')) {
            $activities = $this->activityLogService->getActivitiesByModel($request->model, null, 50);
        }

        return view('admin.activity-log.index', compact('activities'));
    }
}


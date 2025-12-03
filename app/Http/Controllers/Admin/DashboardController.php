<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Banner;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use App\Models\Page;
use App\Models\Student;
use App\Models\SuccessStory;
use App\Services\Admin\ActivityLogService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $stats = [
            'courses' => Course::count(),
            'categories' => CourseCategory::count(),
            'levels' => CourseLevel::count(),
            'pages' => Page::count(),
            'stories' => SuccessStory::count(),
            'unread_messages' => Contact::where('is_read', false)->count(),
            'banners' => Banner::count(),
            'students' => Student::count(),
            'active_students' => Student::where('status', 'active')->count(),
            'pending_students' => Student::where('status', 'pending')->count(),
        ];

        // Chart data - Courses by status
        $coursesByStatus = [
            'active' => Course::where('status', 'active')->count(),
            'inactive' => Course::where('status', 'inactive')->count(),
        ];

        // Chart data - Students by status
        $studentsByStatus = [
            'active' => Student::where('status', 'active')->count(),
            'pending' => Student::where('status', 'pending')->count(),
            'suspended' => Student::where('status', 'suspended')->count(),
        ];

        // Recent activities
        $activityLogService = new ActivityLogService();
        $recentActivities = $activityLogService->getRecentActivities(10);

        $recentCourses = Course::with(['category', 'level'])
            ->latest()
            ->limit(5)
            ->get();

        $recentMessages = Contact::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentCourses',
            'recentMessages',
            'coursesByStatus',
            'studentsByStatus',
            'recentActivities'
        ));
    }
}

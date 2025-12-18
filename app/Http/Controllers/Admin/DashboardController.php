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
use App\Models\PageView;
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

        // Analytics Data (Last 7 days)
        $startDate = now()->subDays(7);

        // Page views stats
        $analyticsStats = [
            'total_views' => PageView::where('created_at', '>=', $startDate)->count(),
            'unique_visitors' => PageView::where('created_at', '>=', $startDate)
                ->distinct('ip_address')->count('ip_address'),
        ];

        // Top 5 Cities
        $topCities = PageView::query()
            ->select('city', 'country', \DB::raw('count(*) as views'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('city')
            ->where('city', '!=', 'Unknown')
            ->where('city', '!=', 'Localhost')
            ->groupBy('city', 'country')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        // Browser distribution
        $browserStats = PageView::query()
            ->select('browser', \DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('browser')
            ->where('browser', '!=', 'Unknown')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->get();

        // OS distribution
        $osStats = PageView::query()
            ->select('os', \DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('os')
            ->where('os', '!=', 'Unknown')
            ->groupBy('os')
            ->orderBy('count', 'desc')
            ->get();

        // Average time on page
        $avgTimeOnPage = PageView::query()
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('time_on_page')
            ->where('time_on_page', '>', 0)
            ->avg('time_on_page');

        // Top Countries
        $topCountries = PageView::query()
            ->select('country', \DB::raw('count(*) as views'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('country')
            ->where('country', '!=', 'Unknown')
            ->where('country', '!=', 'Local')
            ->groupBy('country')
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentCourses',
            'recentMessages',
            'coursesByStatus',
            'studentsByStatus',
            'recentActivities',
            'analyticsStats',
            'topCities',
            'browserStats',
            'osStats',
            'avgTimeOnPage',
            'topCountries'
        ));
    }
}

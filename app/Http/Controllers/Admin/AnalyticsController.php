<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\CourseInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    /**
     * Display analytics dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', '30'); // Default 30 days
        $startDate = now()->subDays($period);

        // Overview Stats
        $stats = [
            'total_views' => PageView::getViewsCount($startDate),
            'unique_visitors' => PageView::getUniqueVisitors($startDate),
            'total_students' => Student::where('created_at', '>=', $startDate)->count(),
            'total_enrollments' => Enrollment::where('created_at', '>=', $startDate)->count(),
            'total_inquiries' => CourseInquiry::where('created_at', '>=', $startDate)->count(),
        ];

        // Previous period comparison
        $previousStartDate = now()->subDays($period * 2);
        $previousEndDate = $startDate;

        $previousStats = [
            'total_views' => PageView::getViewsCount($previousStartDate, $previousEndDate),
            'unique_visitors' => PageView::getUniqueVisitors($previousStartDate, $previousEndDate),
            'total_students' => Student::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count(),
            'total_enrollments' => Enrollment::whereBetween('created_at', [$previousStartDate, $previousEndDate])->count(),
        ];

        // Calculate percentage changes
        $changes = [];
        foreach ($stats as $key => $value) {
            if ($key === 'total_inquiries') continue;
            $previousValue = $previousStats[$key] ?? 1;
            $changes[$key] = $previousValue > 0
                ? round((($value - $previousValue) / $previousValue) * 100, 1)
                : 0;
        }

        // Most Viewed Courses
        $mostViewedRaw = PageView::getMostViewed(10, $startDate);
        $mostViewedCourses = [];

        foreach ($mostViewedRaw as $view) {
            if ($view->viewable_type === 'App\Models\Course' && $view->viewable_id) {
                $course = Course::find($view->viewable_id);
                if ($course) {
                    $mostViewedCourses[] = [
                        'course' => $course,
                        'views' => $view->views_count,
                    ];
                }
            }
        }

        // Views by Country
        $viewsByCountry = PageView::getViewsByCountry(10, $startDate);

        // Views by Device
        $viewsByDevice = PageView::getViewsByDevice($startDate);

        // Daily views chart data
        $dailyViews = PageView::getDailyViews($period);
        $chartLabels = $dailyViews->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('M d');
        });
        $chartData = $dailyViews->pluck('views');

        // Recent Activities
        $recentStudents = Student::latest()->take(5)->get();
        $recentEnrollments = Enrollment::with(['student', 'course'])->latest()->take(5)->get();
        $recentInquiries = CourseInquiry::with('course')->latest()->take(5)->get();

        // Page Types Distribution
        $pageTypeStats = PageView::query()
            ->select('page_type', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('page_type')
            ->groupBy('page_type')
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'changes',
            'mostViewedCourses',
            'viewsByCountry',
            'viewsByDevice',
            'chartLabels',
            'chartData',
            'recentStudents',
            'recentEnrollments',
            'recentInquiries',
            'pageTypeStats',
            'period'
        ));
    }

    /**
     * Get real-time stats (for AJAX)
     */
    public function realtime()
    {
        // Views in last 5 minutes
        $realtimeViews = PageView::where('created_at', '>=', now()->subMinutes(5))->count();

        // Active users (views in last 10 minutes)
        $activeUsers = PageView::where('created_at', '>=', now()->subMinutes(10))
            ->distinct('ip_address')
            ->count('ip_address');

        return response()->json([
            'realtime_views' => $realtimeViews,
            'active_users' => $activeUsers,
        ]);
    }
}

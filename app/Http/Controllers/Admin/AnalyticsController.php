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

        // Views by Country/City
        $viewsByCountry = PageView::getViewsByCountry(10, $startDate);

        // Top Cities
        $topCities = PageView::query()
            ->select('country', 'city', DB::raw('count(*) as views'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('city')
            ->where('city', '!=', 'Unknown')
            ->groupBy('country', 'city')
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        // Top ISPs
        $topISPs = PageView::query()
            ->select('isp', DB::raw('count(*) as views'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('isp')
            ->where('isp', '!=', 'Unknown')
            ->groupBy('isp')
            ->orderBy('views', 'desc')
            ->take(10)
            ->get();

        // Browser Stats
        $browserStats = PageView::query()
            ->select('browser', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->get();

        // OS Stats
        $osStats = PageView::query()
            ->select('os', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('os')
            ->groupBy('os')
            ->orderBy('count', 'desc')
            ->get();

        // Search Queries (if any)
        $searchQueries = PageView::query()
            ->select('search_query', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('search_query')
            ->where('search_query', '!=', '')
            ->groupBy('search_query')
            ->orderBy('count', 'desc')
            ->take(20)
            ->get();

        // Average time on page (in seconds)
        $avgTimeOnPage = PageView::query()
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('time_on_page')
            ->where('time_on_page', '>', 0)
            ->avg('time_on_page');

        // Timezone Distribution
        $timezoneStats = PageView::query()
            ->select('timezone', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('timezone')
            ->where('timezone', '!=', 'Unknown')
            ->groupBy('timezone')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Region Distribution
        $regionStats = PageView::query()
            ->select('country', 'region', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('region')
            ->where('region', '!=', 'Unknown')
            ->groupBy('country', 'region')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Hourly Traffic Pattern
        $hourlyTraffic = PageView::query()
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as views'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->pluck('views', 'hour');

        // Complete hourly data (0-23 hours)
        $hourlyData = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyData[] = $hourlyTraffic->get($i, 0);
        }

        // Recent Visitors Detail (last 20) - Exclude admin pages
        $recentVisitors = PageView::query()
            ->select('ip_address', 'country', 'city', 'region', 'timezone', 'browser', 'os', 'device_type', 'created_at', 'url')
            ->where('created_at', '>=', $startDate)
            ->where('url', 'NOT LIKE', '%/admin%')
            ->whereNotNull('country')
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        // Top Referring URLs
        $topReferrers = PageView::query()
            ->select('referer', DB::raw('count(*) as count'))
            ->where('created_at', '>=', $startDate)
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->where('referer', 'NOT LIKE', '%' . request()->getHost() . '%')
            ->groupBy('referer')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        // Traffic Sources Summary
        $trafficSources = [
            'direct' => PageView::query()
                ->where('created_at', '>=', $startDate)
                ->where(function($q) {
                    $q->whereNull('referer')->orWhere('referer', '');
                })
                ->count(),
            'internal' => PageView::query()
                ->where('created_at', '>=', $startDate)
                ->whereNotNull('referer')
                ->where('referer', 'LIKE', '%' . request()->getHost() . '%')
                ->count(),
            'external' => PageView::query()
                ->where('created_at', '>=', $startDate)
                ->whereNotNull('referer')
                ->where('referer', '!=', '')
                ->where('referer', 'NOT LIKE', '%' . request()->getHost() . '%')
                ->count(),
        ];

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
            'topCities',
            'topISPs',
            'browserStats',
            'osStats',
            'searchQueries',
            'avgTimeOnPage',
            'timezoneStats',
            'regionStats',
            'hourlyData',
            'recentVisitors',
            'topReferrers',
            'trafficSources',
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

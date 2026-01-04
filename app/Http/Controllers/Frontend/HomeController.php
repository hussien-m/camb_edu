<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Banner;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use App\Models\Feature;
use App\Models\Page;
use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Cache homepage data for 1 hour (3600 seconds)
        $banners = Cache::remember('home_banners', 3600, function () {
            return Banner::where('is_active', true)
                ->orderBy('order')
                ->get();
        });

        $categories = Cache::remember('home_categories', 3600, function () {
            return CourseCategory::all();
        });

        $levels = Cache::remember('home_levels', 3600, function () {
            return CourseLevel::orderBy('sort_order')->get();
        });

        $featuredCourses = Cache::remember('home_featured_courses', 1800, function () {
            return Course::with(['category', 'level'])
                ->where('status', 'active')
                ->where('is_featured', true)
                ->limit(8)
                ->get();
        });

        $latestCourses = Cache::remember('home_latest_courses', 1800, function () {
            return Course::with(['category', 'level'])
                ->where('status', 'active')
                ->latest()
                ->limit(4)
                ->get();
        });

        $successStories = Cache::remember('home_success_stories', 3600, function () {
            return SuccessStory::where('is_published', true)
                ->latest()
                ->limit(3)
                ->get();
        });

        $features = Cache::remember('home_features', 3600, function () {
            return Feature::active()->ordered()->get();
        });

        // Get active ads by position
        $topAds = Cache::remember('home_top_ads', 1800, function () {
            return Ad::where('is_active', true)
                ->where('position', 'top')
                ->where(function($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        $middleAds = Cache::remember('home_middle_ads', 1800, function () {
            return Ad::where('is_active', true)
                ->where('position', 'middle')
                ->where(function($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        $bottomAds = Cache::remember('home_bottom_ads', 1800, function () {
            return Ad::where('is_active', true)
                ->where('position', 'bottom')
                ->where(function($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        $sidebarAds = Cache::remember('home_sidebar_ads', 1800, function () {
            return Ad::where('is_active', true)
                ->where(function($q) {
                    // Support both: type='sidebar' OR position in sidebar positions
                    $q->where('type', 'sidebar')
                      ->orWhereIn('position', ['sidebar-left', 'sidebar-right']);
                })
                ->where(function($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get();
        });

        // Get popup ads with date validation
        $popupAds = Ad::where('is_active', true)
            ->where('type', 'popup')
            ->where(function($q) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now());
            })
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('frontend.home', compact(
            'banners',
            'categories',
            'levels',
            'featuredCourses',
            'latestCourses',
            'successStories',
            'features',
            'topAds',
            'middleAds',
            'bottomAds',
            'sidebarAds',
            'popupAds'
        ));
    }

    public function search(Request $request)
    {
        // Cache categories and levels with slug
        $categories = Cache::remember(
            'courses_page_categories',
            3600,
            fn() =>
            CourseCategory::select('id', 'name', 'slug')->get()
        );

        $levels = Cache::remember(
            'courses_page_levels',
            3600,
            fn() =>
            CourseLevel::select('id', 'name', 'slug', 'sort_order')->orderBy('sort_order')->get()
        );

        // Build query with eager loading
        $query = Course::with(['category:id,name,slug', 'level:id,name,slug'])
            ->select('id', 'title', 'slug', 'description', 'image', 'duration', 'is_featured', 'category_id', 'level_id', 'status')
            ->where('status', 'active');

        // Apply filters dynamically
        $query->when($request->category_id, fn($q, $categoryId) => $q->where('category_id', $categoryId))
            ->when($request->level_id, fn($q, $levelId) => $q->where('level_id', $levelId))
            ->when($request->keyword, fn($q, $keyword) => $q->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%$keyword%")
                    ->orWhere('description', 'like', "%$keyword%");
            }));

        // Debug: Log SQL query
        \Log::info('Courses Query', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings(),
            'filters' => $request->only(['category_id', 'level_id', 'keyword'])
        ]);

        // Paginate results
        $courses = $query->latest()->paginate(12);

        // AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('frontend.partials.course-grid', compact('courses'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $courses->hasMorePages(),
                'currentPage' => $courses->currentPage(),
                'total' => $courses->total(),
                'lastPage' => $courses->lastPage(),
            ]);
        }

        // Normal request
        return view('frontend.courses', compact('courses', 'categories', 'levels'));
    }

    /**
     * Filter courses by level slug (SEO-friendly URL)
     */
    public function filterByLevel(Request $request, $levelSlug)
    {
        $level = CourseLevel::where('slug', $levelSlug)->firstOrFail();

        $categories = Cache::remember(
            'courses_page_categories',
            3600,
            fn() => CourseCategory::select('id', 'name', 'slug')->get()
        );

        $levels = Cache::remember(
            'courses_page_levels',
            3600,
            fn() => CourseLevel::select('id', 'name', 'slug', 'sort_order')->orderBy('sort_order')->get()
        );

        $courses = Course::with(['category:id,name,slug', 'level:id,name,slug'])
            ->select('id', 'title', 'slug', 'description', 'image', 'duration', 'is_featured', 'category_id', 'level_id', 'status')
            ->where('status', 'active')
            ->where('level_id', $level->id)
            ->latest()
            ->paginate(12);

        // AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('frontend.partials.course-grid', compact('courses'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $courses->hasMorePages(),
                'currentPage' => $courses->currentPage(),
                'total' => $courses->total(),
                'lastPage' => $courses->lastPage(),
            ]);
        }

        return view('frontend.courses', compact('courses', 'categories', 'levels', 'level'));
    }

    /**
     * Filter courses by category slug (SEO-friendly URL)
     */
    public function filterByCategory(Request $request, $categorySlug)
    {
        $category = CourseCategory::where('slug', $categorySlug)->firstOrFail();

        $categories = Cache::remember(
            'courses_page_categories',
            3600,
            fn() => CourseCategory::select('id', 'name', 'slug')->get()
        );

        $levels = Cache::remember(
            'courses_page_levels',
            3600,
            fn() => CourseLevel::select('id', 'name', 'slug', 'sort_order')->orderBy('sort_order')->get()
        );

        $courses = Course::with(['category:id,name,slug', 'level:id,name,slug'])
            ->select('id', 'title', 'slug', 'description', 'image', 'duration', 'is_featured', 'category_id', 'level_id', 'status')
            ->where('status', 'active')
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        // AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('frontend.partials.course-grid', compact('courses'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $courses->hasMorePages(),
                'currentPage' => $courses->currentPage(),
                'total' => $courses->total(),
                'lastPage' => $courses->lastPage(),
            ]);
        }

        return view('frontend.courses', compact('courses', 'categories', 'levels', 'category'));
    }

    /**
     * Filter courses by category AND level slug (SEO-friendly URL)
     */
    public function filterByCategoryAndLevel(Request $request, $categorySlug, $levelSlug)
    {
        $category = CourseCategory::where('slug', $categorySlug)->firstOrFail();
        $level = CourseLevel::where('slug', $levelSlug)->firstOrFail();

        $categories = Cache::remember(
            'courses_page_categories',
            3600,
            fn() => CourseCategory::select('id', 'name', 'slug')->get()
        );

        $levels = Cache::remember(
            'courses_page_levels',
            3600,
            fn() => CourseLevel::select('id', 'name', 'slug', 'sort_order')->orderBy('sort_order')->get()
        );

        $courses = Course::with(['category:id,name,slug', 'level:id,name,slug'])
            ->select('id', 'title', 'slug', 'description', 'image', 'duration', 'is_featured', 'category_id', 'level_id', 'status')
            ->where('status', 'active')
            ->where('category_id', $category->id)
            ->where('level_id', $level->id)
            ->latest()
            ->paginate(12);

        // AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('frontend.partials.course-grid', compact('courses'))->render();
            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $courses->hasMorePages(),
                'currentPage' => $courses->currentPage(),
                'total' => $courses->total(),
                'lastPage' => $courses->lastPage(),
            ]);
        }

        return view('frontend.courses', compact('courses', 'categories', 'levels', 'category', 'level'));
    }

    public function show($categorySlug, $levelSlug, $courseSlug): View
    {
        // Find course by slug with relationships
        $course = Course::with(['category', 'level'])
            ->where('slug', $courseSlug)
            ->where('status', 'active')
            ->where(function ($query) use ($categorySlug, $levelSlug) {
                // If category slug is not 'general', verify it matches
                if ($categorySlug !== 'general') {
                    $query->whereHas('category', function ($q) use ($categorySlug) {
                        $q->where('slug', $categorySlug);
                    });
                }

                // If level slug is not 'beginner', verify it matches
                if ($levelSlug !== 'beginner') {
                    $query->whereHas('level', function ($q) use ($levelSlug) {
                        $q->where('slug', $levelSlug);
                    });
                }
            })
            ->firstOrFail();

        // Get related courses from same category
        $relatedCourses = Course::with(['category', 'level'])
            ->where('status', 'active')
            ->where('id', '!=', $course->id)
            ->where('category_id', $course->category_id)
            ->limit(3)
            ->get();

        return view('frontend.course-detail', compact('course', 'relatedCourses'));
    }

    public function successStories()
    {
        $stories = SuccessStory::where('is_published', true)
            ->latest()
            ->paginate(9);

        return view('frontend.success-stories', compact('stories'));
    }

    /**
     * Display a single page by slug
     */
    public function showPage($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('frontend.page', compact('page'));
    }
}

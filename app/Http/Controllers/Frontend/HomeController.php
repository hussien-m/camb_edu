<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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

        return view('frontend.home', compact(
            'banners',
            'categories',
            'levels',
            'featuredCourses',
            'latestCourses',
            'successStories',
            'features'
        ));
    }

    public function search(Request $request)
    {
        // Cache categories and levels (don't change often)
        $categories = Cache::remember('courses_page_categories', 3600, function () {
            return CourseCategory::all();
        });

        $levels = Cache::remember('courses_page_levels', 3600, function () {
            return CourseLevel::orderBy('sort_order')->get();
        });

        // Build query with eager loading for better performance
        $query = Course::with(['category', 'level'])
            ->select('id', 'title', 'slug', 'description', 'image', 'duration', 'is_featured', 'category_id', 'level_id', 'status')
            ->where('status', 'active');

        // Apply filters
        $hasFilters = false;

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
            $hasFilters = true;
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
            $hasFilters = true;
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', '%' . $keyword . '%');
            });
            $hasFilters = true;
        }

        // Paginate (12 per page for better UX)
        $courses = $query->latest()->paginate(12);

        // AJAX Request: Return JSON
        if ($request->ajax() || $request->wantsJson()) {
            $html = view('frontend.partials.course-grid', compact('courses'))->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $courses->hasMorePages(),
                'currentPage' => $courses->currentPage(),
                'total' => $courses->total()
            ]);
        }

        // Normal Request: Return view
        return view('frontend.courses', compact('courses', 'categories', 'levels'));
    }

    public function show($categorySlug, $levelSlug, $courseSlug): View
    {
        // Find course by slug with relationships
        $course = Course::with(['category', 'level'])
            ->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->whereHas('level', function($q) use ($levelSlug) {
                $q->where('slug', $levelSlug);
            })
            ->where('slug', $courseSlug)
            ->where('status', 'active')
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

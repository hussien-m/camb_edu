<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use App\Models\Page;
use App\Models\SuccessStory;
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
        ];

        $recentCourses = Course::with(['category', 'level'])
            ->latest()
            ->limit(5)
            ->get();

        $recentMessages = Contact::latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentCourses', 'recentMessages'));
    }
}

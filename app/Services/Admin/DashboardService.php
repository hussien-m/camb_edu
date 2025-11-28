<?php

namespace App\Services\Admin;

use App\Models\Banner;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use App\Models\Page;
use App\Models\SuccessStory;

class DashboardService
{
    /**
     * Get dashboard statistics.
     */
    public function getStatistics()
    {
        return [
            'courses' => Course::count(),
            'categories' => CourseCategory::count(),
            'levels' => CourseLevel::count(),
            'pages' => Page::count(),
            'stories' => SuccessStory::count(),
            'unread_messages' => Contact::where('is_read', false)->count(),
            'banners' => Banner::count(),
        ];
    }

    /**
     * Get recent courses.
     */
    public function getRecentCourses($limit = 5)
    {
        return Course::with(['category', 'level'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent messages.
     */
    public function getRecentMessages($limit = 5)
    {
        return Contact::latest()
            ->limit($limit)
            ->get();
    }
}

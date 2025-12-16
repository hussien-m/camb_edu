<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Page;
use App\Models\SuccessStory;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    /**
     * Generate XML sitemap
     */
    public function index()
    {
        // Cache sitemap for 1 day
        $sitemap = Cache::remember('sitemap_xml', 86400, function () {
            return $this->generateSitemap();
        });

        return response($sitemap)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate sitemap content
     */
    private function generateSitemap(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        $xml .= ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // Homepage
        $xml .= $this->addUrl(route('home'), now(), 'daily', '1.0');

        // Courses index page
        $xml .= $this->addUrl(route('courses.index'), now(), 'daily', '0.9');

        // Success Stories page
        $xml .= $this->addUrl(route('success.stories'), now(), 'weekly', '0.8');

        // All courses
        $courses = Course::where('status', 'active')
            ->with(['category', 'level'])
            ->get();

        foreach ($courses as $course) {
            $url = route('courses.show', [
                $course->category->slug,
                $course->level->slug,
                $course->slug
            ]);

            $xml .= $this->addUrl(
                $url,
                $course->updated_at,
                'weekly',
                '0.8',
                $course->image ? asset('storage/' . $course->image) : null
            );
        }

        // All pages
        $pages = Page::where('is_published', true)->get();

        foreach ($pages as $page) {
            $url = route('page.show', $page->slug);
            $xml .= $this->addUrl($url, $page->updated_at, 'monthly', '0.7');
        }

        // Success Stories
        $stories = SuccessStory::where('is_published', true)->get();

        foreach ($stories as $story) {
            // If you have individual success story pages, add them here
            // For now, they're on the main success stories page
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Add URL to sitemap
     */
    private function addUrl(
        string $loc,
        $lastmod = null,
        string $changefreq = 'weekly',
        string $priority = '0.5',
        ?string $image = null
    ): string {
        $xml = '<url>';
        $xml .= '<loc>' . htmlspecialchars($loc) . '</loc>';

        if ($lastmod) {
            $xml .= '<lastmod>' . $lastmod->toW3cString() . '</lastmod>';
        }

        $xml .= '<changefreq>' . $changefreq . '</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';

        if ($image) {
            $xml .= '<image:image>';
            $xml .= '<image:loc>' . htmlspecialchars($image) . '</image:loc>';
            $xml .= '</image:image>';
        }

        $xml .= '</url>';

        return $xml;
    }
}

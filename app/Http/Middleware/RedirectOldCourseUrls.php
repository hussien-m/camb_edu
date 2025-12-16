<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\CourseCategory;
use App\Models\CourseLevel;
use App\Models\Page;

class RedirectOldCourseUrls
{
    /**
     * Handle an incoming request.
     * Redirect old query parameter URLs to new SEO-friendly URLs
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle page_id redirects (e.g., ?page_id=1 -> /page/about)
        if ($request->has('page_id')) {
            $pageId = $request->get('page_id');
            $page = Page::find($pageId);

            if ($page && $page->slug) {
                return redirect("/page/{$page->slug}", 301);
            }
        }

        // Only handle /courses route with query parameters
        if ($request->path() === 'courses' && ($request->has('category_id') || $request->has('level_id'))) {
            $categoryId = $request->get('category_id');
            $levelId = $request->get('level_id');
            $keyword = $request->get('keyword');
            $page = $request->get('page');

            // Build new SEO-friendly URL
            $newUrl = null;

            // Case 1: Both category and level
            if ($categoryId && $levelId) {
                $category = CourseCategory::find($categoryId);
                $level = CourseLevel::find($levelId);

                if ($category && $level) {
                    $newUrl = "/courses/category/{$category->slug}/level/{$level->slug}";
                }
            }
            // Case 2: Only level
            elseif ($levelId) {
                $level = CourseLevel::find($levelId);

                if ($level) {
                    $newUrl = "/courses/level/{$level->slug}";
                }
            }
            // Case 3: Only category
            elseif ($categoryId) {
                $category = CourseCategory::find($categoryId);

                if ($category) {
                    $newUrl = "/courses/category/{$category->slug}";
                }
            }

            // If we have a new URL, redirect with 301 (permanent)
            if ($newUrl) {
                // Add page parameter if exists
                if ($page && $page > 1) {
                    $newUrl .= "?page={$page}";
                }

                // Add keyword parameter if exists
                if ($keyword) {
                    $separator = strpos($newUrl, '?') !== false ? '&' : '?';
                    $newUrl .= "{$separator}keyword=" . urlencode($keyword);
                }

                return redirect($newUrl, 301); // 301 = Permanent Redirect
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViews
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests and successful responses
        if ($request->isMethod('GET') && $response->getStatusCode() == 200) {
            $this->trackView($request);
        }

        return $response;
    }

    /**
     * Track the page view
     */
    protected function trackView(Request $request)
    {
        try {
            $data = [
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'device_type' => $this->getDeviceType($request),
                'country' => $this->getCountryFromIp($request->ip()),
                'user_id' => auth('student')->id() ?? auth()->id(),
            ];

            // Determine page type and viewable
            $routeName = $request->route()?->getName();

            if ($routeName === 'courses.show') {
                $data['page_type'] = 'course';
                $course = $request->route('course');
                if ($course) {
                    $data['viewable_type'] = 'App\Models\Course';
                    $data['viewable_id'] = is_object($course) ? $course->id : \App\Models\Course::where('slug', $course)->first()?->id;
                }
            } elseif ($routeName === 'page.show') {
                $data['page_type'] = 'page';
                $page = $request->route('slug');
                if ($page) {
                    $pageModel = \App\Models\Page::where('slug', $page)->first();
                    if ($pageModel) {
                        $data['viewable_type'] = 'App\Models\Page';
                        $data['viewable_id'] = $pageModel->id;
                    }
                }
            } elseif ($routeName === 'home') {
                $data['page_type'] = 'home';
            } elseif ($routeName === 'courses.index') {
                $data['page_type'] = 'courses_list';
            } elseif ($routeName === 'success.stories') {
                $data['page_type'] = 'success_stories';
            } else {
                $data['page_type'] = 'other';
            }

            PageView::create($data);
        } catch (\Exception $e) {
            // Silently fail to not interrupt user experience
            \Log::error('Failed to track page view: ' . $e->getMessage());
        }
    }

    /**
     * Get device type from user agent
     */
    protected function getDeviceType(Request $request): string
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        if (str_contains($userAgent, 'mobile') || str_contains($userAgent, 'android')) {
            return 'mobile';
        } elseif (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Get country from IP address
     */
    protected function getCountryFromIp(string $ip): string
    {
        // Local IPs
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return 'Local';
        }

        try {
            // Simple country detection using ipapi.co (free, no API key needed)
            // For production, consider using a local GeoIP database
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country");

            if ($response) {
                $data = json_decode($response, true);
                return $data['country'] ?? 'Unknown';
            }
        } catch (\Exception $e) {
            \Log::debug('Failed to get country for IP: ' . $ip);
        }

        return 'Unknown';
    }
}

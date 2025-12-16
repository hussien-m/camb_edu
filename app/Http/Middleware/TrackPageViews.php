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
            // Get advanced location data
            $locationData = $this->getAdvancedLocationData($request->ip());

            // Get browser and OS info
            $browserInfo = $this->getBrowserAndOS($request->userAgent());

            $data = [
                'url' => $request->fullUrl(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'device_type' => $this->getDeviceType($request),
                'country' => $locationData['country'],
                'city' => $locationData['city'],
                'region' => $locationData['region'],
                'timezone' => $locationData['timezone'],
                'isp' => $locationData['isp'],
                'browser' => $browserInfo['browser'],
                'os' => $browserInfo['os'],
                'session_id' => session()->getId(),
                'search_query' => $request->query('search') ?? $request->query('q'),
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
     * Get advanced location data from IP address
     */
    protected function getAdvancedLocationData(string $ip): array
    {
        $default = [
            'country' => 'Unknown',
            'city' => 'Unknown',
            'region' => 'Unknown',
            'timezone' => 'Unknown',
            'isp' => 'Unknown',
        ];

        // Local IPs
        if ($ip === '127.0.0.1' || $ip === '::1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return [
                'country' => 'Local',
                'city' => 'Localhost',
                'region' => 'Local Network',
                'timezone' => config('app.timezone'),
                'isp' => 'Local',
            ];
        }

        try {
            // Get comprehensive data from ip-api.com
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,timezone,isp");

            if ($response) {
                $data = json_decode($response, true);

                if ($data && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? 'Unknown',
                        'city' => $data['city'] ?? 'Unknown',
                        'region' => $data['regionName'] ?? 'Unknown',
                        'timezone' => $data['timezone'] ?? 'Unknown',
                        'isp' => $data['isp'] ?? 'Unknown',
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::debug('Failed to get location for IP: ' . $ip);
        }

        return $default;
    }

    /**
     * Extract browser and OS from user agent
     */
    protected function getBrowserAndOS(?string $userAgent): array
    {
        if (!$userAgent) {
            return ['browser' => 'Unknown', 'os' => 'Unknown'];
        }

        $userAgent = strtolower($userAgent);

        // Detect Browser
        $browser = 'Unknown';
        if (str_contains($userAgent, 'edge') || str_contains($userAgent, 'edg/')) {
            $browser = 'Edge';
        } elseif (str_contains($userAgent, 'chrome') && !str_contains($userAgent, 'edge')) {
            $browser = 'Chrome';
        } elseif (str_contains($userAgent, 'safari') && !str_contains($userAgent, 'chrome')) {
            $browser = 'Safari';
        } elseif (str_contains($userAgent, 'firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($userAgent, 'opera') || str_contains($userAgent, 'opr/')) {
            $browser = 'Opera';
        } elseif (str_contains($userAgent, 'msie') || str_contains($userAgent, 'trident')) {
            $browser = 'IE';
        }

        // Detect OS
        $os = 'Unknown';
        if (str_contains($userAgent, 'windows')) {
            $os = 'Windows';
        } elseif (str_contains($userAgent, 'mac')) {
            $os = 'macOS';
        } elseif (str_contains($userAgent, 'linux')) {
            $os = 'Linux';
        } elseif (str_contains($userAgent, 'android')) {
            $os = 'Android';
        } elseif (str_contains($userAgent, 'iphone') || str_contains($userAgent, 'ipad')) {
            $os = 'iOS';
        }

        return [
            'browser' => $browser,
            'os' => $os,
        ];
    }
}

<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class UrlHelper
{
    /**
     * Build a safe, absolute canonical URL for the current request.
     */
    public static function canonicalUrl(?Request $request = null): string
    {
        $request = $request ?? request();
        $baseUrl = rtrim(config('app.url') ?: $request->getSchemeAndHttpHost(), '/');
        $path = ltrim($request->path(), '/');

        // If the path itself looks like a full URL, fall back to the base URL.
        if (preg_match('#^https?://#i', $path)) {
            return $baseUrl;
        }

        $url = $path === '' ? $baseUrl : $baseUrl . '/' . $path;
        $query = $request->getQueryString();

        return $query ? $url . '?' . $query : $url;
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    /**
     * Track ad click
     */
    public function trackClick(Ad $ad)
    {
        try {
            $ad->incrementClicks();
            
            // Redirect to ad link if exists
            if ($ad->link) {
                return redirect($ad->link);
            }
            
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('Error tracking ad click: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Track ad view (for image tracking)
     */
    public function trackView(Ad $ad)
    {
        try {
            $ad->incrementViews();
            
            // Return a 1x1 transparent pixel
            $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
            
            return response($pixel, 200)
                ->header('Content-Type', 'image/gif')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            Log::error('Error tracking ad view: ' . $e->getMessage());
            return response('', 200);
        }
    }
}

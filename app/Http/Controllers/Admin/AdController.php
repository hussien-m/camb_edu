<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdRequest;
use App\Http\Requests\Admin\UpdateAdRequest;
use App\Models\Ad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Ad::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('position')) {
                $query->where('position', $request->position);
            }

            $sortBy = $request->get('sort_by', 'order');
            $sortOrder = $request->get('sort_order', 'asc');

            $allowedSortFields = ['order', 'created_at', 'title', 'views_count', 'clicks_count'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'order';
            }

            $query->orderBy($sortBy, $sortOrder);

            $ads = $query->paginate(15)->withQueryString();

            // Get active ads count for debugging
            $activeAdsCount = Ad::active()->count();
            $topAdsCount = Ad::active()->atPosition('top')->count();
            $middleAdsCount = Ad::active()->atPosition('middle')->count();
            $bottomAdsCount = Ad::active()->atPosition('bottom')->count();

            return view('admin.ads.index', compact('ads', 'activeAdsCount', 'topAdsCount', 'middleAdsCount', 'bottomAdsCount'));
        } catch (\Exception $e) {
            Log::error('Error fetching ads: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading ads.');
        }
    }

    public function create(): View
    {
        return view('admin.ads.create');
    }

    public function store(StoreAdRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('ads', 'public');
            }

            Ad::create($data);

            // Clear ads cache
            $this->clearAdsCache();

            return redirect()
                ->route('admin.ads.index')
                ->with('success', 'Ad created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating ad: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create ad. Please try again.');
        }
    }

    public function edit(Ad $ad): View
    {
        return view('admin.ads.edit', compact('ad'));
    }

    public function update(UpdateAdRequest $request, Ad $ad): RedirectResponse
    {
        try {
            $data = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($ad->image) {
                    Storage::disk('public')->delete($ad->image);
                }
                $data['image'] = $request->file('image')->store('ads', 'public');
            }

            $ad->update($data);

            // Clear ads cache
            $this->clearAdsCache();

            return redirect()
                ->route('admin.ads.index')
                ->with('success', 'Ad updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating ad: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ad_id' => $ad->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update ad. Please try again.');
        }
    }

    public function destroy(Ad $ad): RedirectResponse
    {
        try {
            // Delete image if exists
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }

            $ad->delete();

            // Clear ads cache
            $this->clearAdsCache();

            return redirect()
                ->route('admin.ads.index')
                ->with('success', 'Ad deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting ad: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'ad_id' => $ad->id
            ]);

            return back()
                ->with('error', 'Failed to delete ad. Please try again.');
        }
    }

    public function toggleStatus(Ad $ad): RedirectResponse
    {
        try {
            $ad->is_active = !$ad->is_active;
            $ad->save();

            // Clear ads cache
            $this->clearAdsCache();

            return back()
                ->with('success', 'Ad status updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error toggling ad status: ' . $e->getMessage());
            return back()
                ->with('error', 'Failed to update ad status.');
        }
    }

    /**
     * Clear all ads cache
     */
    private function clearAdsCache(): void
    {
        Cache::forget('home_top_ads');
        Cache::forget('home_middle_ads');
        Cache::forget('home_bottom_ads');
        Cache::forget('home_sidebar_ads');
    }
}

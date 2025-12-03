<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBannerRequest;
use App\Http\Requests\Admin\UpdateBannerRequest;
use App\Models\Banner;
use App\Services\Admin\BannerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BannerController extends Controller
{
    protected $bannerService;

    public function __construct(BannerService $bannerService)
    {
        $this->bannerService = $bannerService;
    }

    public function index(Request $request)
    {
        try {
            $query = Banner::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('subtitle', 'like', "%{$search}%");
                });
            }

            $sortBy = $request->get('sort_by', 'order');
            $sortOrder = $request->get('sort_order', 'asc');

            // Validate sort_by to prevent SQL injection
            $allowedSortFields = ['order', 'created_at', 'title', 'id'];
            if (!in_array($sortBy, $allowedSortFields)) {
                $sortBy = 'order';
            }

            $query->orderBy($sortBy, $sortOrder);

            $banners = $query->paginate(15)->withQueryString();
            return view('admin.banners.index', compact('banners'));
        } catch (\Exception $e) {
            Log::error('Error fetching banners: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading banners.');
        }
    }

    public function create(): View
    {
        return view('admin.banners.create');
    }

    public function store(StoreBannerRequest $request): RedirectResponse
    {
        try {
            $this->bannerService->createBanner($request->validated());

            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating banner: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create banner. Please try again.');
        }
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(UpdateBannerRequest $request, Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->updateBanner($banner, $request->validated());

            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating banner: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'banner_id' => $banner->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update banner. Please try again.');
        }
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        try {
            $this->bannerService->deleteBanner($banner);

            return redirect()
                ->route('admin.banners.index')
                ->with('success', 'Banner deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting banner: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'banner_id' => $banner->id
            ]);

            return back()
                ->with('error', 'Failed to delete banner. Please try again.');
        }
    }
}

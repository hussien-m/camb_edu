<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFeatureRequest;
use App\Http\Requests\Admin\UpdateFeatureRequest;
use App\Models\Feature;
use App\Services\Admin\FeatureService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FeatureController extends Controller
{
    protected $featureService;

    public function __construct(FeatureService $featureService)
    {
        $this->featureService = $featureService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Feature::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
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

            $features = $query->get();
            return view('admin.features.index', compact('features'));
        } catch (\Exception $e) {
            Log::error('Error fetching features: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading features.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.features.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeatureRequest $request)
    {
        try {
            $this->featureService->createFeature($request->validated());

            return redirect()->route('admin.features.index')
                ->with('success', 'Feature created successfully');
        } catch (\Exception $e) {
            Log::error('Error creating feature: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create feature. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Feature $feature)
    {
        return view('admin.features.show', compact('feature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feature $feature)
    {
        return view('admin.features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeatureRequest $request, Feature $feature)
    {
        try {
            $this->featureService->updateFeature($feature, $request->validated());

            return redirect()->route('admin.features.index')
                ->with('success', 'Feature updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating feature: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'feature_id' => $feature->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update feature. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature)
    {
        try {
            $this->featureService->deleteFeature($feature);

            return redirect()->route('admin.features.index')
                ->with('success', 'Feature deleted successfully');
        } catch (\Exception $e) {
            Log::error('Error deleting feature: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'feature_id' => $feature->id
            ]);

            return back()
                ->with('error', 'Failed to delete feature. Please try again.');
        }
    }
}

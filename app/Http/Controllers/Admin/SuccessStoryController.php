<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SuccessStoryStoreRequest;
use App\Http\Requests\Admin\SuccessStoryUpdateRequest;
use App\Models\SuccessStory;
use App\Services\Admin\SuccessStoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class SuccessStoryController extends Controller
{
    protected $storyService;

    public function __construct(SuccessStoryService $storyService)
    {
        $this->storyService = $storyService;
    }

    public function index(Request $request)
    {
        try {
            $query = SuccessStory::query();

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('student_name', 'like', "%{$search}%")
                      ->orWhere('title', 'like', "%{$search}%")
                      ->orWhere('country', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $stories = $query->paginate(15)->withQueryString();
            return view('admin.stories.index', compact('stories'));
        } catch (\Exception $e) {
            Log::error('Error fetching stories: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'An error occurred while loading stories. Please try again.');
        }
    }

    public function create(): View
    {
        return view('admin.stories.create');
    }

    public function store(SuccessStoryStoreRequest $request): RedirectResponse
    {
        try {
            $this->storyService->createStory($request->validated());

            return redirect()
                ->route('admin.stories.index')
                ->with('success', 'Success story created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating success story: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to create success story. Please try again.');
        }
    }

    public function edit(SuccessStory $story): View
    {
        return view('admin.stories.edit', compact('story'));
    }

    public function update(SuccessStoryUpdateRequest $request, SuccessStory $story): RedirectResponse
    {
        try {
            $this->storyService->updateStory($story, $request->validated());

            return redirect()
                ->route('admin.stories.index')
                ->with('success', 'Success story updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating success story: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'story_id' => $story->id
            ]);

            return back()
                ->withInput()
                ->with('error', 'Failed to update success story. Please try again.');
        }
    }

    public function destroy(SuccessStory $story): RedirectResponse
    {
        try {
            $this->storyService->deleteStory($story);

            return redirect()
                ->route('admin.stories.index')
                ->with('success', 'Success story deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting success story: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'story_id' => $story->id
            ]);

            return back()
                ->with('error', 'Failed to delete success story. Please try again.');
        }
    }
}

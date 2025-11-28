<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSuccessStoryRequest;
use App\Http\Requests\Admin\UpdateSuccessStoryRequest;
use App\Models\SuccessStory;
use App\Services\Admin\SuccessStoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SuccessStoryController extends Controller
{
    protected $storyService;

    public function __construct(SuccessStoryService $storyService)
    {
        $this->storyService = $storyService;
    }

    public function index(): View
    {
        $stories = SuccessStory::latest()->paginate(15);
        return view('admin.stories.index', compact('stories'));
    }

    public function create(): View
    {
        return view('admin.stories.create');
    }

    public function store(StoreSuccessStoryRequest $request): RedirectResponse
    {
        $this->storyService->createStory($request->validated());

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Success story created successfully.');
    }

    public function edit(SuccessStory $story): View
    {
        return view('admin.stories.edit', compact('story'));
    }

    public function update(UpdateSuccessStoryRequest $request, SuccessStory $story): RedirectResponse
    {
        $this->storyService->updateStory($story, $request->validated());

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Success story updated successfully.');
    }

    public function destroy(SuccessStory $story): RedirectResponse
    {
        $this->storyService->deleteStory($story);

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Success story deleted successfully.');
    }
}

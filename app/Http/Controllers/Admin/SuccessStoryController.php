<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuccessStory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SuccessStoryController extends Controller
{
    public function index(): View
    {
        $stories = SuccessStory::latest()->paginate(15);
        return view('admin.stories.index', compact('stories'));
    }

    public function create(): View
    {
        return view('admin.stories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'story' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('stories', 'public');
        }

        $validated['is_published'] = $request->has('is_published') ? 1 : 0;

        SuccessStory::create($validated);

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Success story created successfully.');
    }

    public function edit(SuccessStory $story): View
    {
        return view('admin.stories.edit', compact('story'));
    }

    public function update(Request $request, SuccessStory $story): RedirectResponse
    {
        $validated = $request->validate([
            'student_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'title' => 'nullable|string|max:255',
            'story' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($story->image) {
                Storage::disk('public')->delete($story->image);
            }
            $validated['image'] = $request->file('image')->store('stories', 'public');
        }

        $validated['is_published'] = $request->has('is_published') ? 1 : 0;

        $story->update($validated);

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Success story updated successfully.');
    }

    public function destroy(SuccessStory $story): RedirectResponse
    {
        if ($story->image) {
            Storage::disk('public')->delete($story->image);
        }

        $story->delete();

        return redirect()
            ->route('admin.stories.index')
            ->with('success', 'Success story deleted successfully.');
    }
}

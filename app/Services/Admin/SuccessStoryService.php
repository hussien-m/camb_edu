<?php

namespace App\Services\Admin;

use App\Models\SuccessStory;
use Illuminate\Support\Facades\Storage;

class SuccessStoryService
{
    /**
     * Create a new success story.
     */
    public function createStory(array $data): SuccessStory
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('stories', 'public');
        }

        $data['is_published'] = isset($data['is_published']) ? 1 : 0;

        return SuccessStory::create($data);
    }

    /**
     * Update an existing success story.
     */
    public function updateStory(SuccessStory $story, array $data): bool
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($story->image) {
                Storage::disk('public')->delete($story->image);
            }
            $data['image'] = $data['image']->store('stories', 'public');
        }

        $data['is_published'] = isset($data['is_published']) ? 1 : 0;

        return $story->update($data);
    }

    /**
     * Delete a success story.
     */
    public function deleteStory(SuccessStory $story): bool
    {
        if ($story->image) {
            Storage::disk('public')->delete($story->image);
        }

        return $story->delete();
    }
}

<?php

namespace App\Services\Admin;

use App\Models\Page;
use Illuminate\Support\Str;

class PageService
{
    /**
     * Create a new page
     */
    public function createPage(array $data): Page
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['is_published'] = isset($data['is_published']) ? 1 : 0;
        return Page::create($data);
    }

    /**
     * Update an existing page
     */
    public function updatePage(Page $page, array $data): bool
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['is_published'] = isset($data['is_published']) ? 1 : 0;
        return $page->update($data);
    }

    /**
     * Delete a page
     */
    public function deletePage(Page $page): bool
    {
        return $page->delete();
    }
}

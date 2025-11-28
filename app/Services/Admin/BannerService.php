<?php

namespace App\Services\Admin;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class BannerService
{
    /**
     * Create a new banner.
     */
    public function createBanner(array $data): Banner
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $data['image']->store('banners', 'public');
        }

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return Banner::create($data);
    }

    /**
     * Update an existing banner.
     */
    public function updateBanner(Banner $banner, array $data): bool
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $data['image']->store('banners', 'public');
        }

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $banner->update($data);
    }

    /**
     * Delete a banner.
     */
    public function deleteBanner(Banner $banner): bool
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        return $banner->delete();
    }
}

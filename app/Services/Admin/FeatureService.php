<?php

namespace App\Services\Admin;

use App\Models\Feature;

class FeatureService
{
    /**
     * Create a new feature.
     */
    public function createFeature(array $data): Feature
    {
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        return Feature::create($data);
    }

    /**
     * Update an existing feature.
     */
    public function updateFeature(Feature $feature, array $data): bool
    {
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;
        return $feature->update($data);
    }

    /**
     * Delete a feature.
     */
    public function deleteFeature(Feature $feature): bool
    {
        return $feature->delete();
    }
}

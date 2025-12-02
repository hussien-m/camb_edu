<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SettingService
{
    /**
     * Update settings with support for image uploads.
     */
    public function updateSettings(array $settingsData, $request): void
    {
        // Get all settings from database
        $allSettings = Setting::all()->keyBy('key');

        // First, handle all text/non-file settings
        foreach ($settingsData as $key => $value) {
            $setting = $allSettings->get($key);

            if (!$setting) {
                continue;
            }

            // Skip image type settings here - they're handled separately
            if ($setting->type === 'image') {
                continue;
            }

            // Update the setting value
            $setting->update(['value' => $value]);
        }

        // Now handle file uploads separately
        $files = $request->file('settings');
        if ($files && is_array($files)) {
            foreach ($files as $key => $file) {
                $setting = $allSettings->get($key);

                if (!$setting || $setting->type !== 'image') {
                    continue;
                }

                if ($file && $file->isValid()) {
                    // Delete old image if exists
                    if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                        Storage::disk('public')->delete($setting->value);
                    }

                    // Store new image
                    $path = $file->store('settings', 'public');
                    $setting->update(['value' => $path]);

                    Log::info("Image uploaded for {$key}: {$path}");
                }
            }
        }

        // Clear cache
        Setting::clearCache();
    }
}

<?php

namespace App\Services\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    /**
     * Update settings with support for image uploads.
     */
    public function updateSettings(array $settingsData, $request): void
    {
        foreach ($settingsData as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                continue;
            }

            // Handle image upload
            if ($setting->type === 'image' && $request->hasFile("settings.{$key}")) {
                // Delete old image
                if ($setting->value) {
                    Storage::disk('public')->delete($setting->value);
                }
                $value = $request->file("settings.{$key}")->store('settings', 'public');
            }

            $setting->update(['value' => $value]);
        }

        // Clear cache
        Setting::clearCache();
    }
}

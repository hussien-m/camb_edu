<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
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

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}

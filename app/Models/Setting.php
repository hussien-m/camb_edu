<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value
     */
    public static function set(string $key, $value, string $type = 'text', string $group = 'general'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
            ]
        );
        Cache::forget("setting_{$key}");
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            return self::where('group', $group)->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('settings_all');
        self::all()->each(function ($setting) {
            Cache::forget("setting_{$setting->key}");
        });
    }

    /**
     * Get all settings as key-value array
     */
    public static function getAllSettings(): array
    {
        return Cache::remember('settings_all', 3600, function () {
            return self::pluck('value', 'key')->toArray();
        });
    }
}

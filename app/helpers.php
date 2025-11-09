<?php

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings as array
     *
     * @return array
     */
    function settings(): array
    {
        return \App\Models\Setting::getAllSettings();
    }
}

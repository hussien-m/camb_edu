<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ClearAppCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all application caches (config, route, view, settings, data)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🧹 Clearing all caches...');

        // Clear Laravel default caches
        $this->info('→ Clearing config cache...');
        Artisan::call('config:clear');

        $this->info('→ Clearing route cache...');
        Artisan::call('route:clear');

        $this->info('→ Clearing view cache...');
        Artisan::call('view:clear');

        $this->info('→ Clearing application cache...');
        Artisan::call('cache:clear');

        // Clear custom caches
        $this->info('→ Clearing homepage cache...');
        Cache::forget('home_banners');
        Cache::forget('home_categories');
        Cache::forget('home_levels');
        Cache::forget('home_featured_courses');
        Cache::forget('home_latest_courses');
        Cache::forget('home_success_stories');
        Cache::forget('home_features');

        $this->info('→ Clearing courses page cache...');
        Cache::forget('courses_page_categories');
        Cache::forget('courses_page_levels');
        // Clear paginated courses cache (up to 10 pages)
        for ($i = 1; $i <= 10; $i++) {
            Cache::forget("courses_page_default_p{$i}");
        }

        $this->info('→ Clearing settings cache...');
        Setting::clearCache();

        $this->info('✅ All caches cleared successfully!');

        return Command::SUCCESS;
    }
}

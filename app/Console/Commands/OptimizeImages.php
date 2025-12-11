<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize
                            {--path=courses : Path to optimize}
                            {--limit=100 : Number of images to process per run}
                            {--skip=0 : Number of images to skip}';
    protected $description = 'Optimize all images in storage';

    public function handle(ImageOptimizationService $optimizer): int
    {
        // Remove time limit
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $path = $this->option('path');
        $limit = (int) $this->option('limit');
        $skip = (int) $this->option('skip');

        $this->info("🖼️  Optimizing images in: {$path}");

        $files = Storage::disk('public')->files($path);
        $images = array_filter($files, function($file) {
            return preg_match('/\.(jpg|jpeg|png)$/i', $file);
        });

        $total = count($images);
        $this->info("Found {$total} images total");

        // Apply skip and limit
        $images = array_slice($images, $skip, $limit);
        $processing = count($images);

        $this->info("Processing {$processing} images (skipping first {$skip})");
        $this->newLine();

        $bar = $this->output->createProgressBar($processing);
        $bar->start();

        $optimized = 0;
        $failed = 0;

        foreach ($images as $image) {
            if ($optimizer->optimizeExisting($image, 80)) {
                $optimized++;
            } else {
                $failed++;
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Optimized: {$optimized}");
        if ($failed > 0) {
            $this->warn("⚠️  Failed: {$failed}");
        }

        // Show next command if there are more images
        $remaining = $total - ($skip + $processing);
        if ($remaining > 0) {
            $nextSkip = $skip + $processing;
            $this->newLine();
            $this->comment("📋 {$remaining} images remaining");
            $this->comment("Run next batch: php artisan images:optimize --skip={$nextSkip} --limit={$limit}");
        } else {
            $this->newLine();
            $this->info("🎉 All images processed!");
        }

        return Command::SUCCESS;
    }
}

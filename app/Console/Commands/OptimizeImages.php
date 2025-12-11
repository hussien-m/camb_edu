<?php

namespace App\Console\Commands;

use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize {--path=courses : Path to optimize}';
    protected $description = 'Optimize all images in storage';

    public function handle(ImageOptimizationService $optimizer): int
    {
        $path = $this->option('path');
        $this->info("🖼️  Optimizing images in: {$path}");

        $files = Storage::disk('public')->files($path);
        $images = array_filter($files, function($file) {
            return preg_match('/\.(jpg|jpeg|png)$/i', $file);
        });

        $total = count($images);
        $this->info("Found {$total} images");

        $bar = $this->output->createProgressBar($total);
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

        return Command::SUCCESS;
    }
}

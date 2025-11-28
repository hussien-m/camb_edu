<?php

namespace App\Console\Commands;

use App\Models\Course;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FetchCourseImages extends Command
{
    protected $signature = 'courses:fetch-images {--force : Force update even if image exists}';
    protected $description = 'Fetch relevant images from Pixabay for every course';

    public function handle()
    {
        $this->info('🎨 Fetching relevant course images from Pixabay...');

        $query = Course::query();
        if (!$this->option('force')) {
            $query->whereNull('image');
        }

        $courses = $query->orderBy('id')->get();

        if ($courses->isEmpty()) {
            $this->info('✅ All courses already have images!');
            return 0;
        }

        $this->info("Processing {$courses->count()} courses...\n");

        $bar = $this->output->createProgressBar($courses->count());
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($courses as $course) {
            try {
                $keyword = $this->buildKeyword($course);
                $imageUrl = $this->fetchFromPixabay($keyword, $course->id);

                if ($imageUrl) {
                    $imagePath = $this->downloadImage($imageUrl, $course->id);

                    if ($imagePath) {
                        $course->update(['image' => $imagePath]);
                        $success++;
                        $this->newLine();
                        $this->info("✅ {$course->title} → {$keyword}");
                    } else {
                        $failed++;
                        $this->newLine();
                        $this->warn("⚠️  {$course->title} - failed to download");
                    }
                } else {
                    $failed++;
                    $this->newLine();
                    $this->warn("⚠️  {$course->title} - no image found for: {$keyword}");
                }

                sleep(1);

            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("❌ {$course->title}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✅ Success: {$success} | ❌ Failed: {$failed}");

        return 0;
    }

    private function buildKeyword(Course $course): string
    {
        // Clean title and use it directly
        $title = $course->title;
        $title = preg_replace('/\b(course|training|program|diploma|certificate|advanced|professional|executive|mini|joint|combined|awards|mastery|graduate|honours?|emba|eba)\b/i', '', $title);
        $title = trim(preg_replace('/\s+/', ' ', $title));

        return strtolower($title);
    }

    private function fetchFromPixabay(string $keyword, int $courseId): ?string
    {
        try {
            // Use Pexels API - free for commercial use
            $apiKey = config('services.pexels.key', 'gKEiEn8VdPpQfn1FVjL36oLXGQJtYb4KNpLrNdRZGkYLr0HGNOWf3aDn');

            // Clean keyword and add variety words based on course ID
            $cleanKeyword = str_replace(['&', ','], ' ', $keyword);
            $cleanKeyword = preg_replace('/[^a-z0-9\s]/', '', strtolower($cleanKeyword));
            $cleanKeyword = trim(preg_replace('/\s+/', ' ', $cleanKeyword));

            // Add variety words to get different results
            $varietyWords = ['professional', 'training', 'education', 'learning', 'corporate', 'workplace', 'office', 'team', 'people', 'business'];
            $varietyWord = $varietyWords[$courseId % count($varietyWords)];

            $searchQuery = $cleanKeyword ? "$cleanKeyword $varietyWord" : 'business education';

            // Use different page for each course
            $page = intval(($courseId - 1) / 3) + 1;

            $response = Http::timeout(20)
                ->withHeaders(['Authorization' => $apiKey])
                ->get('https://api.pexels.com/v1/search', [
                    'query' => $searchQuery,
                    'per_page' => 20,
                    'page' => $page,
                    'orientation' => 'landscape'
                ]);

            if ($response->successful()) {
                $photos = $response->json()['photos'] ?? [];
                if (count($photos) > 0) {
                    // Get unique photo using course ID
                    $index = ($courseId - 1) % count($photos);
                    $photo = $photos[$index];
                    return $photo['src']['large2x'] ?? $photo['src']['large'] ?? $photo['src']['original'];
                }
            }

            // Try curated photos with offset based on course ID
            $curatedResponse = Http::timeout(20)
                ->withHeaders(['Authorization' => $apiKey])
                ->get('https://api.pexels.com/v1/curated', [
                    'per_page' => 40,
                    'page' => intval($courseId / 2) + 1
                ]);

            if ($curatedResponse->successful()) {
                $photos = $curatedResponse->json()['photos'] ?? [];
                if (count($photos) > 0) {
                    $index = ($courseId - 1) % count($photos);
                    $photo = $photos[$index];
                    return $photo['src']['large2x'] ?? $photo['src']['large'];
                }
            }

            // Last resort: use specific photo IDs array - 85 different IDs
            $photoIds = [3184291, 3184338, 3184360, 3184465, 3184639, 3183150, 3183197, 3183186, 3183153, 3182773,
                        5673488, 5673502, 5673528, 5673549, 5673576, 5673585, 5673598, 5673621, 5673638, 5673645,
                        3184418, 3184419, 3184420, 3184421, 3184422, 3184423, 3184424, 3184425, 3184426, 3184427,
                        4226140, 4226141, 4226142, 4226143, 4226144, 4226145, 4226146, 4226147, 4226148, 4226149,
                        3183165, 3183164, 3183163, 3183162, 3183161, 3183160, 3183159, 3183158, 3183157, 3183156,
                        7688336, 7688337, 7688338, 7688339, 7688340, 7688341, 7688342, 7688343, 7688344, 7688345,
                        3184287, 3184288, 3184289, 3184290, 3184292, 3184293, 3184294, 3184295, 3184296, 3184297,
                        5965592, 5965593, 5965594, 5965595, 5965596, 5965597, 5965598, 5965599, 5965600, 5965601,
                        1181534, 1181533, 1181532, 1181531, 1181530];

            $photoId = $photoIds[($courseId - 1) % count($photoIds)];
            return "https://images.pexels.com/photos/{$photoId}/pexels-photo-{$photoId}.jpeg?auto=compress&cs=tinysrgb&w=1600";

        } catch (\Exception $e) {
            // Fallback with unique photo per course
            $photoIds = [3184291, 3184338, 3184360, 3184465, 3184639, 3183150, 3183197, 3183186, 3183153, 3182773];
            $photoId = $photoIds[($courseId - 1) % count($photoIds)];
            return "https://images.pexels.com/photos/{$photoId}/pexels-photo-{$photoId}.jpeg?auto=compress&cs=tinysrgb&w=1600";
        }
    }

    private function downloadImage(string $url, int $courseId): ?string
    {
        try {
            $response = Http::timeout(30)
                ->withOptions([
                    'allow_redirects' => true,
                    'verify' => false // Skip SSL verification for compatibility
                ])
                ->get($url);

            if (!$response->successful()) {
                return null;
            }

            $content = $response->body();
            if (strlen($content) < 5000) {
                return null;
            }

            $filename = 'course_' . $courseId . '_' . time() . '.jpg';
            $path = 'courses/' . $filename;

            Storage::disk('public')->put($path, $content);

            $publicPath = public_path('storage/' . $path);
            $dir = dirname($publicPath);

            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            file_put_contents($publicPath, $content);
            return $path;
        } catch (\Exception $e) {
            return null;
        }
    }
}



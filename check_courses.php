<?php
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Course Counts by Level ===\n\n";

$levels = DB::table('course_levels')->orderBy('sort_order')->get();

foreach ($levels as $level) {
    $count = DB::table('courses')->where('level_id', $level->id)->count();
    echo sprintf("%-30s : %4d courses (Level ID: %d)\n", $level->name, $count, $level->id);
}

echo "\n=== Total ===\n";
echo "Total Courses: " . DB::table('courses')->count() . "\n";

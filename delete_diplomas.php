<?php
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Deleting existing Professional Diploma courses...\n";

$deleted = DB::table('courses')->where('level_id', 12)->delete();

echo "✓ Deleted {$deleted} Professional Diploma courses\n";

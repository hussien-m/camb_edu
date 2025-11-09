<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Course;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all courses with clean slugs (only course title)
        $courses = Course::all();

        foreach ($courses as $course) {
            // Create slug from title only
            $slug = Str::slug($course->title);

            // Make sure slug is unique
            $originalSlug = $slug;
            $count = 1;
            while (Course::where('slug', $slug)->where('id', '!=', $course->id)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }

            $course->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse
    }
};

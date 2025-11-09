<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained('course_categories')->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('course_levels')->nullOnDelete();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('duration', 100)->nullable();
            $table->string('mode', 50)->nullable();
            $table->decimal('fee', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

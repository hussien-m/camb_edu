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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['banner', 'sidebar', 'inline', 'popup'])->default('banner');
            $table->enum('position', ['top', 'middle', 'bottom', 'sidebar-left', 'sidebar-right'])->default('top');
            $table->string('image')->nullable();
            $table->text('html_content')->nullable(); // For custom HTML ads
            $table->string('link')->nullable();
            $table->boolean('open_in_new_tab')->default(true);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('clicks_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};

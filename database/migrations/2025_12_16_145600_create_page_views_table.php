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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500);
            $table->string('page_type')->nullable(); // home, course, page, etc.
            $table->unsignedBigInteger('viewable_id')->nullable();
            $table->string('viewable_type')->nullable(); // Course, Page, etc.
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('referer', 500)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('device_type', 50)->nullable(); // mobile, desktop, tablet
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->index(['viewable_id', 'viewable_type']);
            $table->index('page_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};

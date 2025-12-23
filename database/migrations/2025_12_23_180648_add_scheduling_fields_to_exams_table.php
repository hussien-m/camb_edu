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
        Schema::table('exams', function (Blueprint $table) {
            $table->boolean('is_scheduled')->default(false)->after('status');
            $table->dateTime('scheduled_start_date')->nullable()->after('is_scheduled');
            $table->dateTime('scheduled_end_date')->nullable()->after('scheduled_start_date');
            $table->string('timezone')->default('UTC')->after('scheduled_end_date');
            $table->text('scheduling_notes')->nullable()->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn([
                'is_scheduled',
                'scheduled_start_date',
                'scheduled_end_date',
                'timezone',
                'scheduling_notes'
            ]);
        });
    }
};

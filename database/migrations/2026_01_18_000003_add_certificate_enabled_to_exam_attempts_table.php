<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->boolean('certificate_enabled')->default(false)->after('passed');
        });

        DB::table('exam_attempts')
            ->whereIn('id', function ($query) {
                $query->select('exam_attempt_id')->from('certificates');
            })
            ->update(['certificate_enabled' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_attempts', function (Blueprint $table) {
            $table->dropColumn('certificate_enabled');
        });
    }
};

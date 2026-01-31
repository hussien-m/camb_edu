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
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['exam_attempt_id']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            // Make exam_attempt_id nullable for admin-manual certificates
            $table->unsignedBigInteger('exam_attempt_id')->nullable()->change();
            // Add transcript/grades sheet file path
            $table->string('transcript_file')->nullable()->after('certificate_file');
            // Add active toggle for admin to enable/disable
            $table->boolean('is_active')->default(true)->after('transcript_file');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('exam_attempt_id')->references('id')->on('exam_attempts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['exam_attempt_id']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['transcript_file', 'is_active']);
            $table->unsignedBigInteger('exam_attempt_id')->nullable(false)->change();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('exam_attempt_id')->references('id')->on('exam_attempts')->onDelete('cascade');
        });
    }
};

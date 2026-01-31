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
            $table->dropForeign(['course_id']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->string('course_title')->nullable()->after('course_id');
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn('course_title');
            $table->unsignedBigInteger('course_id')->nullable(false)->change();
        });

        Schema::table('certificates', function (Blueprint $table) {
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }
};

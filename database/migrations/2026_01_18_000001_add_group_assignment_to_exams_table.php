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
            $table->boolean('group_assignment_enabled')->default(false)->after('scheduling_notes');
            $table->boolean('allow_enrolled_access')->default(true)->after('group_assignment_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['group_assignment_enabled', 'allow_enrolled_access']);
        });
    }
};

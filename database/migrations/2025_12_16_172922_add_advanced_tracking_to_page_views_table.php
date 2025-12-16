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
        Schema::table('page_views', function (Blueprint $table) {
            $table->string('city')->nullable()->after('country');
            $table->string('region')->nullable()->after('city');
            $table->string('timezone')->nullable()->after('region');
            $table->string('isp')->nullable()->after('timezone');
            $table->string('session_id')->nullable()->after('user_id');
            $table->integer('time_on_page')->nullable()->comment('Time in seconds');
            $table->string('search_query')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->timestamp('exit_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropColumn([
                'city',
                'region',
                'timezone',
                'isp',
                'session_id',
                'time_on_page',
                'search_query',
                'browser',
                'os',
                'exit_time'
            ]);
        });
    }
};

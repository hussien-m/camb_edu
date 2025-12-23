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
        Schema::create('exam_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('reminder_type', ['24h', '12h', '6h', '90min', '10min']);
            $table->dateTime('scheduled_for');
            $table->boolean('sent')->default(false);
            $table->dateTime('sent_at')->nullable();
            $table->timestamps();

            $table->index(['exam_id', 'student_id', 'reminder_type']);
            $table->index(['sent', 'scheduled_for']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_reminders');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('user_students')->cascadeOnDelete();
            $table
                ->foreignId('activity_status_id')
                ->nullable()
                ->constrained('activity_statuses')
                ->nullOnDelete();
            $table->dateTime('posted')->nullable();
            $table->timestamps();

            $table->unique(['activity_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_students');
    }
};

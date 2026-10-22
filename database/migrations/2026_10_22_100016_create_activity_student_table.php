<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_status_id')->nullable()->constrained()->restrictOnDelete();
            $table->dateTime('posted_at')->nullable();
            $table->timestamps();

            $table->unique(['activity_id', 'user_student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_student');
    }
};

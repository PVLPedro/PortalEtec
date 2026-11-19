<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
            $table->foreignId('side_id')->nullable()->constrained('sides')->nullOnDelete();
            $table->unsignedInteger('rm');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_students');
    }
};

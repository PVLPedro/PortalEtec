<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('school_class_discipline_id')
                ->constrained('school_class_discipline')
                ->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('side_id')->nullable()->constrained('sides')->nullOnDelete();
            $table->string('title', 120);
            $table->text('description')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->boolean('comments_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

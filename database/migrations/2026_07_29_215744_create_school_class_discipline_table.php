<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_class_discipline', function (Blueprint $table) {
            $table->id();

            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();

            $table->foreignId('discipline_id')->constrained()->cascadeOnDelete();

            $table
                ->foreignId('id_teacher')
                ->nullable()
                ->references('id_teacher')
                ->on('user_teachers')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['school_class_id', 'discipline_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_class_discipline');
    }
};

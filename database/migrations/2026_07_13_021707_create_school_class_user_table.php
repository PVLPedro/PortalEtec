<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_class_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('id_teacher')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['school_class_id', 'id_teacher']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_class_user');
    }
};

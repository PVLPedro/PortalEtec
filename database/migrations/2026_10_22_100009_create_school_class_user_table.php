<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Alocação de professor (user) em uma turma para uma disciplina.
    public function up(): void
    {
        Schema::create('school_class_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('discipline_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Nome explícito: o automático passa de 64 caracteres (limite do MySQL).
            $table->unique(
                ['school_class_id', 'user_id', 'discipline_id'],
                'uq_class_user_discipline',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_class_user');
    }
};

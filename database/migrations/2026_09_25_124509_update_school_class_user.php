<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('school_class_user', function (Blueprint $table) {
            $table
                ->foreignId('discipline_id')
                ->after('id_teacher')
                ->constrained('disciplines')
                ->cascadeOnDelete();

            // Criar o novo unique ANTES de remover o antigo
            $table->unique(
                ['school_class_id', 'id_teacher', 'discipline_id'],
                'uq_class_teacher_discipline',
            );
            $table->dropUnique(['school_class_id', 'id_teacher']);
        });
    }

    public function down(): void
    {
        Schema::table('school_class_user', function (Blueprint $table) {
            $table->unique(['school_class_id', 'id_teacher']);
            $table->dropUnique('uq_class_teacher_discipline');

            $table->dropForeign(['discipline_id']);
            $table->dropColumn('discipline_id');
        });
    }
};

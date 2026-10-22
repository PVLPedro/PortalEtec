<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $colunaTurma = Schema::hasColumn('user_students', 'id_class')
            ? 'id_class'
            : 'school_classes_id';

        Schema::disableForeignKeyConstraints();

        try {
            Schema::create('user_students_new', function (Blueprint $table) {
                $table->increments('id_student');

                $table
                    ->foreignId('user_id')
                    ->nullable()
                    ->constrained('users', 'id')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->unsignedBigInteger('id_class')->nullable();

                $table->integer('rm');

                $table
                    ->foreign('id_class')
                    ->references('id')
                    ->on('school_classes')
                    ->cascadeOnUpdate()
                    ->nullOnDelete();
            });

            DB::statement("
                INSERT INTO user_students_new
                    (id_student, user_id, id_class, rm)
                SELECT
                    id_student, user_id, {$colunaTurma}, rm
                FROM user_students
            ");

            Schema::drop('user_students');

            Schema::rename('user_students_new', 'user_students');
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    public function down(): void
    {
        // Não reverte a estrutura anterior automaticamente.
    }
};

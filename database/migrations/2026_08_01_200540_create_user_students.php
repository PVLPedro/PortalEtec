<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_students', function (Blueprint $table) {
            $table->increments('id_student'); // INT AUTO_INCREMENT PRIMARY KEY

            // FK para users.id (padrão do Laravel: BIGINT UNSIGNED)
            // a coluna aqui se chama "id_user", mas referencia o "id" da tabela users
            $table
                ->foreignId('user_id')
                ->nullable()
                ->constrained(table: 'users', column: 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedInteger('id_class')->nullable();
            $table->integer('rm');

            // FK para class.id_class
            $table
                ->foreign('id_class')
                ->references('id_class')
                ->on('class')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_students', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['id_class']);
        });

        Schema::dropIfExists('user_students');
    }
};

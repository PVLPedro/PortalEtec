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
        Schema::create('etec_worker', function (Blueprint $table) {
            $table->increments('id');

            // FK para users.id (padrão do Laravel: BIGINT UNSIGNED)
            $table
                ->foreignId('user_id')
                ->constrained(table: 'users', column: 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // FK para etecs.id_etec
            $table->unsignedInteger('id_etec');
            $table
                ->foreign('id_etec')
                ->references('id_etec')
                ->on('etecs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Guarda o value do enum App\Enums\Role ('professor' ou 'coordenador' nesse contexto)
            $table->string('role', 20);

            // Impede vincular o mesmo usuario duas vezes na mesma etec
            $table->unique(['id_user', 'id_etec'], 'uq_user_etec');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('etec_worker', function (Blueprint $table) {
            $table->dropForeign(['id_user']);
            $table->dropForeign(['id_etec']);
        });

        Schema::dropIfExists('etec_worker');
    }
};

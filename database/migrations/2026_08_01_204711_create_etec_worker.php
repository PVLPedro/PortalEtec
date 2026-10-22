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

            $table
                ->foreignId('id_user')
                ->constrained(table: 'users', column: 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedInteger('id_etec');
            $table
                ->foreign('id_etec')
                ->references('codigo')
                ->on('etecs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('role', 20);

            $table->unique(['id_user', 'id_etec'], 'id_user_etec');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etec_worker');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('etec_worker', function (Blueprint $table) {
            $table->increments('id');

            $table
                ->foreignId('user_id')
                ->constrained(table: 'users', column: 'id')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_etec');
            $table
                ->foreign('id_etec')
                ->references('id')
                ->on('etecs')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->string('role', 20);

            $table->unique(['user_id', 'id_etec'], 'uq_user_etec');
        });
    }

    public function down(): void
    {
        Schema::table('etec_worker', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['id_etec']);
        });

        Schema::dropIfExists('etec_worker');
    }
};

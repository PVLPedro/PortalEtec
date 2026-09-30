<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_students', function (Blueprint $table) {
            $table->increments('id_activity_student');

            $table->unsignedInteger('id_activity')->nullable();
            $table->unsignedInteger('id_student')->nullable();
            $table->unsignedInteger('id_status')->nullable();

            $table->dateTime('posted')->nullable();

            $table
                ->foreign('id_activity')
                ->references('id_activity')
                ->on('activity')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table
                ->foreign('id_student')
                ->references('id_student')
                ->on('user_students')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table
                ->foreign('id_status')
                ->references('id_status')
                ->on('status')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('activity_students', function (Blueprint $table) {
            $table->dropForeign(['id_activity']);
            $table->dropForeign(['id_student']);
            $table->dropForeign(['id_status']);
        });

        Schema::dropIfExists('activity_students');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('school_class_discipline', function (Blueprint $table) {
            $table->unsignedInteger('id_side')->nullable()->after('id_teacher');

            $table
                ->foreign('id_side')
                ->references('id_side')
                ->on('side')
                ->onUpdate('cascade')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('school_class_discipline', function (Blueprint $table) {
            $table->dropForeign(['id_side']);
            $table->dropColumn('id_side');
        });
    }
};

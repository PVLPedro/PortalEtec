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
        Schema::table('user_students', function (Blueprint $table) {
            $table->unsignedInteger('id_side')->nullable()->after('id_class');

            $table
                ->foreign('id_side')
                ->references('id_side')
                ->on('side')
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
            $table->dropForeign(['id_side']);
            $table->dropColumn('id_side');
        });
    }
};

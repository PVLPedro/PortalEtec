<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {

    //     Schema::table('school_class_discipline', function (Blueprint $table) {
    //         $table->unsignedInteger('id_side')->nullable()->after('id_teacher');

    //         $table
    //             ->foreign('id_side')
    //             ->references('id_side')
    //             ->on('side')
    //             ->onUpdate('cascade')
    //             ->onDelete('set null');
    //     });
    // }
    public function up(): void
    {
        Schema::create('school_class_discipline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('id_teacher')->constrained('users')->cascadeOnDelete();

            $table->unsignedBigInteger('id_side')->nullable();
            $table
                ->foreign('id_side')
                ->references('id_side')
                ->on('side')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_class_discipline', function (Blueprint $table) {
            $table->dropForeign(['id_side']);
            $table->dropColumn('id_side');
        });
    }
};

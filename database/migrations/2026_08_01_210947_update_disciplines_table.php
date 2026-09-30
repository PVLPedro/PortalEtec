<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('disciplines', function (Blueprint $table) {
            $table->renameColumn('name', 'discipline_name');

            $table->string('discipline_initialism', 5)->nullable()->after('id');

            $table
                ->foreignId('course_id')
                ->nullable()
                ->after('discipline_name')
                ->constrained('courses')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('disciplines', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn(['discipline_initialism', 'course_id']);

            $table->renameColumn('discipline_name', 'name');
        });
    }
};

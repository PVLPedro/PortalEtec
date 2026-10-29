
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('school_class_discipline', function (Blueprint $table) {
            $table
                ->foreignId('side_id')
                ->nullable()
                ->after('teacher_id')
                ->constrained('sides')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('school_class_discipline', function (Blueprint $table) {
            $table->dropForeign(['side_id']);
            $table->dropColumn('side_id');
        });
    }
};


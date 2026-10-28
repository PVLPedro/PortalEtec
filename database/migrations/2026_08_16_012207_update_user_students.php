
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_students', function (Blueprint $table) {
            $table
                ->foreignId('side_id')
                ->nullable()
                ->after('school_class_id')
                ->constrained('sides')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('user_students', function (Blueprint $table) {
            $table->dropForeign(['side_id']);
            $table->dropColumn('side_id');
        });
    }
};


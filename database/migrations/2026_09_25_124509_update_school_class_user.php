
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('school_class_user', function (Blueprint $table) {
            $table
                ->foreignId('discipline_id')
                ->after('user_id')
                ->constrained('disciplines')
                ->cascadeOnDelete();

            $table->dropUnique(['school_class_id', 'user_id']);

            $table->unique(['school_class_id', 'user_id', 'discipline_id']);
        });
    }

    public function down(): void
    {
        Schema::table('school_class_user', function (Blueprint $table) {
            $table->dropUnique(['school_class_id', 'user_id', 'discipline_id']);

            $table->unique(['school_class_id', 'user_id']);

            $table->dropForeign(['discipline_id']);
            $table->dropColumn('discipline_id');
        });
    }
};


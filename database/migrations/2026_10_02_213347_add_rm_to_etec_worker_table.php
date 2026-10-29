
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('etec_worker', function (Blueprint $table) {
            $table->unsignedInteger('rm')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('etec_worker', function (Blueprint $table) {
            $table->dropColumn('rm');
        });
    }
};


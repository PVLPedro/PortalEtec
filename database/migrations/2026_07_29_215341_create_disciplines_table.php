
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('disciplines', function (Blueprint $table) {
            $table->id();

            $table->string('initialism', 5)->nullable();
            $table->string('name')->unique();

            $table->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disciplines');
    }
};


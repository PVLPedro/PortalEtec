
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity_students', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('activity_id')
                ->nullable()
                ->constrained('activities')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignId('student_id')
                ->nullable()
                ->constrained('user_students')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table
                ->foreignId('activity_status_id')
                ->nullable()
                ->constrained('activity_statuses')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->dateTime('posted')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_students');
    }
};


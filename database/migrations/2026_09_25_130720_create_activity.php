<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('id_activity'); // INT AUTO_INCREMENT PRIMARY KEY

            $table
                ->foreignId('school_class_user_id')
                ->nullable()
                ->constrained('school_class_user')
                ->cascadeOnDelete();

            $table->string('descriptive', 80)->nullable();
            $table->dateTime('deadline')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('activity', function (Blueprint $table) {
            $table->dropForeign(['school_class_user_id']);
        });

        Schema::dropIfExists('activity');
    }
};

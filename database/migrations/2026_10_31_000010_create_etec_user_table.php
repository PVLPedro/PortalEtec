<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('etec_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('etec_id')->constrained('etecs')->cascadeOnDelete();
            $table->string('role', 20);
            $table->unsignedInteger('rm')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'etec_id']);
            $table->unique(['etec_id', 'rm']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etec_user');
    }
};

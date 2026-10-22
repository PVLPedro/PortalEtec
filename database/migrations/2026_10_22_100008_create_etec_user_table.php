<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etec_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('etec_id')->constrained()->cascadeOnDelete();
            $table->string('role', 20); // cast para App\Enums\Role ('professor' ou 'coordenador')
            $table->timestamps();

            $table->unique(['user_id', 'etec_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etec_user');
    }
};

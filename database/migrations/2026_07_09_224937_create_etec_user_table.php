<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etec_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etec_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('rm', 7)->nullable(); // Só preenchido se o usuário for aluno
            $table->timestamps();

            $table->unique(['etec_id', 'user_id']); // Não duplica vínculo
            $table->unique(['etec_id', 'rm']); // RM único por Etec (aceita NULL múltiplas vezes)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etec_user');
    }
};

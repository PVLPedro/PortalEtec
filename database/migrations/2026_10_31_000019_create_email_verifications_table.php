<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('email');
            $table->string('purpose', 20);
            $table->string('code');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->dateTime('sent_at');
            $table->dateTime('expires_at');
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_verifications');
    }
};

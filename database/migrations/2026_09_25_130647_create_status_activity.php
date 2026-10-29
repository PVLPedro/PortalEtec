<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('status_activity', function (Blueprint $table) {
            $table->unsignedInteger('id_status')->primary();
            $table->string('descriptive', 80);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_activity');
    }
};

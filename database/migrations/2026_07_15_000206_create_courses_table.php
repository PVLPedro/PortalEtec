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
        Schema::create('course', function (Blueprint $table) {
            $table->id('id_course');
            $table->string('course_initialism', 6)->nullable();
            $table->string('course_name', 100);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('course');
    }
};
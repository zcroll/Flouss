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
        Schema::create('degrees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->integer('degree_level');
            $table->text('education_levels');
            $table->string('cip_code');
            $table->float('score');
            $table->integer('salary');
            $table->string('satisfaction');
            $table->float('satisfaction_raw');
            $table->string('image');
            $table->string('large_image');
            $table->string('link');
            $table->boolean('is_external');
            $table->boolean('is_premium');
            $table->text('specializations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degrees');
    }
};

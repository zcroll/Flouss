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
        if (!Schema::hasTable('degree_skills_v2')) {
        Schema::create('degree_skills_v2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('degree_id');
            $table->string('skill_title');
            $table->text('skill_description');
            $table->timestamps();

            $table->foreign('degree_id')->references('id')->on('degrees')->onDelete('cascade');
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('degree_skills_v2');
    }
};

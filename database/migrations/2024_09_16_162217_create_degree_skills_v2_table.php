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
        if (!Schema::hasTable('theproject2.degree_skills_v2')) {
        Schema::create('theproject2.degree_skills_v2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('degree_id');
            $table->string('skill_title');
            $table->text('skill_description');
            $table->timestamps();

            $table->foreign('degree_id')->references('id')->on('theproject2.degrees')->onDelete('cascade');
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theproject2.degree_skills_v2');
    }
};

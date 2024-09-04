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
        Schema::create('result', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id'); // User ID column
            $table->json('scores'); // JSON column for scores
            $table->json('jobs'); // JSON column for jobs
            $table->json('highestTwoScores'); // JSON column for highest two scores
            $table->json('Archetype');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result');
    }
};

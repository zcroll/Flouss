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
        Schema::create('big_five_traits', function (Blueprint $table) {
            $table->id();
            $table->longText('description')->nullable();
            $table->decimal('openness', 5, 2)->nullable();
            $table->decimal('conscientiousness', 5, 2)->nullable();
            $table->decimal('extraversion', 5, 2)->nullable();
            $table->decimal('agreeableness', 5, 2)->nullable();
            $table->decimal('neuroticism', 5, 2)->nullable();
            $table->enum('lang', ["fr","en"]);
            $table->foreignId('job_name_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('big_five_traits');
    }
};

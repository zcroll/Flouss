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
        Schema::create('personality_traits', function (Blueprint $table) {
            $table->id();
            $table->string('trait_name')->nullable();
            $table->string('trait_score')->nullable();
            $table->string('trait_type')->nullable();
            $table->foreignId('job_info_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personality_traits');
    }
};

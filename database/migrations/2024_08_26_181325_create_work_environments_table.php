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
        Schema::create('work_environments', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ["how-much-ability","how-talented","how-easy"]);
            $table->longText('description')->nullable();
            $table->integer('score')->nullable();
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
        Schema::dropIfExists('work_environments');
    }
};

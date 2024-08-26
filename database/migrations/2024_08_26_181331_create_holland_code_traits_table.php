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
        Schema::create('holland_code_traits', function (Blueprint $table) {
            $table->id();
            $table->longText('description')->nullable();
            $table->decimal('realistic', 5, 2)->nullable();
            $table->decimal('investigative', 5, 2)->nullable();
            $table->decimal('artistic', 5, 2)->nullable();
            $table->decimal('social', 5, 2)->nullable();
            $table->decimal('enterprising', 5, 2)->nullable();
            $table->decimal('conventional', 5, 2)->nullable();
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
        Schema::dropIfExists('holland_code_traits');
    }
};

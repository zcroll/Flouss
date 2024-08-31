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
        Schema::create('job_info_duties', function (Blueprint $table) {
            $table->id();
            $table->longText('duty_category')->nullable();
            $table->longText('duty_description')->nullable();
            $table->foreignId('job_info_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_info_duties');
    }
};

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
        Schema::create('job_info_details', function (Blueprint $table) {
            $table->id();
            $table->text('role_description_main')->nullable();
            $table->text('role_description_secondary')->nullable();
            $table->foreignId('job_info_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_info_details');
    }
};

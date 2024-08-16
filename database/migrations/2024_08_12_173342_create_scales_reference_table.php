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
        Schema::create('scales_reference', function (Blueprint $table) {
            $table->string('scale_id', 3)->primary();
            $table->string('scale_name', 50);
            $table->decimal('minimum', 1, 0);
            $table->decimal('maximum', 3, 0);            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scales_reference');
    }
};

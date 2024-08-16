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
        Schema::create('unspsc_reference', function (Blueprint $table) {
            $table->decimal('commodity_code', 8, 0)->primary();
            $table->string('commodity_title', 150);
            $table->decimal('class_code', 8, 0);
            $table->string('class_title', 150);
            $table->decimal('family_code', 8, 0);
            $table->string('family_title', 150);
            $table->decimal('segment_code', 8, 0);
            $table->string('segment_title', 150);            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unspsc_reference');
    }
};

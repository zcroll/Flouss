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
        Schema::create('technology_skills', function (Blueprint $table) {
            $table->char('onetsoc_code', 10);
            $table->string('example', 150);
            $table->decimal('commodity_code', 8, 0);
            $table->char('hot_technology', 1);
            $table->char('in_demand', 1);

            $table->foreign('onetsoc_code')->references('onetsoc_code')->on('occupation_data');
            $table->foreign('commodity_code')->references('commodity_code')->on('unspsc_reference');
                  $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technology_skills');
    }
};

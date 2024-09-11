<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalityDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personality_details', function (Blueprint $table) {
            $table->id();
            $table->string('career_name')->nullable();
            $table->longText('description')->nullable();
            $table->string('trait_score')->nullable();
            $table->string('trait_type')->nullable();
            $table->unsignedBigInteger('job_info_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personality_details');
    }
}

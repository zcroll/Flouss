<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonaScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona_scales', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('persona_id')->nullable();
            $table->integer('scale_id')->nullable();
            $table->decimal('value', 5, 2)->nullable();
            $table->decimal('percentile', 5, 2)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
            
            $table->foreign('persona_id', 'persona_scales_ibfk_1')->references('id')->on('persona');
            $table->foreign('scale_id', 'persona_scales_ibfk_2')->references('id')->on('scales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persona_scales');
    }
}

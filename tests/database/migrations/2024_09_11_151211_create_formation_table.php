<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('nomAr')->nullable();
            $table->text('descriptionAr')->nullable();
            $table->text('descriptionFr')->nullable();
            $table->string('diplomeLibelleAr')->nullable();
            $table->string('EtablissementFr')->nullable();
            $table->string('EtablissementAr')->nullable();
            $table->string('parcoursNomAr')->nullable();
            $table->string('parcoursNomFr')->nullable();
            $table->string('parcoursCode', 50)->nullable();
            $table->string('priorite', 50)->nullable();
            $table->string('diplomeLibelleFr')->nullable();
            $table->string('etablissement_id', 10)->nullable();
            $table->timestamps();
            
            $table->foreign('etablissement_id', 'formation_etablissement_id_foreign')->references('id')->on('etablissement')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formation');
    }
}

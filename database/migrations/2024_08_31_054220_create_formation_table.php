<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('nom', 255)->nullable();
            $table->string('nomAr', 255)->nullable();
            $table->text('descriptionAr')->nullable();
            $table->text('descriptionFr')->nullable();
            $table->string('diplomeLibelleAr', 255)->nullable();
            $table->string('EtablissementFr', 255)->nullable();
            $table->string('EtablissementAr', 255)->nullable();
            $table->string('parcoursNomAr', 255)->nullable();
            $table->string('parcoursNomFr', 255)->nullable();
            $table->string('parcoursCode', 50)->nullable();
            $table->string('priorite', 50)->nullable();
            $table->string('diplomeLibelleFr', 255)->nullable();
            $table->string('etablissement_id', 10)->nullable(); // Foreign key column
            $table->foreign('etablissement_id')->references('id')->on('etablissement')->onDelete('cascade');
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
        Schema::dropIfExists('formation');
    }
};

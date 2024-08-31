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
Schema::create('etablissement', function (Blueprint $table) {
    $table->string('id', 10)->primary();
    $table->string('nom', 255);
    $table->string('nomAr', 255);
    $table->string('sigle', 50)->nullable();
    $table->string('categoryEtablissement', 100)->nullable();
    $table->string('typeEtablissement', 100)->nullable();
    $table->text('adresse')->nullable();
    $table->string('ville', 100)->nullable();
    $table->string('codePostal', 20)->nullable();
    $table->string('telephone', 50)->nullable();
    $table->string('fax', 50)->nullable();
    $table->string('email', 100)->nullable();
    $table->string('siteWeb', 255)->nullable();
    $table->string('regionFr', 255)->nullable();
    $table->string('regionAr', 255)->nullable();
    $table->string('adresseFr', 255)->nullable();
    $table->string('adresseAr', 255)->nullable();
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
    Schema::dropIfExists('etablissement');
}
};

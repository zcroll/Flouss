<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persona', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name')->nullable();
            $table->string('name_plural')->nullable();
            $table->string('a_an', 10)->nullable();
            $table->string('primary_trait')->nullable();
            $table->string('secondary_trait')->nullable();
            $table->text('description')->nullable();
            $table->text('adjectives')->nullable();
            $table->text('strengths')->nullable();
            $table->text('strengths_short')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('personality_paragraph')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persona');
    }
}

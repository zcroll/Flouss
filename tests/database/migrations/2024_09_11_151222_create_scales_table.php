<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scales', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('scale_group')->nullable();
            $table->string('scale_group_slug')->nullable();
            $table->string('name')->nullable();
            $table->string('short_name', 50)->nullable();
            $table->integer('scale_id')->nullable();
            $table->text('definition')->nullable();
            $table->text('short_definition')->nullable();
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
        Schema::dropIfExists('scales');
    }
}

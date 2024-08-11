<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->longText('activity');
            $table->text('category');
            $table->decimal('scale');
            $table->text('interest_type');




            $table->timestamps();
        });
    }
};

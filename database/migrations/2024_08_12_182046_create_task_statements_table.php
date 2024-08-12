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
        Schema::create('task_statements', function (Blueprint $table) {
            $table->char('onetsoc_code', 10);
            $table->decimal('task_id', 8, 0)->primary();
            $table->string('task', 1000);
            $table->string('task_type', 12)->nullable();
            $table->decimal('incumbents_responding', 4, 0)->nullable();
            $table->date('date_updated');
            $table->string('domain_source', 30);

            $table->foreign('onetsoc_code')->references('onetsoc_code')->on('occupation_data');            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_statements');
    }
};

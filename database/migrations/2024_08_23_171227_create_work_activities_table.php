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
        Schema::create('work_activities', function (Blueprint $table) {
            $table->string('onetsoc_code', 10);
            $table->string('element_id', 20);
            $table->string('scale_id', 3);
            $table->decimal('data_value', 5, 2);
            $table->decimal('n', 4, 0)->nullable();
            $table->decimal('standard_error', 7, 4)->nullable();
            $table->decimal('lower_ci_bound', 7, 4)->nullable();
            $table->decimal('upper_ci_bound', 7, 4)->nullable();
            $table->char('recommend_suppress', 1)->nullable();
            $table->char('not_relevant', 1)->nullable();
            $table->date('date_updated');
            $table->string('domain_source', 30);

            $table->foreign('onetsoc_code')->references('onetsoc_code')->on('occupation_data');
            $table->foreign('element_id')->references('element_id')->on('content_model_reference');
            $table->foreign('scale_id')->references('scale_id')->on('scales_reference');            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_activities');
    }
};

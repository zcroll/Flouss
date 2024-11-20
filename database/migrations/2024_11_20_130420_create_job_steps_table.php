<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('job_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_info_id');
            $table->text('step_title_fr');
            $table->text('step_title');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_steps');
    }
};

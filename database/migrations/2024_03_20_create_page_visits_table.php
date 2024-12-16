<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('route_name')->nullable();
            $table->integer('visit_count')->default(0);
            $table->timestamp('last_visit_at');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['path', 'route_name']);
            $table->index('last_visit_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
    }
}; 
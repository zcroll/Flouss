<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_behaviors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('event_type'); // pageview, click, scroll, etc.
            $table->string('page_path');
            $table->string('element_id')->nullable();
            $table->string('element_class')->nullable();
            $table->json('metadata')->nullable(); // Additional event data
            $table->string('user_agent')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['event_type', 'occurred_at']);
            $table->index('page_path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_behaviors');
    }
}; 
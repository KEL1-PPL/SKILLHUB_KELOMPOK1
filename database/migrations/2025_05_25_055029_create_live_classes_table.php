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
        Schema::create('live_classes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->datetime('datetime');
            $table->string('platform', 50);
            $table->string('link', 500);
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->integer('participants_count')->default(0);
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('published');
            $table->timestamps();

            $table->index('datetime');
            $table->index('status');
            
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_classes');
    }
};
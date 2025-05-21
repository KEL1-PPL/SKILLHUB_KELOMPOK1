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
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->dateTime('scheduled_at');
            $table->string('platform');
            $table->string('access_link');
            $table->integer('max_participants')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('live_class_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_class_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('registered_at')->useCurrent();
            $table->boolean('attended')->default(false);
            $table->timestamps();
            
            $table->unique(['live_class_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('live_class_registrations');
        Schema::dropIfExists('live_classes');
    }
};

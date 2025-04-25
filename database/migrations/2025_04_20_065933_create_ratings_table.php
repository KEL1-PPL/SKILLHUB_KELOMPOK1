<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
{
    Schema::create('ratings', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('course_id');  // Menambahkan kolom course_id
        $table->tinyInteger('rating')->unsigned()->default(1); // Menambahkan nilai default dan memastikan rating berada dalam rentang 1-5
        $table->tinyInteger('value')->unsigned()->default(1); 
        $table->text('comment')->nullable();
        $table->timestamps();

        // Menambahkan foreign keys
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');  // Menambahkan relasi ke tabel courses
    });
}


    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};

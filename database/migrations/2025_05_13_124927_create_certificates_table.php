<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // siswa
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // kursus
            $table->string('certificate_number')->unique(); // no sertifikat
            $table->string('certificate_file')->nullable(); // path file sertifikat (PDF)
            $table->date('issued_at'); // tanggal terbit
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};

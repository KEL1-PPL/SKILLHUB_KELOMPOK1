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
        Schema::table('courses', function (Blueprint $table) {
            // Menambahkan kolom rating dan image
            $table->integer('rating')->default(0); // Kolom rating, default 0
            $table->string('image')->nullable(); // Kolom image, nullable jika tidak ada gambar
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Menghapus kolom rating dan image
            $table->dropColumn(['rating', 'image']);
        });
    }
};

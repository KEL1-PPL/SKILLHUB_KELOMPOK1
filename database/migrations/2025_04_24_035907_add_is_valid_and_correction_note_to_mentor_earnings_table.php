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
        Schema::table('mentor_earnings', function (Blueprint $table) {
            $table->boolean('is_valid')->default(true);
            $table->text('correction_note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mentor_earnings', function (Blueprint $table) {
            $table->dropColumn(['is_valid', 'correction_note']);
        });
    }
};

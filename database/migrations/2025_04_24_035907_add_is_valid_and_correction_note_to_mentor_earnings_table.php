<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mentor_incomes', function (Blueprint $table) {
            $table->boolean('is_valid')->default(true);
            $table->string('correction_note')->nullable();
        });
    }

    public function down()
    {
        Schema::table('mentor_incomes', function (Blueprint $table) {
            $table->dropColumn(['is_valid', 'correction_note']);
        });
    }
};

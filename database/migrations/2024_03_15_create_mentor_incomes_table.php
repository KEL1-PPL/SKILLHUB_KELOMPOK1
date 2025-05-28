<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mentor_incomes', function (Blueprint $table) {
            $table->id();
            $table->string('mentorId');
            $table->string('studentId');
            $table->decimal('amount', 10, 2);
            $table->dateTime('transactionDate');
            $table->string('note')->nullable();
            $table->enum('status', ['valid', 'corrected', 'deleted'])->default('valid');
            $table->string('correctionNote')->nullable();
            $table->string('courseId')->nullable();
            $table->string('subscriptionId')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mentor_incomes');
    }
};
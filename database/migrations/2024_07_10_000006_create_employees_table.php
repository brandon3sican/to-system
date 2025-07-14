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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('phone', 20);
            $table->text('address');
            $table->date('birthdate');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('date_hired');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('div_sec_unit_id');
            $table->unsignedBigInteger('employment_status_id');
            $table->decimal('salary', 10, 2);
            $table->unique(['first_name', 'last_name']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

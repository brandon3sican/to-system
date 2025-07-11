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
        Schema::create('travel_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('official_station_id');
            $table->text('destination');
            $table->text('purpose');
            $table->date('departure_date');
            $table->date('arrival_date');
            $table->date('return_date');
            $table->enum('per_deim', ['yes', 'no']);
            $table->integer('assistant')->default(0);
            $table->text('appropriation');
            $table->enum('status', ['Pending', 'Recommended', 'Approved', 'Rejected', 'Cancelled'])->default('Pending');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('recommended_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_orders');
    }
};

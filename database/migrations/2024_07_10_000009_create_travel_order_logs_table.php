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
        Schema::create('travel_order_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_order_id');
            $table->string('action_type', 50);
            $table->unsignedBigInteger('performed_by');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Create index for faster queries
        Schema::table('travel_order_logs', function (Blueprint $table) {
            $table->index(['travel_order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_order_logs');
    }
};

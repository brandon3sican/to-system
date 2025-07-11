<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('official_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('address');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert initial official station
        DB::table('official_stations')->insert([
            [
                'name' => 'DENR-CAR',
                'address' => 'Regional Office, Baguio City',
                'description' => 'Department of Environment and Natural Resources - Cordillera Administrative Region',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('official_stations');
    }
};

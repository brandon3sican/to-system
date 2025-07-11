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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'Employee', 'description' => 'Regular employee role', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Recommender', 'description' => 'Has ability to recommend travel orders', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Approver', 'description' => 'Has ability to approve travel orders', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Administrator', 'description' => 'System administrator with full access', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

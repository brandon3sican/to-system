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
        Schema::create('employment_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert default employment statuses
        DB::table('employment_statuses')->insert([
            ['name' => 'Active', 'description' => 'Currently employed and active', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'On Leave', 'description' => 'Employee is on leave', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Terminated', 'description' => 'Employment has been terminated', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Retired', 'description' => 'Employee has retired', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Probationary', 'description' => 'Employee is in probation period', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Contractual', 'description' => 'Employee is on contract', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_statuses');
    }
};

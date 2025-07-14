<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InsertAdminAccount extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    // Insert Administrator role if it doesn't exist
    $adminRoleId = DB::table('roles')
        ->where('name', 'Administrator')
        ->value('id');

    // Insert Administrator employee record first
    $adminEmployeeId = DB::table('employees')->insertGetId([
        'first_name' => 'System',
        'last_name' => 'Administrator',
        'phone' => '09123456789',
        'address' => 'System Administrator',
        'birthdate' => '1990-01-01',
        'gender' => 'Other',
        'date_hired' => now(),
        'position_id' => 1,
        'div_sec_unit_id' => 1,
        'employment_status_id' => 1,
        'salary' => 0.00,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    // Insert Administrator user
    DB::table('users')->insert([
        'username' => 'admin',
        'password' => Hash::make('admin123'),
        'role_id' => $adminRoleId,
        'employee_id' => $adminEmployeeId,
        'created_at' => now(),
        'updated_at' => now()
    ]);
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete Administrator employee record
        DB::table('employees')
            ->where('first_name', 'System')
            ->where('last_name', 'Administrator')
            ->delete();

        // Delete Administrator user
        DB::table('users')
            ->where('username', 'admin')
            ->delete();
    }
}

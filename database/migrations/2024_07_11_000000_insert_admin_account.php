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

        // Insert Administrator user
        $adminUserId = DB::table('users')->insertGetId([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRoleId,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Insert Administrator employee record
        DB::table('employees')->insert([
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'phone' => '09123456789',
            'address' => 'System Administrator',
            'birthdate' => '1990-01-01',
            'gender' => 'Other',
            'date_hired' => now(),
            'position_id' => 1, // Assuming position_id 1 exists
            'div_sec_unit_id' => 1, // Assuming div_sec_unit_id 1 exists
            'employment_status_id' => 1, // Assuming employment_status_id 1 exists
            'salary' => 0.00,
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

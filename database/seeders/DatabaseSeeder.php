<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Administrator role if it doesn't exist
        $adminRole = Role::firstOrCreate([
            'name' => 'Administrator'
        ]);

        // Create Administrator employee record
        $adminEmployee = Employee::create([
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
        ]);

        // Create Administrator user
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
            'employee_id' => $adminEmployee->id,
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add foreign keys for employees
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreign('div_sec_unit_id')->references('id')->on('div_sec_units');
            $table->foreign('employment_status_id')->references('id')->on('employment_statuses');
        });

        // Add foreign keys for users
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles');
        });

        // Add foreign keys for travel_orders
        Schema::table('travel_orders', function (Blueprint $table) {
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('official_station_id')->references('id')->on('official_stations');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('recommended_by')->references('id')->on('users');
            $table->foreign('approved_by')->references('id')->on('users');
        });

        // Add foreign keys for travel_order_logs
        Schema::table('travel_order_logs', function (Blueprint $table) {
            $table->foreign('travel_order_id')->references('id')->on('travel_orders');
            $table->foreign('performed_by')->references('id')->on('users');
        });

        // Add foreign key for positions
        Schema::table('positions', function (Blueprint $table) {
            $table->foreign('div_sec_unit_id')->references('id')->on('div_sec_units');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys in reverse order of creation
        try {
            DB::statement('ALTER TABLE travel_orders DROP FOREIGN KEY IF EXISTS travel_orders_employee_id_foreign');
            DB::statement('ALTER TABLE travel_orders DROP FOREIGN KEY IF EXISTS travel_orders_official_station_id_foreign');
            DB::statement('ALTER TABLE travel_orders DROP FOREIGN KEY IF EXISTS travel_orders_created_by_foreign');
            DB::statement('ALTER TABLE travel_orders DROP FOREIGN KEY IF EXISTS travel_orders_recommended_by_foreign');
            DB::statement('ALTER TABLE travel_orders DROP FOREIGN KEY IF EXISTS travel_orders_approved_by_foreign');

            DB::statement('ALTER TABLE users DROP FOREIGN KEY IF EXISTS users_role_id_foreign');

            DB::statement('ALTER TABLE employees DROP FOREIGN KEY IF EXISTS employees_position_id_foreign');
            DB::statement('ALTER TABLE employees DROP FOREIGN KEY IF EXISTS employees_div_sec_unit_id_foreign');
            DB::statement('ALTER TABLE employees DROP FOREIGN KEY IF EXISTS employees_employment_status_id_foreign');
        } catch (\Exception $e) {
            // If any foreign key doesn't exist, just continue
        }
    }
};

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
        // Find Development Department ID
        $departmentId = DB::table('div_sec_units')
            ->where('name', 'Development Department')
            ->value('id');

        if ($departmentId) {
            DB::table('positions')->insert([
                'name' => 'Administrator',
                'div_sec_unit_id' => $departmentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('positions')
            ->where('name', 'Administrator')
            ->delete();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the new Admin Manager role
        DB::table('roles')->insert([
            'name' => 'Admin Manager',
            'display_name' => 'Admin Manager',
            'description' => 'Administrative manager with full application and institution management permissions',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->where('name', 'Admin Manager')->delete();
    }
};

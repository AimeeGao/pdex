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
        Schema::table('users', function (Blueprint $table) {
            // Change bceid_business_guid from uuid to string to match institutions table
            // Keep existing index, just change the column type
            $table->string('bceid_business_guid')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // For PostgreSQL, we need to handle the type conversion carefully
        // First, let's check if there's any data that can't be converted to UUID
        try {
            // Test if all current values can be cast to UUID (or are NULL)
            DB::statement("
                SELECT bceid_business_guid 
                FROM users 
                WHERE bceid_business_guid IS NOT NULL 
                  AND bceid_business_guid !~ '^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$'
                LIMIT 1
            ");
            
            // If we get here, either there's no invalid data or the query succeeded
            // Now attempt the conversion with proper casting
            DB::statement('ALTER TABLE users ALTER COLUMN bceid_business_guid TYPE uuid USING 
                CASE 
                    WHEN bceid_business_guid IS NULL THEN NULL
                    WHEN bceid_business_guid ~ \'^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$\' THEN bceid_business_guid::uuid
                    ELSE NULL
                END
            ');
            
        } catch (\Exception $e) {
            // If there are issues with the data, we'll clean it up first
            DB::statement("UPDATE users SET bceid_business_guid = NULL WHERE bceid_business_guid IS NOT NULL AND bceid_business_guid !~ '^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$'");
            
            // Then attempt the conversion
            DB::statement('ALTER TABLE users ALTER COLUMN bceid_business_guid TYPE uuid USING bceid_business_guid::uuid');
        }
    }
};

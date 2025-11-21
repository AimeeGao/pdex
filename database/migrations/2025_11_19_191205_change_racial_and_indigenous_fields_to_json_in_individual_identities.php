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
        // First, convert existing string values to JSON format
        DB::statement("
            UPDATE individual_identities 
            SET racial_identity = CASE 
                WHEN racial_identity IS NOT NULL AND racial_identity != '' 
                THEN '[\"' || racial_identity || '\"]'
                ELSE NULL 
            END
            WHERE racial_identity IS NOT NULL
        ");
        
        DB::statement("
            UPDATE individual_identities 
            SET indigenous_group = CASE 
                WHEN indigenous_group IS NOT NULL AND indigenous_group != '' 
                THEN '[\"' || indigenous_group || '\"]'
                ELSE NULL 
            END
            WHERE indigenous_group IS NOT NULL
        ");
        
        // Now alter the column types using USING clause for PostgreSQL
        DB::statement('ALTER TABLE individual_identities ALTER COLUMN racial_identity TYPE json USING racial_identity::json');
        DB::statement('ALTER TABLE individual_identities ALTER COLUMN indigenous_group TYPE json USING indigenous_group::json');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert arrays back to single values (take first element) using PostgreSQL syntax
        DB::statement("
            UPDATE individual_identities 
            SET racial_identity = racial_identity->>0
            WHERE racial_identity IS NOT NULL 
            AND jsonb_typeof(racial_identity::jsonb) = 'array'
        ");
        
        DB::statement("
            UPDATE individual_identities 
            SET indigenous_group = indigenous_group->>0
            WHERE indigenous_group IS NOT NULL 
            AND jsonb_typeof(indigenous_group::jsonb) = 'array'
        ");
        
        // Change back to string using USING clause
        DB::statement('ALTER TABLE individual_identities ALTER COLUMN racial_identity TYPE varchar(255) USING racial_identity::text');
        DB::statement('ALTER TABLE individual_identities ALTER COLUMN indigenous_group TYPE varchar(255) USING indigenous_group::text');
    }
};

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
        // This migration runs after the table rename, so application_individual_permissions exists
        // and application_api_permissions is created
        
        // Since API Access Permissions now include all tables, we need to decide the migration strategy:
        // Option 1: Keep individual_* tables in individual permissions only (current individual data access)
        // Option 2: Copy all permissions to API permissions (making API comprehensive)
        
        // We'll go with Option 1 to maintain separation of concerns:
        // - Individual permissions (application_individual_permissions): for data consumption with Required/Optional
        // - API permissions (application_api_permissions): for API access with Read/Write permissions
        
        // For now, we'll leave existing individual_* permissions where they are
        // and only move institutional table permissions to API permissions
        
        $existingPermissions = DB::table('application_individual_permissions')->get();
        
        foreach ($existingPermissions as $permission) {
            // Move institutional table permissions to API permissions
            if (!str_starts_with($permission->table_name, 'individual')) {
                DB::table('application_api_permissions')->insert([
                    'application_id' => $permission->application_id,
                    'table_name' => $permission->table_name,
                    'column_name' => $permission->column_name,
                    'display_name' => $permission->display_name,
                    'can_read' => $permission->can_read,
                    'can_write' => $permission->can_write,
                    'access_notes' => $permission->access_notes ?? null,
                    'created_at' => $permission->created_at,
                    'updated_at' => $permission->updated_at,
                ]);
                
                // Remove from application_individual_permissions
                DB::table('application_individual_permissions')
                    ->where('id', $permission->id)
                    ->delete();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Move API permissions back to individual permissions table
        $apiPermissions = DB::table('application_api_permissions')->get();
        
        foreach ($apiPermissions as $permission) {
            DB::table('application_individual_permissions')->insert([
                'application_id' => $permission->application_id,
                'table_name' => $permission->table_name,
                'column_name' => $permission->column_name,
                'display_name' => $permission->display_name,
                'can_read' => $permission->can_read,
                'can_write' => $permission->can_write,
                'is_required' => false, // API permissions don't have is_required
                'access_notes' => $permission->access_notes,
                'created_at' => $permission->created_at,
                'updated_at' => $permission->updated_at,
            ]);
        }
        
        // Clear the API permissions table
        DB::table('application_api_permissions')->truncate();
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
        Schema::table('users', function (Blueprint $table) {
            // Revert back to uuid type, keep existing index
            $table->uuid('bceid_business_guid')->nullable()->change();
        });
    }
};

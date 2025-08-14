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
        Schema::table('application_data_permissions', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('column_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_data_permissions', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });
    }
};

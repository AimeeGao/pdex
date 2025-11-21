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
        Schema::table('individuals', function (Blueprint $table) {
            $table->string('provincial_education_number')->nullable()->unique()->after('government_issued_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individuals', function (Blueprint $table) {
            $table->dropUnique(['provincial_education_number']);
            $table->dropColumn('provincial_education_number');
        });
    }
};

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
        // Add application_id to oauth_clients table
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->unsignedBigInteger('application_id')->nullable()->after('id');
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->index(['application_id', 'is_active']);
        });

        // Remove api_key and api_secret from applications table as they're replaced by OAuth
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['api_key', 'api_secret']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore api_key and api_secret to applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
        });

        // Remove application_id from oauth_clients table
        Schema::table('oauth_clients', function (Blueprint $table) {
            $table->dropForeign(['application_id']);
            $table->dropIndex(['application_id', 'is_active']);
            $table->dropColumn('application_id');
        });
    }
};

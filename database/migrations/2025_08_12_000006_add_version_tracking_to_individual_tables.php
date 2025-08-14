<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        // Add version tracking to individuals table
        Schema::table('individuals', function (Blueprint $table) {
            $table->unsignedInteger('version_number')->default(1)->after('id')->comment('Current version number for this individual record');
            $table->index('version_number');
        });

        // Add version tracking to individual_addresses table
        Schema::table('individual_addresses', function (Blueprint $table) {
            $table->unsignedInteger('version_number')->default(1)->after('user_id')->comment('Version number matching the individuals table version');
            $table->boolean('latest_version')->default(true)->after('is_active')->comment('Indicates if this is the latest version of the address record');
            $table->index(['individual_id', 'version_number']);
            $table->index(['individual_id', 'latest_version']);
        });

        // Add version tracking to individual_employments table
        Schema::table('individual_employments', function (Blueprint $table) {
            $table->unsignedInteger('version_number')->default(1)->after('user_id')->comment('Version number matching the individuals table version');
            $table->boolean('latest_version')->default(true)->after('is_current')->comment('Indicates if this is the latest version of the employment record');
            $table->index(['individual_id', 'version_number']);
            $table->index(['individual_id', 'latest_version']);
        });

        // Add version tracking to individual_identities table
        Schema::table('individual_identities', function (Blueprint $table) {
            $table->unsignedInteger('version_number')->default(1)->after('user_id')->comment('Version number matching the individuals table version');
            $table->boolean('latest_version')->default(true)->after('receives_minority_support_services')->comment('Indicates if this is the latest version of the identity record');
            $table->index(['individual_id', 'version_number']);
            $table->index(['individual_id', 'latest_version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::table('individuals', function (Blueprint $table) {
            $table->dropIndex(['version_number']);
            $table->dropColumn('version_number');
        });

        Schema::table('individual_addresses', function (Blueprint $table) {
            $table->dropIndex(['individual_id', 'version_number']);
            $table->dropIndex(['individual_id', 'latest_version']);
            $table->dropColumn(['version_number', 'latest_version']);
        });

        Schema::table('individual_employments', function (Blueprint $table) {
            $table->dropIndex(['individual_id', 'version_number']);
            $table->dropIndex(['individual_id', 'latest_version']);
            $table->dropColumn(['version_number', 'latest_version']);
        });

        Schema::table('individual_identities', function (Blueprint $table) {
            $table->dropIndex(['individual_id', 'version_number']);
            $table->dropIndex(['individual_id', 'latest_version']);
            $table->dropColumn(['version_number', 'latest_version']);
        });
    }
};

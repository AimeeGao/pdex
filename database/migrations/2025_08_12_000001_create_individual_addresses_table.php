<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('individual_addresses', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->unsignedBigInteger('individual_id')->comment('Reference to individuals table');
            $table->unsignedBigInteger('user_id')->comment('Reference to users table');
            
            // Address Type (could be current, mailing, permanent, etc.)
            $table->string('address_type')->default('current')->comment('Type of address: current, mailing, permanent');
            
            // Address Information
            $table->string('address_line1')->nullable()->comment('Primary address line (street number and name)');
            $table->string('address_line2')->nullable()->comment('Secondary address line (apartment, unit, etc.)');
            $table->string('city')->nullable()->comment('City or municipality');
            $table->string('province')->nullable()->comment('Province, state, or region');
            $table->string('postal_code')->nullable()->comment('Postal code or ZIP code');
            $table->string('country')->nullable()->comment('Country');
            
            // Status and Metadata
            $table->boolean('is_primary')->default(false)->comment('Indicates if this is the primary address');
            $table->boolean('is_active')->default(true)->comment('Indicates if this address is currently active');
            
            // Audit Fields
            $table->timestamps();
            
            // Foreign Key Constraints
            $table->foreign('individual_id')->references('id')->on('individuals')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('individual_id');
            $table->index('user_id');
            $table->index(['individual_id', 'address_type']);
            $table->index(['individual_id', 'is_primary']);
            $table->index(['individual_id', 'is_active']);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('individual_addresses');
    }
};

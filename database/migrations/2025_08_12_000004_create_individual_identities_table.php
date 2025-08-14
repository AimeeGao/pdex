<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('individual_identities', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->unsignedBigInteger('individual_id')->comment('Reference to individuals table');
            $table->unsignedBigInteger('user_id')->comment('Reference to users table');
            
            // Citizenship and Origin
            $table->string('citizenship_status')->nullable()->comment('Citizenship status: citizen, permanent_resident, temporary_resident, refugee');
            $table->string('country_of_birth')->nullable()->comment('Country where individual was born');
            $table->string('language_spoken_at_home')->nullable()->comment('Primary language spoken at home');
            $table->integer('years_in_country')->nullable()->comment('Number of years individual has lived in the country');
            
            // Immigration Status
            $table->boolean('refugee_status')->default(false)->comment('Indicates if individual has refugee status');
            $table->string('immigration_status')->nullable()->comment('Detailed immigration status: permanent_resident, refugee, temporary_resident, work_permit, study_permit');
            
            // Indigenous Identity
            $table->boolean('indigenous_status')->default(false)->comment('Indicates whether individual identifies as Indigenous (First Nations, Métis, Inuit, etc.)');
            $table->string('indigenous_group')->nullable()->comment('Specific Indigenous group: First Nations, Métis, Inuit, Other');
            $table->string('band_affiliation')->nullable()->comment('Name of the band or Indigenous community affiliated with');
            $table->string('indigenous_status_card_number')->nullable()->comment('Government-issued Indigenous status card number, if applicable');
            $table->boolean('is_registered_with_band')->default(false)->comment('Indicates if officially registered with Indigenous band');
            $table->boolean('on_reserve_resident')->default(false)->comment('Indicates if resides on a recognized Indigenous reserve');
            
            // Racial and Cultural Identity
            $table->string('racial_identity')->nullable()->comment('Self-identified racial group: Black, East Asian, South Asian, Latinx, White, Other');
            $table->boolean('is_visible_minority')->default(false)->comment('Indicates if identifies as member of visible minority group');
            
            // Support Services
            $table->boolean('receives_indigenous_support_services')->default(false)->comment('Indicates if receives support services for Indigenous students');
            $table->boolean('receives_minority_support_services')->default(false)->comment('Indicates if receives support services for minority groups');
            
            // Audit Fields
            $table->timestamps();
            
            // Foreign Key Constraints
            $table->foreign('individual_id')->references('id')->on('individuals')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Indexes
            $table->index('individual_id');
            $table->index('user_id');
            $table->index('citizenship_status');
            $table->index('indigenous_status');
            $table->index('is_visible_minority');
            $table->index('refugee_status');
            $table->index(['individual_id', 'indigenous_status']);
            $table->index(['individual_id', 'is_visible_minority']);
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down() {
        Schema::dropIfExists('individual_identities');
    }
};

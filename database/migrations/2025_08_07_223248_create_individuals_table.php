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
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            $table->uuid('guid')->unique();
            $table->uuid('user_guid');
            $table->foreign('user_guid')->references('guid')->on('users')->onDelete('cascade');
            // Identity Numbers
            $table->string('social_insurance_number')->nullable()->unique();
            $table->string('government_issued_id')->nullable();
            $table->string('provincial_education_number')->nullable()->unique();
            // Name & Contact
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('preferred_name')->nullable();
            $table->string('email_address')->unique();
            $table->string('phone_number')->nullable();
            $table->string('alternate_phone_number')->nullable();
            // Demographics (minimal, no address/identity/minority fields)
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('preferred_pronouns')->nullable();
            // Health & Accessibility
            $table->boolean('disability_status')->default(false);
            $table->text('accommodation_needs')->nullable();
            // Status & Metadata
            $table->string('status')->default('active');
            $table->string('verification_status')->default('unverified');
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            // Audit Fields
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            // Indexes
            $table->index(['status', 'verification_status']);
            $table->index('user_guid');
            $table->index('guid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individuals');
    }
};

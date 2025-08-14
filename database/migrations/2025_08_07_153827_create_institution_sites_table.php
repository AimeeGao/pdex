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
        Schema::create('institution_sites', function (Blueprint $table) {
            $table->id();
            $table->string('guid', 32)->index()->unique();
            $table->string('institution_guid', 32);
            $table->foreign('institution_guid')->references('guid')->on('institutions')
                ->onDelete('cascade');
            $table->string('operating_name')->nullable();

            // Contact Information
            $table->string('primary_phone');
            $table->string('primary_email');
            $table->string('website')->nullable();
            
            // Regulation & Compliance
            $table->string('regulating_body');
            $table->string('other_regulating_body')->nullable();
            $table->date('established_date')->nullable();
            $table->boolean('info_sharing_agreement')->default(false);
            
            // Primary Contact Person
            $table->string('contact_first_name');
            $table->string('contact_last_name');
            $table->string('contact_email');
            $table->string('contact_phone');
            
            // Address Information
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('city');
            $table->string('province_state');
            $table->string('country')->default('Canada');
            $table->string('postal_code');
            
            // Status & Classification
            $table->boolean('public')->default(false);
            $table->boolean('active_status')->default(false);
            $table->string('standing_status')->nullable()->comment('Good Standing, Probation, etc.');
            $table->string('economic_region')->nullable()->comment('Cariboo, Kootenay, Mainland/Southwest, Nechako, etc.');
            
            // Metadata
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['active_status', 'standing_status']);
            $table->index('economic_region');
            $table->index(['city', 'province_state']);
            $table->index('institution_guid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_sites');
    }
};

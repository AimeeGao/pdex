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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index()->unique();
            $table->string('description')->nullable();
            $table->string('bcsc_redirect_url')->nullable();
            $table->string('idir_redirect_url')->nullable();
            $table->string('bceid_redirect_url')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('status')->default('inactive'); // active, inactive, offline

            
            // Add Security Officer approval columns
            $table->string('security_approval_status')->default('pending'); // pending, approved, rejected
            $table->text('security_approval_notes')->nullable();
            $table->timestamp('security_approved_at')->nullable();
            $table->unsignedBigInteger('security_approved_by')->nullable();
            
            // Add Privacy Officer approval columns
            $table->string('privacy_approval_status')->default('pending'); // pending, approved, rejected
            $table->text('privacy_approval_notes')->nullable();
            $table->timestamp('privacy_approved_at')->nullable();
            $table->unsignedBigInteger('privacy_approved_by')->nullable();
            
            // Add alert message columns
            $table->text('active_alert_message')->nullable();
            $table->text('offline_alert_message')->nullable();
            $table->timestamp('offline_start_time')->nullable();
            $table->timestamp('offline_end_time')->nullable();
            
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->boolean('bcsc_enabled')->default(false);
            $table->boolean('idir_enabled')->default(false);
            $table->boolean('bceid_enabled')->default(false);
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->boolean('stra_provided')->default(false);
            $table->boolean('pia_provided')->default(false);
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->softDeletes();
                        
            // Add foreign key constraints
            $table->foreign('security_approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('privacy_approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};

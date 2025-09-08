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
        Schema::create('individual_application_permission_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('individual_id')->constrained('individuals')->onDelete('cascade');
            $table->foreignId('application_id')->constrained('applications')->onDelete('cascade');
            $table->foreignId('application_individual_permission_id')->constrained('application_individual_permissions')->onDelete('cascade');
            $table->boolean('is_selected')->default(false);
            $table->timestamps();
            
            // Ensure unique combination
            $table->unique(['individual_id', 'application_id', 'application_individual_permission_id'], 'individual_app_permission_unique');
            
            // Add indexes for performance
            $table->index(['individual_id', 'application_id']);
            $table->index(['application_id', 'application_individual_permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_application_permission_selections');
    }
};

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
        Schema::create('institution_relationships', function (Blueprint $table) {
            $table->id();
            
            // Institution relationships using GUIDs
            $table->string('institution_a_guid', 32);
            $table->string('institution_b_guid', 32);
            
            // Foreign key constraints
            $table->foreign('institution_a_guid')->references('guid')->on('institutions')
                ->onDelete('cascade');
            $table->foreign('institution_b_guid')->references('guid')->on('institutions')
                ->onDelete('cascade');
            
            // Relationship metadata
            $table->string('relationship_type'); // 'geographic', 'academic', 'partnership', 'consortium', 'transfer_agreement', etc.
            $table->string('relationship_reason'); // 'Same city', 'Multiple campuses', 'Transfer agreement', etc.
            $table->text('description')->nullable(); // Detailed description
            
            // Status and validity
            $table->boolean('is_active')->default(true);
            $table->date('effective_date')->nullable();
            $table->date('expiry_date')->nullable();
            
            // Additional metadata
            $table->json('metadata')->nullable(); // For storing additional flexible data
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['institution_a_guid', 'institution_b_guid']);
            $table->index(['relationship_type', 'is_active']);
            $table->index('relationship_type');
            
            // Ensure we don't have duplicate relationships (bidirectional check)
            $table->unique(['institution_a_guid', 'institution_b_guid'], 'unique_institution_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_relationships');
    }
};

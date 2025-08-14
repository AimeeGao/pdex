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
        Schema::create('individual_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('individual_id')->index();
            $table->integer('version_number')->default(1);
            $table->json('data'); // Store the complete individual data as JSON
            $table->string('created_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('individual_id')->references('id')->on('individuals')->onDelete('cascade');
            
            // Ensure unique version numbers per individual
            $table->unique(['individual_id', 'version_number']);
            
            // Index for efficient queries
            $table->index(['individual_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_versions');
    }
};

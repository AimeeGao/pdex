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
        Schema::create('application_api_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->onDelete('cascade');
            $table->string('table_name');
            $table->string('column_name');
            $table->string('display_name')->nullable();
            $table->boolean('can_read')->default(false);
            $table->boolean('can_write')->default(false);
            $table->text('access_notes')->nullable();
            $table->timestamps();

            // Ensure unique combination of application_id, table_name, and column_name
            $table->unique(['application_id', 'table_name', 'column_name']);
            
            // Index for performance
            $table->index(['application_id', 'table_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_api_permissions');
    }
};

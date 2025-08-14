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
        Schema::create('application_data_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->string('table_name'); // individuals, individual_addresses, etc.
            $table->string('column_name');
            $table->boolean('can_read')->default(false);
            $table->boolean('can_write')->default(false);
            $table->text('access_notes')->nullable(); // Optional notes about why access is granted
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->unique(['application_id', 'table_name', 'column_name'], 'app_table_column_unique');
            $table->index(['application_id', 'table_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_data_permissions');
    }
};

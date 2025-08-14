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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('guid', 32)->index()->unique();
            
            // Business Information
            $table->string('bceid_business_guid')->nullable()->index();
            $table->string('legal_operating_name');
            $table->string('institution_type'); // College, University, Teaching University, etc.
            $table->string('dli')->nullable()->comment('Designated Learning Institution number');
            $table->boolean('active_status')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};

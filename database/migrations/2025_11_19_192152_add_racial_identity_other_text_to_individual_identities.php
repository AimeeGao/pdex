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
        Schema::table('individual_identities', function (Blueprint $table) {
            $table->string('racial_identity_other_text', 200)->nullable()->after('racial_identity')
                ->comment('Free text response for "Another Racial Identity" option (200 character limit, UTF-8 encoding)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('individual_identities', function (Blueprint $table) {
            $table->dropColumn('racial_identity_other_text');
        });
    }
};

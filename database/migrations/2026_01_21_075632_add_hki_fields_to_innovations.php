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
        Schema::table('innovations', function (Blueprint $table) {
            $table->string('hki_registration_number')->nullable()->after('hki_status');
            $table->string('hki_patent_number')->nullable()->after('hki_registration_number');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('innovations', function (Blueprint $table) {
            //
        });
    }
};

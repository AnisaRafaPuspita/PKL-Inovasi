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
            $table->string('ki_type')->nullable()->after('hki_patent_number');
            $table->string('ki_status')->nullable()->after('ki_type');
            $table->string('ki_number')->nullable()->after('ki_status');
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('innovation_rankings', function (Blueprint $table) {
            $table->renameColumn('pamphlet', 'photo');
        });
    }

    public function down(): void
    {
        Schema::table('innovation_rankings', function (Blueprint $table) {
            $table->renameColumn('photo', 'pamphlet');
        });
    }
};

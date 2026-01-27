<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('innovation_rankings', function (Blueprint $table) {
            $table->text('description')->nullable()->after('achievement');

            $table->dropConstrainedForeignId('innovation_id');

            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('innovation_rankings', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('achievement');

            $table->foreignId('innovation_id')->constrained()->cascadeOnDelete()->after('rank');

            $table->dropColumn('description');
        });
    }
};

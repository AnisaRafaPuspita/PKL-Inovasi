<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('innovator_of_the_month', function (Blueprint $table) {

            if (!Schema::hasColumn('innovator_of_the_month', 'innovation_id')) {
                $table->foreignId('innovation_id')
                    ->nullable()
                    ->constrained('innovations')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('innovator_of_the_month', 'description')) {
                $table->text('description')->nullable();
            }

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('innovator_of_the_month', function (Blueprint $table) {
            //
        });
    }
};

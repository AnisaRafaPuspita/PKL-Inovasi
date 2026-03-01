<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('innovator_of_the_month', function (Blueprint $table) {
            $table->dropUnique('innovator_of_the_month_month_year_unique');
        });

        Schema::table('innovator_of_the_month', function (Blueprint $table) {
            $table->dropColumn(['month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::table('innovator_of_the_month', function (Blueprint $table) {
            $table->tinyInteger('month')->unsigned();
            $table->smallInteger('year')->unsigned();
            $table->unique(['month', 'year'], 'innovator_of_the_month_month_year_unique');
        });
    }
};
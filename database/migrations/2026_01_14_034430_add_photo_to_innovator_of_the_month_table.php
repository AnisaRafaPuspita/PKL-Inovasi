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
            $table->string('photo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('innovator_of_the_month', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }


};

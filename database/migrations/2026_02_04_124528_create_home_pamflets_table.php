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
        Schema::create('home_pamflets', function (Blueprint $table) {
            $table->id();
            $table->string('pamflet_1')->nullable();
            $table->string('pamflet_2')->nullable();
            $table->string('pamflet_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_pamflets');
    }
};

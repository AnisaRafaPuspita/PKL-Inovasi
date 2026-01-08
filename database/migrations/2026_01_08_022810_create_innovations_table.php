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
        Schema::create('innovations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->string('partner')->nullable();
            $table->string('hki_status')->nullable();
            $table->string('video_url')->nullable();
            $table->text('description');
            $table->text('review')->nullable();
            $table->text('advantages')->nullable();
            $table->text('impact')->nullable();
            $table->boolean('is_impact')->default(false);
            $table->enum('status', ['draft', 'pending', 'published'])->default('draft');
            $table->unsignedInteger('views_count')->default(0);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('innovations');
    }
};

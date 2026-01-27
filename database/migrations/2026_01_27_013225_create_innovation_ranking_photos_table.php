<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('innovation_ranking_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('innovation_ranking_id')
                ->constrained('innovation_rankings')
                ->cascadeOnDelete();
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('innovation_ranking_photos');
    }
};

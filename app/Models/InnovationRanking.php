<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnovationRanking extends Model
{
    use HasFactory;

    protected $table = 'innovation_rankings';

    protected $fillable = [
        'rank',
        'year',
        'achievement',
        'description',
        'reference_link',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'rank' => 'integer',
        'is_active' => 'boolean',
    ];

    public function photos()
    {
        return $this->hasMany(InnovationRankingPhoto::class, 'innovation_ranking_id');
    }
}

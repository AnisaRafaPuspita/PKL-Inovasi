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
        'achievement',
        'description',
        'reference_link',
        'logo',
    ];

    protected $casts = [
        'rank' => 'integer',
    ];

    public function photos()
    {
        return $this->hasMany(InnovationRankingPhoto::class, 'innovation_ranking_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InnovationRankingPhoto extends Model
{
    use HasFactory;

    protected $table = 'innovation_ranking_photos';

    protected $fillable = [
        'innovation_ranking_id',
        'path',
    ];

    public function ranking()
    {
        return $this->belongsTo(InnovationRanking::class, 'innovation_ranking_id');
    }

}

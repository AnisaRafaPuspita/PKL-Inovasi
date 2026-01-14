<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InnovationRanking extends Model
{
    protected $table = 'innovation_rankings';

    protected $fillable = [
        'rank',
        'innovation_id',
        'achievement',
        'status',
        'image',
    ];

    protected $casts = [
        'rank' => 'integer',
    ];

    public function innovation()
    {
        return $this->belongsTo(Innovation::class, 'innovation_id');
    }
}

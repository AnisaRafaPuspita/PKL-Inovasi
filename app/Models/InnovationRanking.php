<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Innovation;

class InnovationRanking extends Model
{
    use HasFactory;

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

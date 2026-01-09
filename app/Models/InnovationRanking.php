<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnovationRanking extends Model
{
    use HasFactory;

    protected $fillable = [
        'rank',
        'innovation_id',
        'achievement',
        'status',
        'image',
    ];

    public function innovation()
    {
        return $this->belongsTo(Innovation::class);
    }
}

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
    'pamphlet',
    ];



    protected $casts = [
        'rank' => 'integer',
    ];
}

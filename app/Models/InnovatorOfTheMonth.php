<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InnovatorOfTheMonth extends Model
{
    protected $table = 'innovator_of_the_month';

    protected $fillable = [
        'innovator_id',
        'innovator_name',
        'faculty',
        'description',
        'innovation_id',
        'photo',
        'month',
        'year',
    ];

    public function innovation()
    {
        return $this->belongsTo(\App\Models\Innovation::class, 'innovation_id');
    }
}

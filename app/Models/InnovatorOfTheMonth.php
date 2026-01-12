<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnovatorOfTheMonth extends Model
{
    use HasFactory;

    protected $table = 'innovator_of_the_month';

    protected $fillable = [
        'innovator_id',
        'month',
        'year',
    ];

    public function innovator()
    {
        return $this->belongsTo(Innovator::class);
    }
}

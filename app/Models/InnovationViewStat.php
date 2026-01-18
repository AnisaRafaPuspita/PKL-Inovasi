<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InnovationViewStat extends Model
{
    protected $fillable = ['date', 'views'];
    protected $casts = ['date' => 'date'];
}

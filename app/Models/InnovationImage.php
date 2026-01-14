<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnovationImage extends Model
{
    protected $fillable = [
        'innovation_id',
        'image_path',
        'is_primary',
    ];

    public function innovation()
    {
        return $this->belongsTo(Innovation::class);
    }
}

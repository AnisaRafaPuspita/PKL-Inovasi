<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Innovation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'partner',
        'hki_status',
        'video_url',
        'description',
        'review',
        'advantages',
        'impact',
        'is_impact',
        'status',
        'views_count',
        'image_url',
    ];

    /* ================= RELATIONS ================= */

    public function innovators()
    {
        return $this->belongsToMany(Innovator::class);
    }

    public function ranking()
    {
        return $this->hasOne(InnovationRanking::class);
    }
}

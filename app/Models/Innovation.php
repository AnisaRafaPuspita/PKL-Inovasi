<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Innovation extends Model
{
    protected $table = 'innovations';

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
    ];

    protected $casts = [
        'is_impact' => 'boolean',
        'views_count' => 'integer',
    ];

    public function innovators()
    {
        return $this->belongsToMany(
            Innovator::class,
            'innovation_innovator',
            'innovation_id',
            'innovator_id'
        );
    }

    public function permission()
    {
        return $this->hasOne(\App\Models\InnovationPermission::class);
    }

}

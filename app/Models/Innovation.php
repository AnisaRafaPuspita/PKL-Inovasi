<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Innovator;
use App\Models\InnovationImage;
use App\Models\InnovationRanking;
use App\Models\InnovationPermission;

class Innovation extends Model
{
    use HasFactory;

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
        'image_url',
        'source',
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

    public function images()
    {
        return $this->hasMany(InnovationImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(InnovationImage::class)
            ->where('is_primary', true);
    }

    public function ranking()
    {
        return $this->hasOne(InnovationRanking::class);
    }

    public function permission()
    {
        return $this->hasOne(InnovationPermission::class);
    }

    public function scopeImpact($query)
    {
        return $query->whereNotNull('impact')
            ->where('impact', '!=', '');
    }

    public function scopeProduct($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('impact')
                ->orWhere('impact', '');
        });
    }

    public function getIsImpactfulAttribute(): bool
    {
        return trim((string) ($this->impact ?? '')) !== '';
    }

    public function setImpactAttribute($value)
    {
        $impact = trim((string) $value);

        $this->attributes['impact'] = $impact !== '' ? $impact : null;
        $this->attributes['is_impact'] = $impact !== '' ? 1 : 0;
    }
}

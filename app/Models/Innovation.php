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
        'image_url', // legacy / fallback foto lama
        'source',
    ];

    protected $casts = [
        'is_impact' => 'boolean',
        'views_count' => 'integer',
    ];

    /* ================= RELATIONS ================= */

    // many-to-many ke innovator (pivot custom)
    public function innovators()
    {
        return $this->belongsToMany(
            Innovator::class,
            'innovation_innovator',
            'innovation_id',
            'innovator_id'
        );
    }

    // multiple images (slider)
    public function images()
    {
        return $this->hasMany(InnovationImage::class);
    }

    // primary image (opsional)
    public function primaryImage()
    {
        return $this->hasOne(InnovationImage::class)
                    ->where('is_primary', true);
    }

    // ranking inovasi (admin)
    public function ranking()
    {
        return $this->hasOne(InnovationRanking::class);
    }

    // permission inovasi (admin)
    public function permission()
    {
        return $this->hasOne(InnovationPermission::class);
    }
}

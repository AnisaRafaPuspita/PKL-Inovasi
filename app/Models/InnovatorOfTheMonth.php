<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Innovator;
use App\Models\Innovation;

class InnovatorOfTheMonth extends Model
{
    use HasFactory;

    protected $table = 'innovator_of_the_month';

    protected $fillable = [
        'innovator_id',
        'innovation_id',
        'description',
        'photo',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'integer',
    ];


    /* ================= RELATIONS ================= */

    // relasi utama (dipakai di home & admin)
    public function innovator()
    {
        return $this->belongsTo(Innovator::class);
    }

    // relasi ke inovasi (opsional / legacy)
    public function innovation()
    {
        return $this->belongsTo(Innovation::class, 'innovation_id');
    }
}

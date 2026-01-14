<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Faculty;
use App\Models\Innovation;
use App\Models\User;

class Innovator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'faculty_id',
        'photo',
        'bio',
    ];

    /* ================= RELATIONS ================= */

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function innovations()
    {
        return $this->belongsToMany(
            Innovation::class,
            'innovation_innovator'
        );
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}

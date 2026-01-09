<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(Innovation::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Innovator extends Model
{
    protected $fillable = [
        'name',
        'faculty_id',
        'photo',
        'bio'
    ];

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Innovation;

class InnovationPermission extends Model
{
    protected $fillable = ['innovation_id', 'status', 'reviewed_at'];

    public function innovation()
    {
        return $this->belongsTo(Innovation::class);
    }
}

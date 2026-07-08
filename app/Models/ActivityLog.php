<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $guarded = [];

    public function causer()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeAndDislike extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'likeable_id',
        'likeable_type',
    ];

    public function likeable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
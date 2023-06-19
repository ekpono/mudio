<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaView extends Model
{
    use HasFactory;

    protected $fillable = ['media_id', 'ip_address', 'visits', 'headers', 'user_id'];
}

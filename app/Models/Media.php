<?php

namespace App\Models;

use App\Enums\MediaVisibility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Media extends Model
{
    use HasFactory, SoftDeletes, HasTags;

    protected $fillable = [
        'user_id', 'file_name', 'title', 'description', 'uploaded_from_ip', 'file_type', 'path',
        'extension', 'poster', 'source', 'visibility', 'response', 'updated_by', 'deleted_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => config('app.asset_url').'/storage/app/'.$value
        );
    }

    protected function poster(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                if ($this->file_type === 'audio') {
                    return config('app.asset_url').'/storage/app/public/'.$value;
                }

                return config('app.asset_url').'/storage/'.$value;
            }
        );
    }

    public function scopeAudio(Builder $query): void
    {
        $query->where('file_type', 'audio');
    }

    public function scopeVideo(Builder $query): void
    {
        $query->where('file_type', 'video');
    }

    public function scopePublic(Builder $query): void
    {
        $query->where('visibility', MediaVisibility::PUBLIC->value);
    }

    public function scopeAuth(Builder $query): void
    {
        $query->where('user_id', auth()->id());
    }
}

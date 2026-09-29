<?php

namespace App\Models;

use App\Models\Album;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlbumPhoto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['user_id','title','title_en','img','thumb_img','album_id','is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    protected static function booted()
    {
        static::saved(function ($photo) {
            \App\Services\HomeCacheService::clearHomeCache();
        });

        static::deleted(function ($photo) {
            \App\Services\HomeCacheService::clearHomeCache();
        });

        static::restored(function ($photo) {
            \App\Services\HomeCacheService::clearHomeCache();
        });
    }

    protected $hidden = [
        'album_id',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}

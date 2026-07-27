<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class NewsAlbumPhoto extends Pivot implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;
    protected $table = 'news_album_photo';

    public $incrementing = true;

    protected $fillable = [
        'news_id',
        'album_photo_id',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }

    public function albumPhoto()
    {
        return $this->belongsTo(AlbumPhoto::class);
    }

}

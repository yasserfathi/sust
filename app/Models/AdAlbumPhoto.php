<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AdAlbumPhoto extends Pivot implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;
    protected $table = 'ad_album_photo';

    public $incrementing = true;

    protected $fillable = [
        'ad_id',
        'album_photo_id',
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

    public function albumPhoto()
    {
        return $this->belongsTo(AlbumPhoto::class);
    }

}

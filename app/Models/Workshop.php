<?php

namespace App\Models;

use App\Models\College;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AlbumPhoto;

class Workshop extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;

    public $table = 'workshops';

    protected $fillable = ['college_id', 'title', 'slug', 'type', 'lang', 'workshop_date', 'detail_portion', 'detail', 'keywords', 'file', 'active', 'auth_id'];

    protected $auditInclude = [
        'college_id', 'title', 'slug', 'type', 'lang', 'news_date', 'detail_portion', 'detail', 'keywords', 'active', 'auth_id', 'file'
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function photos()
    {
        return $this->belongsToMany(AlbumPhoto::class, 'workshop_album_photo')
                    ->withPivot('id')
                    ->orderBy('workshop_album_photo.id');
    }

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Efficiently fetches the FIRST image for lists using a Subquery.
     * This avoids N+1 queries.
     */
    public function scopeWithFirstImage($query)
    {
        return $query->addSelect(['first_image' => AlbumPhoto::select('thumb_img')
            ->join('workshop_album_photo', 'album_photos.id', '=', 'workshop_album_photo.album_photo_id')
            ->whereColumn('workshop_album_photo.workshop_id', 'workshops.id')
            ->orderBy('workshop_album_photo.id', 'asc')
            ->limit(1)
        ]);
    }
}

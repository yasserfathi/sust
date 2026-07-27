<?php

namespace App\Models;

use App\Models\College;

use App\Models\AlbumPhoto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ad extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;

    protected $fillable = ['college_id', 'title', 'slug', 'lang', 'ad_date', 'detail_portion', 'detail', 'keywords', 'active', 'duration', 'auth_id', 'file'];

    protected $auditInclude = [
        'college_id',
        'title',
        'slug',
        'lang',
        'ad_date',
        'detail_portion',
        'detail',
        'keywords',
        'photos',
        'active',
        'duration',
        'auth_id',
        'file'
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function photos()
    {
        return $this->belongsToMany(AlbumPhoto::class, 'ad_album_photo')
            ->withPivot('id')
            ->orderBy('ad_album_photo.id');
    }

    public function scopeWithFirstImage($query)
    {
        return $query->addSelect([
            'first_image' => AlbumPhoto::select('thumb_img')
                ->join('ad_album_photo', 'album_photos.id', '=', 'ad_album_photo.album_photo_id')
                ->whereColumn('ad_album_photo.ad_id', 'ads.id')
                ->orderBy('ad_album_photo.id', 'asc')
                ->limit(1)
        ]);
    }

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
        'priority' => 'boolean',
        'ad_date' => 'date:Y-m-d',
    ];

    protected function adDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value,
            set: fn($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }

}

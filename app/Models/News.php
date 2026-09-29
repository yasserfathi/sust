<?php

namespace App\Models;

use App\Models\College;
use App\Models\AlbumPhoto; // Import the Photo model
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute; // Fixed: Added missing import
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class News extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'college_id', 'title', 'slug', 'lang', 'news_date', 'detail_portion', 
        'detail', 'keywords', 'active', 'auth_id', 'file'
    ];

    protected $auditInclude = [
        'college_id', 'title', 'slug', 'lang', 'news_date', 'detail_portion', 
        'detail', 'keywords', 'active', 'auth_id', 'file'
    ];

    protected $casts = [
        'active' => 'boolean',
        'priority' => 'boolean',
        'news_date' => 'date:Y-m-d',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function photos()
    {
        return $this->belongsToMany(AlbumPhoto::class, 'news_album_photo')
                    ->withPivot('id') // Optional: if you need pivot ID
                    ->orderBy('news_album_photo.id'); // Keeps photos in order of selection
    }

    protected static function booted()
    {
        static::saved(function ($news) {
            \App\Services\HomeCacheService::clearHomeCache();
        });

        static::deleted(function ($news) {
            \App\Services\HomeCacheService::clearHomeCache();
        });

        static::restored(function ($news) {
            \App\Services\HomeCacheService::clearHomeCache();
        });
    }

    /* -----------------------------------------------------------------
     |  Accessors & Mutators
     | -----------------------------------------------------------------
     */
    protected function newsDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null,
        );
    }

    /* -----------------------------------------------------------------
     |  Scopes
     | -----------------------------------------------------------------
     */

    /**
     * Efficiently fetches the FIRST image for lists using a Subquery.
     * This avoids N+1 queries and avoids complex Joins.
     */
    public function scopeWithFirstImage($query)
    {
        return $query->addSelect(['first_image' => AlbumPhoto::select('thumb_img')
            ->join('news_album_photo', 'album_photos.id', '=', 'news_album_photo.album_photo_id')
            ->whereColumn('news_album_photo.news_id', 'news.id')
            ->orderBy('news_album_photo.id', 'asc') // Get the first photo attached
            ->limit(1)
        ]);
    }
}
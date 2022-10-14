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
    protected $fillable = ['user_id','title','title_en','img'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}

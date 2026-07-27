<?php

namespace App\Models;

use App\Models\Album;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffAlbumPhoto extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['user_id','auth_id','title','title_en','img','thumb_img','album_id'];

    // protected $hidden = [
    //     'user_id'
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

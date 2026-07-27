<?php

namespace App\Models;

use App\Models\AlbumPhoto;
use App\Models\College;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Album extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['college_id','title','title_en','description','keywords','active','user_id'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function photos()
    {
        return $this->hasMany(AlbumPhoto::class);
    }

    protected $casts = [
        'active' => 'boolean',
    ];
}

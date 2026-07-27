<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollegeGallery extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;
    protected $fillable = ['college_id', 'photos', 'user_id'];

    protected $auditInclude = ['college_id', 'photos', 'user_id'];

    protected $appends = ['photos_count'];

    public function getPhotosCountAttribute()
    {
        if (empty($this->photos)) {
            return 0;
        }

        return count(explode(',', $this->photos));
    }

     public function college()
    {
        return $this->belongsTo(College::class);
    }
}

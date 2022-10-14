<?php

namespace App\Models;

use App\Models\College;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Album extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['user_id','college_id','title','title_en','active'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}

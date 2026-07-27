<?php

namespace App\Models;

use App\Models\College;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CollegeStrategic extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['college_id','lang','vision','mission','goals','keywords','auth_id'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }
}

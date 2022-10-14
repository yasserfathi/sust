<?php

namespace App\Models;

use App\Models\College;

use App\Models\StaffEmploy;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['user_id','college_id','name','name_en','active'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function staff()
    {
        return $this->hasMany(StaffEmploy::class);
    }
}

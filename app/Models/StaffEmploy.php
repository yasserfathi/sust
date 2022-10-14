<?php

namespace App\Models;

use App\Models\User;

use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffEmploy extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['user_id','department_id','job_title','job_title_en','rank','hire_date',
                           'specialty','subspecialty','specialty_en','subspecialty_en'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
<?php

namespace App\Models;

use App\Models\Department;
use App\Models\AcademicCourse;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicProgram extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;
    protected $fillable = [
        'department_id',
        'program_type',
        'program_name',
        'program_name_en',
        'NOOFYEARSNO',
        'NOOFSEM',
        'file',
        'active',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courses()
    {
        return $this->hasMany(AcademicCourse::class, 'program_id');
    }

    protected $casts = [
        'active' => 'boolean',
        'NOOFYEARSNO' => 'integer',
        'NOOFSEM' => 'integer',
    ];

}

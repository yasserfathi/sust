<?php

namespace App\Models;

use App\Models\Department;


use App\Models\StaffEmploy;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'department_id', 'name', 'name_en', 'keywords', 'description', 'keywords_ar', 'description_ar', 'active'];

    protected $hidden = [
        'department_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /* public function school()
    {
        return $this->belongsTo(School::class);
    }

    */
    public function staff()
    {
        return $this->hasMany(StaffEmploy::class);
    }

    protected $casts = [
        'active' => 'boolean',
    ];

}

<?php

namespace App\Models;

use App\Models\Department;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class College extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'name', 'name_en', 'slug', 'active', 'logo', 'logo_en', 'banner', 'college_type'];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $collegeTypes = [
        'college' => 'كلية',
        'deanship' => 'عمادة',
        'center' => 'مركز',
        'secretariat' => 'أمانة',
    ];

    public function getCollegeTypeAttribute($value)
    {
        return $this->collegeTypes[$value] ?? $value;
    }

    // For forms/select options
    public function getCollegeTypeOptions()
    {
        return $this->collegeTypes;
    }

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}

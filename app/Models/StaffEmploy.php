<?php

namespace App\Models;

use App\Models\User;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffEmploy extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;
    protected $fillable = [
        'id',
        'user_id',
        'department_id',
        'grade',
        'grade_en',
        'hire_date',
        'specialty',
        'subspecialty',
        'specialty_en',
        'subspecialty_en',
        'auth_id',
        'active'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'hire_date' => 'date:Y-m-d',
    ];

    protected function hireDate(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn($value) => $value, // Already formatted when saved
            set: fn($value) => Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d'),
        )->shouldCache();
    }

    protected function grade(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value) {
                $translations = [
                    'Professor' => 'استاذ',
                    'Associate Professor' => 'استاذ مشارك',
                    'Assistant Professor' => 'استاذ مساعد',
                    'Lecturer' => 'محاضر',
                    'Teaching Assistant' => 'مساعد تدريس',
                    'Teaching assistant' => 'مساعد تدريس',
                ];
                if (!empty($value) && isset($translations[$value])) {
                    return $translations[$value];
                }
                if ($value === 'مساعد تدريس ج') {
                    return 'مساعد تدريس';
                }
                if ($value === 'الاستاذ') {
                    return 'استاذ';
                }
                return $value;
            }
        );
    }

    protected function gradeEn(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: function ($value, $attributes) {
                $translations = [
                    'الاستاذ' => 'Professor',
                    'استاذ' => 'Professor',
                    'استاذ مشارك' => 'Associate Professor',
                    'استاذ مساعد' => 'Assistant Professor',
                    'محاضر' => 'Lecturer',
                    'مساعد تدريس' => 'Teaching Assistant',
                    'مساعد تدريس ج' => 'Teaching Assistant',
                ];
                if (!empty($value) && isset($translations[$value])) {
                    return $translations[$value];
                }
                if (!empty($value)) {
                    return $value;
                }
                $title = $attributes['grade'] ?? '';
                return $translations[$title] ?? $title;
            }
        );
    }


}

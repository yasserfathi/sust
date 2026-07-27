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
        'job_title',
        'job_title_en',
        'rank',
        'rank_en',
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
}

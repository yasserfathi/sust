<?php

namespace App\Models;

use App\Models\CategoryPage;
use App\Models\Department;
use App\Models\StaffEmploy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'name_en',
        'univ_no',
        'email',
        'phone',
        'img',
        'thumb_img',
        'role',
        'active',
        'password',
        'auth_id',
        'user_id'
    ];

    protected $auditInclude = [
        'name',
        'name_en',
        'univ_no',
        'email',
        'phone',
        'role',
        'active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'auth_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    public function staff()
    {
        return $this->hasMany(StaffEmploy::class);
    }

    public function staff_latest()
    {
        return $this->hasOne(StaffEmploy::class)->latestOfMany('hire_date');
    }

    public function staff_latest_by_id()
    {
        return $this->hasOne(StaffEmploy::class)->latestOfMany('id');
    }

    public function categorypages()
    {
        return $this->belongsToMany(CategoryPage::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public static function resolve()
    {
        return Auth::id();
    }
}
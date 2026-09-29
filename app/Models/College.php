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

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    protected static function booted()
    {
        static::saved(function ($college) {
            \App\Services\HomeCacheService::clearHomeCache();
        });

        static::deleted(function ($college) {
            \App\Services\HomeCacheService::clearHomeCache();
        });

        static::created(function ($college) {
            // استثناء "الإدارة" العامة للجامعة (Main Administration) لأن ألبوماتها مخصصة يدوياً
            if ($college->id == 1 || $college->slug === 'administration') {
                return;
            }

            $college->albums()->create([
                'title'       => 'معرض صور ' . $college->name,
                'title_en'    => 'Photo Gallery - ' . ($college->name_en ?: $college->name),
                'description' => 'الألبوم الافتراضي لصور ' . $college->name,
                'keywords'    => $college->name . ', ' . ($college->name_en ?: ''),
                'active'      => 1,
                'user_id'     => $college->user_id ?? (auth()->check() ? auth()->id() : 1),
            ]);
        });
    }

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $collegeTypes = [
        'college' => 'كلية',
        'deanship' => 'عمادة',
        'center' => 'مركز',
        'institute' => 'معهد',
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

<?php

namespace App\Models;

use App\Models\CategoryPage;
use App\Models\categoryPageUser;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['user_id','title'];

    public function categoryPage()
    {
        return $this->hasMany(CategoryPage::class,'category_id','id');
    }

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}

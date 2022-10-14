<?php

namespace App\Models;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryPage extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['user_id','category_id','title','url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categoryPageUsers()
    {
        return $this->belongsToMany(CategoryPageUser::class);
    }
}
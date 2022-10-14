<?php

namespace App\Models;

use App\Models\CategoryPage;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryPageUser extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;
    protected $fillable = ['category_page_id','user_id','auth_id'];

    protected $table = ['category_page_user'];
    public function categoryPages()
    {
        return $this->belongsTo(CategoryPage::class);
    }

}
<?php

namespace App\Models;

use App\Models\CategoryPage;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryPageUser extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;

    protected $table = 'category_page_user';
    protected $fillable = ['category_page_id', 'user_id', 'auth_id'];
    protected $primaryKey = ['category_page_id', 'user_id'];
    public $incrementing = false;

    public function categoryPages()
    {
        return $this->belongsTo(CategoryPage::class, 'category_page_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected $hidden = [
        // 'id',
        'category_page_id',
        'user_id',
        'auth_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getKeyName()
    {
        return ['category_page_id', 'user_id'];
    }
}

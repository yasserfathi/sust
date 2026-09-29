<?php

namespace App\Models;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffAcademic extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;

    protected $fillable = ['lang', 'user_id', 'item', 'item_val', 'type', 'url', 'img', 'thumb_img', 'file', 'detail', 'auth_id'];

    protected $auditInclude = [
        'lang', 'user_id', 'item', 'item_val', 'type', 'url', 'img', 'thumb_img', 'file', 'detail', 'auth_id',
    ];

    protected $hidden = [
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}

<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffAcademic extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable,HasFactory, SoftDeletes;

    protected $fillable = ['lang', 'user_id', 'item', 'item_val', 'url', 'img', 'file','auth_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
<?php

namespace App\Models;

use App\Models\College;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workshop extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;

    public $table = 'workshops';

    protected $fillable = ['college_id', 'title', 'lang', 'workshop_date', 'detail_portion', 'detail', 'keywords', 'photos', 'file', 'active', 'auth_id'];

    protected $auditInclude = [
        'college_id', 'title', 'lang', 'news_date', 'detail_portion', 'detail', 'keywords', 'photos', 'active', 'auth_id', 'file'
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}

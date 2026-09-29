<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeadAdministrativePosition extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, HasFactory, SoftDeletes;

    protected $fillable = ['administrative_positions_id', 'college_id', 'user_id', 'start_date', 'end_date', 'auth_id'];

    public function administrativePosition()
    {
        return $this->belongsTo(AdministrativePosition::class, 'administrative_positions_id');
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}

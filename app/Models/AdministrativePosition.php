<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdministrativePosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_en',
        'active',
    ];

    public function headAdministrativePositions()
    {
        return $this->hasMany(HeadAdministrativePosition::class, 'administrative_positions_id');
    }
}

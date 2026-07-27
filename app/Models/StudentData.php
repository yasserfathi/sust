<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentData extends Model
{
    protected $fillable = [
        'university_number',
        'full_name',
        'college',
        'department',
        'semester',
        'academic_year',
    ];
}

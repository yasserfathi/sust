<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCourse extends Model
{
    use HasFactory;

    protected $primaryKey = 'course_id';

    protected $fillable = [
        'program_id',
        'course_title',
        'course_code',
        'course_hours',
        'course_desc',
        'course_file',
        'year',
        'semester',
        'lang'
    ];

    public function program()
    {
        return $this->belongsTo(AcademicProgram::class, 'program_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = ['username', 'name', 'email', 'password', 'moodle_token', 'email_verified_at'];

    protected $hidden = [
        'password',
    ];
}
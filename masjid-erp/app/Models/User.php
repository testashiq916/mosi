<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'remember_token'
    ];
}

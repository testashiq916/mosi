<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'email',
        'email_verified_at',
        'password',
        'role',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }

    public function member()
    {
        return $this->hasOne(Member::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $table = 'donors';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'member_id',
        'name',
        'email',
        'mobile',
        'address',
        'is_anonymous'
    ];
}

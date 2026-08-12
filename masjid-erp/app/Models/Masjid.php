<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masjid extends Model
{
    protected $table = 'masjids';

    protected $fillable = [
        'company_id',
        'uuid',
        'name',
        'arabic_name',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'imam_name',
        'capacity',
        'is_active'
    ];
}

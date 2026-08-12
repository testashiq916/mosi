<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'timezone',
        'currency',
        'date_format',
        'logo_path',
        'subscription_id',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
        'user_limit',
        'storage_limit',
        'is_active'
    ];
}

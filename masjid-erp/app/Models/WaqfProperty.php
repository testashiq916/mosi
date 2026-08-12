<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaqfProperty extends Model
{
    protected $table = 'waqf_properties';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'property_code',
        'name',
        'property_type',
        'address',
        'current_value',
        'status'
    ];
}

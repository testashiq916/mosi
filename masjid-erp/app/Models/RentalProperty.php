<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalProperty extends Model
{
    protected $table = 'rental_properties';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'property_code',
        'name',
        'property_type',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'total_units',
        'total_area',
        'area_unit',
        'rental_rate',
        'rate_frequency',
        'security_deposit',
        'status',
        'description',
        'facilities',
        'created_by'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtensilRental extends Model
{
    protected $table = 'utensil_rentals';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'rental_id',
        'resident_id',
        'member_id',
        'rental_date',
        'return_date',
        'expected_return_date',
        'purpose',
        'event_type',
        'security_deposit',
        'total_rental_fee',
        'late_fee',
        'damage_charge',
        'status',
        'checked_out_by',
        'checked_out_at',
        'checked_in_by',
        'checked_in_at',
        'condition_notes',
        'created_by'
    ];
}

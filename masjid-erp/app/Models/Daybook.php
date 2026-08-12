<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Daybook extends Model
{
    protected $table = 'daybook';

    protected $fillable = [
        'company_id',
        'slno',
        'sno',
        'accode',
        'opaccode',
        'amount',
        'drcr',
        'voucher_type',
        'voucher_no',
        'voucher_date',
        'remarks',
        'reference_no',
        'reference_date',
        'member_id',
        'donation_id',
        'student_id',
        'property_id',
        'created_by'
    ];
}

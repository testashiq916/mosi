<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherDetail extends Model
{
    protected $table = 'voucher_details';

    public $timestamps = false;

    protected $fillable = [
        'voucher_id',
        'slno',
        'accode',
        'dr_amount',
        'cr_amount',
        'remarks',
        'member_id',
        'donation_id'
    ];
}

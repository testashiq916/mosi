<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $table = 'vouchers';

    protected $fillable = [
        'company_id',
        'voucher_no',
        'voucher_type',
        'voucher_date',
        'reference_no',
        'reference_date',
        'narration',
        'total_amount',
        'is_posted',
        'posted_by',
        'posted_at',
        'created_by'
    ];
}

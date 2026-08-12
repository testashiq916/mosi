<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'payment_no',
        'member_id',
        'supplier_id',
        'payment_date',
        'payment_type',
        'amount',
        'payment_method',
        'transaction_id',
        'reference_no',
        'description',
        'voucher_id',
        'approved_by',
        'approved_at',
        'created_by'
    ];
}

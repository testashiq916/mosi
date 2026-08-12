<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $table = 'receipts';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'receipt_no',
        'member_id',
        'donor_id',
        'receipt_date',
        'receipt_type',
        'amount',
        'payment_method',
        'transaction_id',
        'reference_no',
        'description',
        'voucher_id',
        'created_by'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function donation()
    {
        return $this->hasOne(Donation::class, 'receipt_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}

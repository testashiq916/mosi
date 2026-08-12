<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    protected $table = 'credit_notes';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'credit_note_no',
        'member_id',
        'donor_id',
        'receipt_id',
        'credit_note_date',
        'amount',
        'reason',
        'description',
        'status',
        'voucher_id',
        'created_by'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }
}

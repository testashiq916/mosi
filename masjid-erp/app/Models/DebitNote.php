<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DebitNote extends Model
{
    protected $table = 'debit_notes';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'debit_note_no',
        'member_id',
        'supplier_id',
        'payment_id',
        'debit_note_date',
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

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}

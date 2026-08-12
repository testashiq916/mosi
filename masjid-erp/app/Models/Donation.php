<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $table = 'donations';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'donor_id',
        'member_id',
        'category_id',
        'donation_id',
        'amount',
        'donation_date',
        'payment_method',
        'transaction_id',
        'is_anonymous',
        'is_recurring',
        'recurrence_pattern',
        'purpose',
        'notes',
        'status',
        'receipt_id',
        'voucher_id',
        'verified_by',
        'verified_at',
        'created_by'
    ];

    protected $casts = [
        'recurrence_pattern' => 'array',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    // NOTE: the source controller (MemberDonationController::store) writes the
    // member's own id into donor_id, but the schema's donor_id FK points at the
    // separate `donors` table. Kept as-is for fidelity to the original code;
    // see README "Known gaps inherited from the source material".
    public function donor()
    {
        return $this->belongsTo(Donor::class);
    }

    public function category()
    {
        return $this->belongsTo(DonationCategory::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberContribution extends Model
{
    protected $table = 'member_contributions';

    protected $fillable = [
        'member_id',
        'contribution_type',
        'amount',
        'contribution_date',
        'payment_method',
        'transaction_id',
        'receipt_id',
        'notes',
        'created_by'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

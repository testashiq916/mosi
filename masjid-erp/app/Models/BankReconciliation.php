<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankReconciliation extends Model
{
    protected $table = 'bank_reconciliations';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'bank_account',
        'reconciliation_date',
        'statement_balance',
        'system_balance',
        'difference_amount',
        'status',
        'remarks',
        'completed_by',
        'completed_at',
        'created_by'
    ];
}

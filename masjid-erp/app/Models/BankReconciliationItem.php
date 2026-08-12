<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankReconciliationItem extends Model
{
    protected $table = 'bank_reconciliation_items';

    public $timestamps = false;

    protected $fillable = [
        'reconciliation_id',
        'daybook_id',
        'status'
    ];
}

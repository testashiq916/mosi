<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialStatement extends Model
{
    protected $table = 'financial_statements';

    public $timestamps = false;

    protected $fillable = [
        'company_id',
        'masjid_id',
        'statement_type',
        'period_start',
        'period_end',
        'total_income',
        'total_expenses',
        'net_profit',
        'statement_data',
        'generated_by',
        'generated_at'
    ];
}

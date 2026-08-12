<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $table = 'journal_entries';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'journal_no',
        'journal_date',
        'description',
        'is_adjustment',
        'voucher_id',
        'created_by'
    ];
}

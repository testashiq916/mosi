<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountM extends Model
{
    protected $table = 'accountm';

    protected $fillable = [
        'company_id',
        'accode',
        'name',
        'grcode',
        'bshead',
        'actype',
        'opening_balance',
        'status',
        'is_default',
        'address',
        'contact_person',
        'phone',
        'email',
        'tax_number',
        'gst_type',
        'gstin',
        'bank_name',
        'bank_branch',
        'bank_account_number',
        'bank_ifsc',
        'created_by'
    ];
}

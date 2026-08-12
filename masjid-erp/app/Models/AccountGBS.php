<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountGBS extends Model
{
    protected $table = 'accountgbs';

    protected $fillable = [
        'company_id',
        'bshead',
        'bshead_name',
        'is_active'
    ];
}

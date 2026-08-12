<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountG extends Model
{
    protected $table = 'accountg';

    protected $fillable = [
        'company_id',
        'grcode',
        'grname',
        'bshead_id',
        'parent_grcode',
        'is_active'
    ];
}

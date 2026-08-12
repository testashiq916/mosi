<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaqfTenant extends Model
{
    protected $table = 'waqf_tenants';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'email',
        'mobile',
        'address',
        'id_proof_type',
        'id_proof_number'
    ];
}

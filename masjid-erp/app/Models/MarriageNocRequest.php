<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarriageNocRequest extends Model
{
    protected $table = 'marriage_noc_requests';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'noc_id',
        'applicant_name',
        'applicant_father_name',
        'applicant_mobile',
        'applicant_address',
        'marriage_registration_id',
        'purpose',
        'requested_date',
        'status',
        'approved_by',
        'approved_at',
        'issued_at',
        'remarks',
        'created_by'
    ];

    public function marriageRegistration()
    {
        return $this->belongsTo(MarriageRegistration::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarriageRegistration extends Model
{
    protected $table = 'marriage_registrations';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'registration_id',
        'groom_name',
        'groom_father_name',
        'groom_mobile',
        'groom_address',
        'bride_name',
        'bride_father_name',
        'bride_mobile',
        'bride_address',
        'marriage_date',
        'venue',
        'officiant_name',
        'dowry_amount',
        'witness_1_name',
        'witness_2_name',
        'nok_name',
        'nok_contact',
        'documents',
        'status',
        'approved_by',
        'approved_at',
        'certificate_issued',
        'certificate_issued_at',
        'created_by'
    ];

    protected $casts = [
        'documents' => 'array',
    ];

    public function nocRequests()
    {
        return $this->hasMany(MarriageNocRequest::class);
    }
}

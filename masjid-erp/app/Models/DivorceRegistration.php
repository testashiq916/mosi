<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DivorceRegistration extends Model
{
    protected $table = 'divorce_registrations';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'divorce_id',
        'marriage_registration_id',
        'husband_name',
        'wife_name',
        'divorce_date',
        'divorce_type',
        'document_path',
        'status',
        'approved_by',
        'approved_at',
        'certificate_issued',
        'certificate_issued_at',
        'remarks',
        'created_by'
    ];
}

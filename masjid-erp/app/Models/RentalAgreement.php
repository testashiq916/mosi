<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalAgreement extends Model
{
    protected $table = 'rental_agreements';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'agreement_id',
        'property_id',
        'tenant_id',
        'resident_id',
        'agreement_type',
        'start_date',
        'end_date',
        'renewal_date',
        'rental_amount',
        'rental_frequency',
        'security_deposit',
        'late_fee_percent',
        'payment_due_day',
        'terms_conditions',
        'agreement_document',
        'status',
        'created_by'
    ];
}

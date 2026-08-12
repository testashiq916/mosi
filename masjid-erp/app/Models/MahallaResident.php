<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahallaResident extends Model
{
    protected $table = 'mahalla_residents';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'resident_id',
        'member_id',
        'resident_type_id',
        'first_name',
        'last_name',
        'arabic_name',
        'gender',
        'date_of_birth',
        'nationality',
        'email',
        'mobile',
        'alternate_mobile',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'occupation',
        'employer',
        'id_proof_type',
        'id_proof_number',
        'id_proof_image',
        'is_active',
        'is_member',
        'subscription_status',
        'subscription_start_date',
        'subscription_end_date',
        'created_by'
    ];

    public function dependents()
    {
        return $this->hasMany(ResidentDependent::class, 'resident_id');
    }

    public function residentType()
    {
        return $this->belongsTo(ResidentType::class, 'resident_type_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

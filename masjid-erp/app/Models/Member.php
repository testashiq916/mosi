<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'member_id',
        'user_id',
        'member_type_id',
        'first_name',
        'last_name',
        'arabic_name',
        'gender',
        'date_of_birth',
        'place_of_birth',
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
        'family_members',
        'profile_image',
        'membership_start_date',
        'membership_end_date',
        'membership_status',
        'is_active',
        'created_by'
    ];

    public function memberType()
    {
        return $this->belongsTo(MemberType::class, 'member_type_id');
    }

    public function family()
    {
        return $this->hasMany(MemberFamily::class);
    }

    public function documents()
    {
        return $this->hasMany(MemberDocument::class);
    }

    public function contributions()
    {
        return $this->hasMany(MemberContribution::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function creditNotes()
    {
        return $this->hasMany(CreditNote::class);
    }

    public function masjid()
    {
        return $this->belongsTo(Masjid::class);
    }
}

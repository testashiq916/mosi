<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'staff_id',
        'user_id',
        'staff_role_id',
        'first_name',
        'last_name',
        'arabic_name',
        'gender',
        'date_of_birth',
        'email',
        'mobile',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'qualification',
        'experience_years',
        'profile_image',
        'id_proof_type',
        'id_proof_number',
        'id_proof_image',
        'joining_date',
        'contract_type',
        'basic_salary',
        'allowances',
        'status',
        'emergency_contact_name',
        'emergency_contact_phone',
        'bank_name',
        'bank_account_number',
        'bank_ifsc',
        'created_by'
    ];

    protected $casts = [
        'allowances' => 'array',
    ];

    public function role()
    {
        return $this->belongsTo(StaffRole::class, 'staff_role_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payroll()
    {
        return $this->hasMany(StaffPayroll::class);
    }

    public function attendance()
    {
        return $this->hasMany(StaffAttendance::class);
    }
}

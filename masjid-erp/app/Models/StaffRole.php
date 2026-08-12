<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffRole extends Model
{
    protected $table = 'staff_roles';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'arabic_name',
        'description',
        'salary_min',
        'salary_max',
        'is_active'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffPayroll extends Model
{
    protected $table = 'staff_payroll';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'staff_id',
        'payroll_id',
        'month',
        'year',
        'basic_salary',
        'allowances',
        'overtime_hours',
        'overtime_amount',
        'deductions',
        'total_earnings',
        'total_deductions',
        'net_salary',
        'status',
        'payment_date',
        'payment_method',
        'transaction_id',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'deductions' => 'array',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    protected $table = 'staff_attendance';

    protected $fillable = [
        'company_id',
        'staff_id',
        'attendance_date',
        'check_in_time',
        'check_out_time',
        'status',
        'working_hours',
        'overtime_hours',
        'remarks'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}

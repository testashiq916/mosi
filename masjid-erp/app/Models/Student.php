<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'student_id',
        'class_id',
        'first_name',
        'last_name',
        'guardian_name',
        'guardian_email',
        'guardian_mobile',
        'date_of_birth',
        'gender',
        'status'
    ];

    public function currentClass()
    {
        return $this->belongsTo(MadrassaClass::class, 'class_id');
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}

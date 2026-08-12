<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MadrassaClass extends Model
{
    protected $table = 'madrassa_classes';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'fee_amount'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
}

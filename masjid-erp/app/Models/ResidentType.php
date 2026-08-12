<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentType extends Model
{
    protected $table = 'resident_types';

    protected $fillable = [
        'company_id',
        'name',
        'arabic_name',
        'description',
        'is_active'
    ];

    public function residents()
    {
        return $this->hasMany(MahallaResident::class, 'resident_type_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentDependent extends Model
{
    protected $table = 'resident_dependents';

    protected $fillable = [
        'resident_id',
        'full_name',
        'relationship',
        'date_of_birth',
        'gender',
        'is_active'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtensilCategory extends Model
{
    protected $table = 'utensil_categories';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'arabic_name',
        'description',
        'is_active'
    ];
}

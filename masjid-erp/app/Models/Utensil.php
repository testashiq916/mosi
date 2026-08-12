<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Utensil extends Model
{
    protected $table = 'utensils';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'category_id',
        'item_code',
        'name',
        'arabic_name',
        'description',
        'quantity',
        'available_quantity',
        'damaged_quantity',
        'unit',
        'rental_fee',
        'security_deposit',
        'min_rental_period',
        'max_rental_period',
        'location',
        'image_path',
        'status',
        'created_by'
    ];
}

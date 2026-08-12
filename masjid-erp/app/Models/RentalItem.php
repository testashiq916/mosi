<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    protected $table = 'rental_items';

    public $timestamps = false;

    protected $fillable = [
        'rental_id',
        'utensil_id',
        'quantity',
        'returned_quantity',
        'damaged_quantity',
        'notes'
    ];
}

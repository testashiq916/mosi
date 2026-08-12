<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationCategory extends Model
{
    protected $table = 'donation_categories';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'arabic_name',
        'description',
        'accode',
        'is_active'
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class, 'category_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $table = 'committees';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'description',
        'is_active'
    ];

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}

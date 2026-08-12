<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberType extends Model
{
    protected $table = 'member_types';

    protected $fillable = [
        'company_id',
        'name',
        'arabic_name',
        'description',
        'monthly_fee',
        'annual_fee',
        'benefits',
        'is_active'
    ];

    protected $casts = [
        'benefits' => 'array',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}

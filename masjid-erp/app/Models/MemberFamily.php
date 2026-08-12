<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberFamily extends Model
{
    protected $table = 'member_family';

    protected $fillable = [
        'member_id',
        'full_name',
        'relationship',
        'date_of_birth',
        'gender',
        'is_active'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

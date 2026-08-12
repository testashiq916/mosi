<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberDocument extends Model
{
    protected $table = 'member_documents';

    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'document_type',
        'document_name',
        'document_path',
        'document_number',
        'issue_date',
        'expiry_date',
        'is_verified',
        'verified_by',
        'verified_at'
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

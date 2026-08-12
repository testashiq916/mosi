<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    protected $table = 'meetings';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'committee_id',
        'meeting_id',
        'title',
        'meeting_type',
        'meeting_date',
        'end_time',
        'venue',
        'agenda',
        'minutes_text',
        'decisions',
        'actions',
        'attendance',
        'documents',
        'status',
        'created_by'
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
        'end_time' => 'datetime',
        'attendance' => 'array',
        'documents' => 'array',
    ];

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function actionItems()
    {
        return $this->hasMany(MeetingAction::class);
    }
}

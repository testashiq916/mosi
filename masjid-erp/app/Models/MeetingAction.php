<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingAction extends Model
{
    protected $table = 'meeting_actions';

    protected $fillable = [
        'meeting_id',
        'action_description',
        'assigned_to',
        'due_date',
        'priority',
        'status',
        'completed_at',
        'remarks'
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

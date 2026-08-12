<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationQueue extends Model
{
    protected $table = 'notification_queues';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'notification_type_id',
        'recipient_type',
        'recipient_id',
        'channel',
        'subject',
        'message',
        'data',
        'status',
        'sent_at',
        'delivered_at',
        'failure_reason',
        'retry_count',
        'created_by'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function type()
    {
        return $this->belongsTo(NotificationType::class, 'notification_type_id');
    }
}

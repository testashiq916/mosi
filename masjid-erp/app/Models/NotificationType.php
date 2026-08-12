<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationType extends Model
{
    protected $table = 'notification_types';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'slug',
        'description',
        'default_template',
        'channels',
        'is_active'
    ];

    protected $casts = [
        'channels' => 'array',
    ];

    public function queueEntries()
    {
        return $this->hasMany(NotificationQueue::class, 'notification_type_id');
    }
}

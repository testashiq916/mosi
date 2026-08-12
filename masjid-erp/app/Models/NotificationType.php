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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'title',
        'description',
        'event_date',
        'venue'
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}

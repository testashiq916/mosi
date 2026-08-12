<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $table = 'event_registrations';

    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'name',
        'email',
        'mobile'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

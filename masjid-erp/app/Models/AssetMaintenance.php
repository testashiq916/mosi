<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    protected $table = 'asset_maintenance';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'asset_id',
        'maintenance_id',
        'maintenance_type',
        'description',
        'scheduled_date',
        'completion_date',
        'cost',
        'vendor_name',
        'vendor_contact',
        'status',
        'remarks',
        'documents',
        'created_by'
    ];
}

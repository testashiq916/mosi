<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
    protected $table = 'fixed_assets';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'asset_id',
        'category_id',
        'name',
        'arabic_name',
        'description',
        'serial_number',
        'model',
        'manufacturer',
        'purchase_date',
        'purchase_price',
        'current_value',
        'depreciation_method',
        'depreciation_rate',
        'salvage_value',
        'useful_life_years',
        'location',
        'assigned_to',
        'warranty_expiry',
        'status',
        'documents',
        'created_by'
    ];
}

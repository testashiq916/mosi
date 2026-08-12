<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    protected $table = 'asset_categories';

    protected $fillable = [
        'company_id',
        'masjid_id',
        'name',
        'arabic_name',
        'description',
        'depreciation_rate',
        'is_active'
    ];

    public function assets()
    {
        return $this->hasMany(FixedAsset::class, 'category_id');
    }
}

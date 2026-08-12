<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * fixed_assets
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE fixed_assets (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    asset_id VARCHAR(50) UNIQUE NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    arabic_name VARCHAR(255),
    description TEXT,
    serial_number VARCHAR(100),
    model VARCHAR(100),
    manufacturer VARCHAR(255),
    purchase_date DATE,
    purchase_price DECIMAL(15,2),
    current_value DECIMAL(15,2),
    depreciation_method ENUM('straight_line', 'declining_balance', 'units_of_production') DEFAULT 'straight_line',
    depreciation_rate DECIMAL(5,2) DEFAULT 0,
    salvage_value DECIMAL(15,2) DEFAULT 0,
    useful_life_years INT,
    location VARCHAR(255),
    assigned_to VARCHAR(255),
    warranty_expiry DATE,
    status ENUM('active', 'in_maintenance', 'disposed', 'lost', 'inactive') DEFAULT 'active',
    documents JSON,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (category_id) REFERENCES asset_categories(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * asset_maintenance
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE asset_maintenance (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    asset_id BIGINT UNSIGNED NOT NULL,
    maintenance_id VARCHAR(50) UNIQUE NOT NULL,
    maintenance_type ENUM('routine', 'repair', 'emergency', 'preventive') NOT NULL,
    description TEXT NOT NULL,
    scheduled_date DATE,
    completion_date DATE,
    cost DECIMAL(15,2),
    vendor_name VARCHAR(255),
    vendor_contact VARCHAR(20),
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'scheduled',
    remarks TEXT,
    documents JSON,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (asset_id) REFERENCES fixed_assets(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_maintenance');
    }
};

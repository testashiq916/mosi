<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * rental_properties
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE rental_properties (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    property_code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    property_type ENUM('apartment', 'shop', 'office', 'hall', 'residential', 'commercial') NOT NULL,
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    zip_code VARCHAR(20),
    total_units INT DEFAULT 1,
    total_area DECIMAL(15,2),
    area_unit VARCHAR(20) DEFAULT 'sq_meter',
    rental_rate DECIMAL(15,2),
    rate_frequency ENUM('monthly', 'quarterly', 'yearly') DEFAULT 'monthly',
    security_deposit DECIMAL(15,2),
    status ENUM('available', 'rented', 'maintenance', 'blocked') DEFAULT 'available',
    description TEXT,
    facilities JSON,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_properties');
    }
};

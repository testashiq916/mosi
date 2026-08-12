<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * utensil_rentals
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE utensil_rentals (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    rental_id VARCHAR(50) UNIQUE NOT NULL,
    resident_id BIGINT UNSIGNED,
    member_id BIGINT UNSIGNED,
    rental_date DATE NOT NULL,
    return_date DATE,
    expected_return_date DATE NOT NULL,
    purpose VARCHAR(255),
    event_type VARCHAR(100),
    security_deposit DECIMAL(10,2),
    total_rental_fee DECIMAL(10,2),
    late_fee DECIMAL(10,2) DEFAULT 0,
    damage_charge DECIMAL(10,2) DEFAULT 0,
    status ENUM('pending', 'active', 'returned', 'overdue', 'damaged', 'cancelled') DEFAULT 'pending',
    checked_out_by BIGINT UNSIGNED,
    checked_out_at TIMESTAMP NULL,
    checked_in_by BIGINT UNSIGNED,
    checked_in_at TIMESTAMP NULL,
    condition_notes TEXT,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (resident_id) REFERENCES mahalla_residents(id) ON DELETE SET NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (checked_out_by) REFERENCES users(id),
    FOREIGN KEY (checked_in_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('utensil_rentals');
    }
};

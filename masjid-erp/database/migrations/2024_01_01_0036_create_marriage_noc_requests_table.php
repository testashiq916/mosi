<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * marriage_noc_requests
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE marriage_noc_requests (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    noc_id VARCHAR(50) UNIQUE NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    applicant_father_name VARCHAR(255),
    applicant_mobile VARCHAR(20),
    applicant_address TEXT,
    marriage_registration_id BIGINT UNSIGNED,
    purpose VARCHAR(255),
    requested_date DATE NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'issued') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED,
    approved_at TIMESTAMP NULL,
    issued_at TIMESTAMP NULL,
    remarks TEXT,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (marriage_registration_id) REFERENCES marriage_registrations(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_noc_requests');
    }
};

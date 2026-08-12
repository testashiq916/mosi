<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * marriage_registrations
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE marriage_registrations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    registration_id VARCHAR(50) UNIQUE NOT NULL,
    groom_name VARCHAR(255) NOT NULL,
    groom_father_name VARCHAR(255),
    groom_mobile VARCHAR(20),
    groom_address TEXT,
    bride_name VARCHAR(255) NOT NULL,
    bride_father_name VARCHAR(255),
    bride_mobile VARCHAR(20),
    bride_address TEXT,
    marriage_date DATE NOT NULL,
    venue VARCHAR(255),
    officiant_name VARCHAR(255),
    dowry_amount DECIMAL(15,2),
    witness_1_name VARCHAR(255),
    witness_2_name VARCHAR(255),
    nok_name VARCHAR(255),
    nok_contact VARCHAR(20),
    documents JSON,
    status ENUM('pending', 'approved', 'registered', 'cancelled') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED,
    approved_at TIMESTAMP NULL,
    certificate_issued BOOLEAN DEFAULT FALSE,
    certificate_issued_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('marriage_registrations');
    }
};

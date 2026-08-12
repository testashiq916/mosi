<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * staff
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE staff (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    staff_id VARCHAR(50) UNIQUE NOT NULL,
    user_id BIGINT UNSIGNED,
    staff_role_id BIGINT UNSIGNED NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    arabic_name VARCHAR(255),
    gender ENUM('male', 'female') NOT NULL,
    date_of_birth DATE,
    email VARCHAR(255),
    mobile VARCHAR(20) NOT NULL,
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    zip_code VARCHAR(20),
    qualification VARCHAR(255),
    experience_years INT DEFAULT 0,
    profile_image VARCHAR(255),
    id_proof_type VARCHAR(50),
    id_proof_number VARCHAR(100),
    id_proof_image VARCHAR(255),
    joining_date DATE,
    contract_type ENUM('permanent', 'contract', 'temporary', 'volunteer') DEFAULT 'permanent',
    basic_salary DECIMAL(10,2),
    allowances JSON,
    status ENUM('active', 'inactive', 'on_leave', 'resigned', 'terminated') DEFAULT 'active',
    emergency_contact_name VARCHAR(255),
    emergency_contact_phone VARCHAR(20),
    bank_name VARCHAR(255),
    bank_account_number VARCHAR(50),
    bank_ifsc VARCHAR(20),
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (staff_role_id) REFERENCES staff_roles(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};

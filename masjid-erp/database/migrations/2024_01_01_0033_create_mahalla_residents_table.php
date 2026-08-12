<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * mahalla_residents
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE mahalla_residents (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    resident_id VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED,
    resident_type_id BIGINT UNSIGNED,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    arabic_name VARCHAR(255),
    gender ENUM('male', 'female') NOT NULL,
    date_of_birth DATE,
    nationality VARCHAR(100),
    email VARCHAR(255),
    mobile VARCHAR(20) NOT NULL,
    alternate_mobile VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    zip_code VARCHAR(20),
    occupation VARCHAR(255),
    employer VARCHAR(255),
    id_proof_type VARCHAR(50),
    id_proof_number VARCHAR(100),
    id_proof_image VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    is_member BOOLEAN DEFAULT FALSE,
    subscription_status ENUM('active', 'inactive', 'expired') DEFAULT 'active',
    subscription_start_date DATE,
    subscription_end_date DATE,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (resident_type_id) REFERENCES resident_types(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('mahalla_residents');
    }
};

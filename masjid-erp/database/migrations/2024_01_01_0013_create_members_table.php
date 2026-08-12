<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * members
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE members (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    member_id VARCHAR(50) UNIQUE NOT NULL,
    user_id BIGINT UNSIGNED,
    member_type_id BIGINT UNSIGNED,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    arabic_name VARCHAR(255),
    gender ENUM('male', 'female') NOT NULL,
    date_of_birth DATE,
    place_of_birth VARCHAR(100),
    nationality VARCHAR(100),
    email VARCHAR(255) NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    alternate_mobile VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    zip_code VARCHAR(20),
    occupation VARCHAR(255),
    employer VARCHAR(255),
    family_members INT DEFAULT 1,
    profile_image VARCHAR(255),
    membership_start_date DATE,
    membership_end_date DATE,
    membership_status ENUM('active', 'inactive', 'suspended', 'expired') DEFAULT 'active',
    is_active BOOLEAN DEFAULT TRUE,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (member_type_id) REFERENCES member_types(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};

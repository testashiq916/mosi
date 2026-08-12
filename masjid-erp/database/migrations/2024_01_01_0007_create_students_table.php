<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * students (table not present in the source dump; added as a minimal stub because other provided tables reference it via foreign key)
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE students (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    class_id BIGINT UNSIGNED,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    guardian_name VARCHAR(255),
    guardian_email VARCHAR(255),
    guardian_mobile VARCHAR(20),
    date_of_birth DATE,
    gender ENUM('male', 'female'),
    status ENUM('active', 'inactive', 'graduated', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (class_id) REFERENCES madrassa_classes(id) ON DELETE SET NULL
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

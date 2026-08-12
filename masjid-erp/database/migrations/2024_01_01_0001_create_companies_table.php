<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * companies
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE companies (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    uuid CHAR(36) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    zip_code VARCHAR(20),
    timezone VARCHAR(50) DEFAULT 'Asia/Riyadh',
    currency VARCHAR(10) DEFAULT 'SAR',
    date_format VARCHAR(20) DEFAULT 'Y-m-d',
    logo_path VARCHAR(255),
    subscription_id BIGINT UNSIGNED,
    subscription_status ENUM('trial', 'active', 'suspended', 'cancelled', 'expired') DEFAULT 'trial',
    subscription_start_date DATE,
    subscription_end_date DATE,
    user_limit INT DEFAULT 50,
    storage_limit BIGINT DEFAULT 5368709120,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * accountm
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE accountm (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    accode VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    grcode VARCHAR(20) NOT NULL,
    bshead VARCHAR(50) NOT NULL,
    actype ENUM('debit', 'credit') NOT NULL,
    opening_balance DECIMAL(15,2) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    is_default BOOLEAN DEFAULT FALSE,
    address TEXT,
    contact_person VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(255),
    tax_number VARCHAR(50),
    gst_type ENUM('registered', 'unregistered', 'composition') DEFAULT 'unregistered',
    gstin VARCHAR(50),
    bank_name VARCHAR(255),
    bank_branch VARCHAR(255),
    bank_account_number VARCHAR(50),
    bank_ifsc VARCHAR(20),
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (grcode) REFERENCES accountg(grcode),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('accountm');
    }
};

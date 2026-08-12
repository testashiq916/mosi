<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * daybook
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE daybook (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    slno BIGINT UNSIGNED AUTO_INCREMENT UNIQUE,
    sno BIGINT UNSIGNED NOT NULL,
    accode VARCHAR(20) NOT NULL,
    opaccode VARCHAR(20) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    drcr ENUM('dr', 'cr') NOT NULL,
    voucher_type ENUM('receipt', 'payment', 'journal', 'contra', 'credit_note', 'debit_note') NOT NULL,
    voucher_no VARCHAR(50) NOT NULL,
    voucher_date DATE NOT NULL,
    remarks TEXT,
    reference_no VARCHAR(50),
    reference_date DATE,
    member_id BIGINT UNSIGNED,
    donation_id BIGINT UNSIGNED,
    student_id BIGINT UNSIGNED,
    property_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (accode) REFERENCES accountm(accode),
    FOREIGN KEY (opaccode) REFERENCES accountm(accode),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE SET NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (property_id) REFERENCES waqf_properties(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_voucher (voucher_type, voucher_no),
    INDEX idx_account (accode),
    INDEX idx_opposite (opaccode),
    INDEX idx_member (member_id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('daybook');
    }
};

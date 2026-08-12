<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * vouchers
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE vouchers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    voucher_no VARCHAR(50) NOT NULL,
    voucher_type ENUM('receipt', 'payment', 'journal', 'contra', 'credit_note', 'debit_note') NOT NULL,
    voucher_date DATE NOT NULL,
    reference_no VARCHAR(50),
    reference_date DATE,
    narration TEXT,
    total_amount DECIMAL(15,2),
    is_posted BOOLEAN DEFAULT FALSE,
    posted_by BIGINT UNSIGNED,
    posted_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (posted_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE KEY unique_voucher (company_id, voucher_type, voucher_no)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

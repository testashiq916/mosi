<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * debit_notes
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE debit_notes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    debit_note_no VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED,
    supplier_id BIGINT UNSIGNED,
    payment_id BIGINT UNSIGNED,
    debit_note_date DATE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    reason VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('issued', 'used', 'cancelled') DEFAULT 'issued',
    voucher_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('debit_notes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * voucher_details
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE voucher_details (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    voucher_id BIGINT UNSIGNED NOT NULL,
    slno INT NOT NULL,
    accode VARCHAR(20) NOT NULL,
    dr_amount DECIMAL(15,2) DEFAULT 0,
    cr_amount DECIMAL(15,2) DEFAULT 0,
    remarks TEXT,
    member_id BIGINT UNSIGNED,
    donation_id BIGINT UNSIGNED,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE CASCADE,
    FOREIGN KEY (accode) REFERENCES accountm(accode),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE SET NULL
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_details');
    }
};

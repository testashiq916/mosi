<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * bank_reconciliation_items
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE bank_reconciliation_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    reconciliation_id BIGINT UNSIGNED NOT NULL,
    daybook_id BIGINT UNSIGNED NOT NULL,
    status ENUM('matched', 'unmatched', 'cleared') DEFAULT 'unmatched',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reconciliation_id) REFERENCES bank_reconciliations(id) ON DELETE CASCADE,
    FOREIGN KEY (daybook_id) REFERENCES daybook(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_reconciliation_items');
    }
};

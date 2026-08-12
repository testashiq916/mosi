<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * accountg
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE accountg (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    grcode VARCHAR(20) UNIQUE NOT NULL,
    grname VARCHAR(100) NOT NULL,
    bshead_id BIGINT UNSIGNED NOT NULL,
    parent_grcode VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (bshead_id) REFERENCES accountgbs(id),
    FOREIGN KEY (parent_grcode) REFERENCES accountg(grcode)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('accountg');
    }
};

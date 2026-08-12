<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * meetings
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE meetings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    committee_id BIGINT UNSIGNED,
    meeting_id VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    meeting_type ENUM('committee', 'management', 'annual', 'emergency', 'regular', 'other') NOT NULL,
    meeting_date DATETIME NOT NULL,
    end_time DATETIME,
    venue VARCHAR(255),
    agenda TEXT,
    minutes_text TEXT,
    decisions TEXT,
    actions TEXT,
    attendance JSON,
    documents JSON,
    status ENUM('scheduled', 'held', 'cancelled', 'rescheduled') DEFAULT 'scheduled',
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (committee_id) REFERENCES committees(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};

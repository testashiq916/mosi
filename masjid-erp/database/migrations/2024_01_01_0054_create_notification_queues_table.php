<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * notification_queues
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE notification_queues (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    notification_type_id BIGINT UNSIGNED NOT NULL,
    recipient_type ENUM('member', 'staff', 'resident', 'donor', 'student', 'parent', 'all') NOT NULL,
    recipient_id BIGINT UNSIGNED,
    channel ENUM('email', 'sms', 'whatsapp', 'push', 'in_app') NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    data JSON,
    status ENUM('pending', 'sent', 'delivered', 'failed', 'cancelled') DEFAULT 'pending',
    sent_at TIMESTAMP NULL,
    delivered_at TIMESTAMP NULL,
    failure_reason TEXT,
    retry_count INT DEFAULT 0,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (notification_type_id) REFERENCES notification_types(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_recipient (recipient_type, recipient_id),
    INDEX idx_status (status)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_queues');
    }
};

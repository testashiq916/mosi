<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * resident_dependents
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE resident_dependents (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    resident_id BIGINT UNSIGNED NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    relationship VARCHAR(50) NOT NULL,
    date_of_birth DATE,
    gender ENUM('male', 'female'),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (resident_id) REFERENCES mahalla_residents(id) ON DELETE CASCADE
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('resident_dependents');
    }
};

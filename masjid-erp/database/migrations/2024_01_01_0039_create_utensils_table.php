<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * utensils
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE utensils (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    item_code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    arabic_name VARCHAR(255),
    description TEXT,
    quantity INT DEFAULT 0,
    available_quantity INT DEFAULT 0,
    damaged_quantity INT DEFAULT 0,
    unit VARCHAR(20),
    rental_fee DECIMAL(10,2),
    security_deposit DECIMAL(10,2),
    min_rental_period INT DEFAULT 1,
    max_rental_period INT DEFAULT 7,
    location VARCHAR(255),
    image_path VARCHAR(255),
    status ENUM('available', 'rented', 'maintenance', 'damaged') DEFAULT 'available',
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (category_id) REFERENCES utensil_categories(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('utensils');
    }
};

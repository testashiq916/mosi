<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * rental_items
 * Executed as raw SQL (DB::unprepared) to preserve the exact schema from the source dump.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared(<<<'SQL'
CREATE TABLE rental_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    rental_id BIGINT UNSIGNED NOT NULL,
    utensil_id BIGINT UNSIGNED NOT NULL,
    quantity INT NOT NULL,
    returned_quantity INT DEFAULT 0,
    damaged_quantity INT DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rental_id) REFERENCES utensil_rentals(id) ON DELETE CASCADE,
    FOREIGN KEY (utensil_id) REFERENCES utensils(id)
);
SQL
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('rental_items');
    }
};

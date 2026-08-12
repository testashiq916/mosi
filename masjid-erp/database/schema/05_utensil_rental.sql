-- ===========================================
-- UTENSIL & FACILITY RENTAL
-- ===========================================

-- Utensil Categories
CREATE TABLE utensil_categories (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    arabic_name VARCHAR(255),
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id)
);

-- Utensils
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

-- Utensil Rentals
CREATE TABLE utensil_rentals (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    rental_id VARCHAR(50) UNIQUE NOT NULL,
    resident_id BIGINT UNSIGNED,
    member_id BIGINT UNSIGNED,
    rental_date DATE NOT NULL,
    return_date DATE,
    expected_return_date DATE NOT NULL,
    purpose VARCHAR(255),
    event_type VARCHAR(100),
    security_deposit DECIMAL(10,2),
    total_rental_fee DECIMAL(10,2),
    late_fee DECIMAL(10,2) DEFAULT 0,
    damage_charge DECIMAL(10,2) DEFAULT 0,
    status ENUM('pending', 'active', 'returned', 'overdue', 'damaged', 'cancelled') DEFAULT 'pending',
    checked_out_by BIGINT UNSIGNED,
    checked_out_at TIMESTAMP NULL,
    checked_in_by BIGINT UNSIGNED,
    checked_in_at TIMESTAMP NULL,
    condition_notes TEXT,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (resident_id) REFERENCES mahalla_residents(id) ON DELETE SET NULL,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (checked_out_by) REFERENCES users(id),
    FOREIGN KEY (checked_in_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Rental Items
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
-- ===========================================
-- BUILDING RENTAL MANAGEMENT
-- ===========================================

-- Rental Properties
CREATE TABLE rental_properties (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    property_code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    property_type ENUM('apartment', 'shop', 'office', 'hall', 'residential', 'commercial') NOT NULL,
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    zip_code VARCHAR(20),
    total_units INT DEFAULT 1,
    total_area DECIMAL(15,2),
    area_unit VARCHAR(20) DEFAULT 'sq_meter',
    rental_rate DECIMAL(15,2),
    rate_frequency ENUM('monthly', 'quarterly', 'yearly') DEFAULT 'monthly',
    security_deposit DECIMAL(15,2),
    status ENUM('available', 'rented', 'maintenance', 'blocked') DEFAULT 'available',
    description TEXT,
    facilities JSON,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Rental Agreements
CREATE TABLE rental_agreements (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    agreement_id VARCHAR(50) UNIQUE NOT NULL,
    property_id BIGINT UNSIGNED NOT NULL,
    tenant_id BIGINT UNSIGNED,
    resident_id BIGINT UNSIGNED,
    agreement_type ENUM('residential', 'commercial', 'mixed') DEFAULT 'residential',
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    renewal_date DATE,
    rental_amount DECIMAL(15,2) NOT NULL,
    rental_frequency ENUM('monthly', 'quarterly', 'yearly') DEFAULT 'monthly',
    security_deposit DECIMAL(15,2),
    late_fee_percent DECIMAL(5,2) DEFAULT 0,
    payment_due_day INT DEFAULT 1,
    terms_conditions TEXT,
    agreement_document VARCHAR(255),
    status ENUM('active', 'expired', 'terminated', 'renewed', 'pending') DEFAULT 'pending',
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (property_id) REFERENCES rental_properties(id),
    FOREIGN KEY (tenant_id) REFERENCES waqf_tenants(id) ON DELETE SET NULL,
    FOREIGN KEY (resident_id) REFERENCES mahalla_residents(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);
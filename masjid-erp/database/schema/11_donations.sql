-- ===========================================
-- DONATION & FUNDRAISING ENHANCED
-- ===========================================

-- Donations (Enhanced)
CREATE TABLE donations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    donor_id BIGINT UNSIGNED NOT NULL,
    member_id BIGINT UNSIGNED,
    category_id BIGINT UNSIGNED,
    donation_id VARCHAR(50) UNIQUE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    donation_date DATE NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'online', 'card', 'cheque', 'waqf') DEFAULT 'cash',
    transaction_id VARCHAR(255),
    is_anonymous BOOLEAN DEFAULT FALSE,
    is_recurring BOOLEAN DEFAULT FALSE,
    recurrence_pattern JSON,
    purpose VARCHAR(255),
    notes TEXT,
    status ENUM('pending', 'received', 'verified', 'refunded') DEFAULT 'pending',
    receipt_id BIGINT UNSIGNED,
    voucher_id BIGINT UNSIGNED,
    verified_by BIGINT UNSIGNED,
    verified_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (donor_id) REFERENCES donors(id),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES donation_categories(id),
    FOREIGN KEY (receipt_id) REFERENCES receipts(id) ON DELETE SET NULL,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE SET NULL,
    FOREIGN KEY (verified_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);
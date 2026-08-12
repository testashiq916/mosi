-- ===========================================
-- ACCOUNTING MODULE (Double-Entry System)
-- ===========================================

-- Account Group BS (Balance Sheet Head)
CREATE TABLE accountgbs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    bshead VARCHAR(50) UNIQUE NOT NULL,
    bshead_name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id)
);

-- Account Group
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

-- Account Master (Chart of Accounts)
CREATE TABLE accountm (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    accode VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    grcode VARCHAR(20) NOT NULL,
    bshead VARCHAR(50) NOT NULL,
    actype ENUM('debit', 'credit') NOT NULL,
    opening_balance DECIMAL(15,2) DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    is_default BOOLEAN DEFAULT FALSE,
    address TEXT,
    contact_person VARCHAR(255),
    phone VARCHAR(20),
    email VARCHAR(255),
    tax_number VARCHAR(50),
    gst_type ENUM('registered', 'unregistered', 'composition') DEFAULT 'unregistered',
    gstin VARCHAR(50),
    bank_name VARCHAR(255),
    bank_branch VARCHAR(255),
    bank_account_number VARCHAR(50),
    bank_ifsc VARCHAR(20),
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (grcode) REFERENCES accountg(grcode),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Default Chart of Accounts for Masjid ERP
INSERT INTO accountgbs (company_id, bshead, bshead_name) VALUES
(1, 'ASSETS', 'Assets'),
(1, 'LIABILITIES', 'Liabilities'),
(1, 'INCOME', 'Income'),
(1, 'EXPENSES', 'Expenses');

INSERT INTO accountg (company_id, grcode, grname, bshead_id, parent_grcode) VALUES
(1, 'CA', 'Current Assets', 1, NULL),
(1, 'FA', 'Fixed Assets', 1, NULL),
(1, 'CL', 'Current Liabilities', 2, NULL),
(1, 'LL', 'Long Term Liabilities', 2, NULL),
(1, 'OP', 'Operating Income', 3, NULL),
(1, 'OI', 'Other Income', 3, NULL),
(1, 'OE', 'Operating Expenses', 4, NULL),
(1, 'NE', 'Non-Operating Expenses', 4, NULL);

-- Sample Accounts
INSERT INTO accountm (company_id, accode, name, grcode, bshead, actype, opening_balance) VALUES
-- Asset Accounts
(1, '101', 'Cash in Hand', 'CA', 'ASSETS', 'debit', 0),
(1, '102', 'Bank Account - Main', 'CA', 'ASSETS', 'debit', 0),
(1, '103', 'Bank Account - Savings', 'CA', 'ASSETS', 'debit', 0),
(1, '104', 'Bank Account - Waqf', 'CA', 'ASSETS', 'debit', 0),
(1, '105', 'Accounts Receivable', 'CA', 'ASSETS', 'debit', 0),
(1, '106', 'Security Deposit Receivable', 'CA', 'ASSETS', 'debit', 0),
(1, '107', 'Masjid Property', 'FA', 'ASSETS', 'debit', 0),
(1, '108', 'Waqf Properties', 'FA', 'ASSETS', 'debit', 0),

-- Liability Accounts
(1, '201', 'Accounts Payable', 'CL', 'LIABILITIES', 'credit', 0),
(1, '202', 'Security Deposit Payable', 'CL', 'LIABILITIES', 'credit', 0),
(1, '203', 'GST Payable - CGST', 'CL', 'LIABILITIES', 'credit', 0),
(1, '204', 'GST Payable - SGST', 'CL', 'LIABILITIES', 'credit', 0),
(1, '205', 'Advance Payments Received', 'CL', 'LIABILITIES', 'credit', 0),
(1, '206', 'Staff Salary Payable', 'CL', 'LIABILITIES', 'credit', 0),

-- Income Accounts
(1, '301', 'Donation Revenue', 'OP', 'INCOME', 'credit', 0),
(1, '302', 'Zakat Revenue', 'OP', 'INCOME', 'credit', 0),
(1, '303', 'Sadaqah Revenue', 'OP', 'INCOME', 'credit', 0),
(1, '304', 'Membership Fees', 'OP', 'INCOME', 'credit', 0),
(1, '305', 'Madrassa Fees', 'OP', 'INCOME', 'credit', 0),
(1, '306', 'Waqf Rental Income', 'OP', 'INCOME', 'credit', 0),
(1, '307', 'Event Income', 'OI', 'INCOME', 'credit', 0),
(1, '308', 'Investment Income', 'OI', 'INCOME', 'credit', 0),

-- Expense Accounts
(1, '401', 'Staff Salaries', 'OE', 'EXPENSES', 'debit', 0),
(1, '402', 'Utilities - Electricity', 'OE', 'EXPENSES', 'debit', 0),
(1, '403', 'Utilities - Water', 'OE', 'EXPENSES', 'debit', 0),
(1, '404', 'Maintenance - Masjid', 'OE', 'EXPENSES', 'debit', 0),
(1, '405', 'Maintenance - Waqf', 'OE', 'EXPENSES', 'debit', 0),
(1, '406', 'Education Materials', 'OE', 'EXPENSES', 'debit', 0),
(1, '407', 'Insurance', 'OE', 'EXPENSES', 'debit', 0),
(1, '408', 'Bank Charges', 'NE', 'EXPENSES', 'debit', 0);

-- Daybook (Ledger Entries - Main Transaction Table)
CREATE TABLE daybook (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    slno BIGINT UNSIGNED AUTO_INCREMENT UNIQUE,
    sno BIGINT UNSIGNED NOT NULL,
    accode VARCHAR(20) NOT NULL,
    opaccode VARCHAR(20) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    drcr ENUM('dr', 'cr') NOT NULL,
    voucher_type ENUM('receipt', 'payment', 'journal', 'contra', 'credit_note', 'debit_note') NOT NULL,
    voucher_no VARCHAR(50) NOT NULL,
    voucher_date DATE NOT NULL,
    remarks TEXT,
    reference_no VARCHAR(50),
    reference_date DATE,
    member_id BIGINT UNSIGNED,
    donation_id BIGINT UNSIGNED,
    student_id BIGINT UNSIGNED,
    property_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (accode) REFERENCES accountm(accode),
    FOREIGN KEY (opaccode) REFERENCES accountm(accode),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE SET NULL,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE SET NULL,
    FOREIGN KEY (property_id) REFERENCES waqf_properties(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_voucher (voucher_type, voucher_no),
    INDEX idx_account (accode),
    INDEX idx_opposite (opaccode),
    INDEX idx_member (member_id)
);

-- Voucher Header
CREATE TABLE vouchers (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    voucher_no VARCHAR(50) NOT NULL,
    voucher_type ENUM('receipt', 'payment', 'journal', 'contra', 'credit_note', 'debit_note') NOT NULL,
    voucher_date DATE NOT NULL,
    reference_no VARCHAR(50),
    reference_date DATE,
    narration TEXT,
    total_amount DECIMAL(15,2),
    is_posted BOOLEAN DEFAULT FALSE,
    posted_by BIGINT UNSIGNED,
    posted_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (posted_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE KEY unique_voucher (company_id, voucher_type, voucher_no)
);

-- Voucher Details
CREATE TABLE voucher_details (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    voucher_id BIGINT UNSIGNED NOT NULL,
    slno INT NOT NULL,
    accode VARCHAR(20) NOT NULL,
    dr_amount DECIMAL(15,2) DEFAULT 0,
    cr_amount DECIMAL(15,2) DEFAULT 0,
    remarks TEXT,
    member_id BIGINT UNSIGNED,
    donation_id BIGINT UNSIGNED,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE CASCADE,
    FOREIGN KEY (accode) REFERENCES accountm(accode),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE SET NULL
);

-- Receipts (Specific for Member Transactions)
CREATE TABLE receipts (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    receipt_no VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED,
    donor_id BIGINT UNSIGNED,
    receipt_date DATE NOT NULL,
    receipt_type ENUM('donation', 'membership', 'madrassa_fee', 'rent', 'event', 'other') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'online', 'card', 'cheque') DEFAULT 'cash',
    transaction_id VARCHAR(255),
    reference_no VARCHAR(50),
    description TEXT,
    voucher_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE SET NULL,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Payments (Member Payments)
CREATE TABLE payments (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    payment_no VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED,
    supplier_id BIGINT UNSIGNED,
    payment_date DATE NOT NULL,
    payment_type ENUM('expense', 'supplier', 'salary', 'refund', 'other') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    payment_method ENUM('cash', 'bank_transfer', 'online', 'card', 'cheque') DEFAULT 'cash',
    transaction_id VARCHAR(255),
    reference_no VARCHAR(50),
    description TEXT,
    voucher_id BIGINT UNSIGNED,
    approved_by BIGINT UNSIGNED,
    approved_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Journal Entries
CREATE TABLE journal_entries (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    journal_no VARCHAR(50) UNIQUE NOT NULL,
    journal_date DATE NOT NULL,
    description TEXT NOT NULL,
    is_adjustment BOOLEAN DEFAULT FALSE,
    voucher_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Credit Notes
CREATE TABLE credit_notes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    credit_note_no VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED,
    donor_id BIGINT UNSIGNED,
    receipt_id BIGINT UNSIGNED,
    credit_note_date DATE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    reason VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('issued', 'used', 'cancelled') DEFAULT 'issued',
    voucher_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE SET NULL,
    FOREIGN KEY (receipt_id) REFERENCES receipts(id) ON DELETE SET NULL,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Debit Notes
CREATE TABLE debit_notes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    debit_note_no VARCHAR(50) UNIQUE NOT NULL,
    member_id BIGINT UNSIGNED,
    supplier_id BIGINT UNSIGNED,
    payment_id BIGINT UNSIGNED,
    debit_note_date DATE NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    reason VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('issued', 'used', 'cancelled') DEFAULT 'issued',
    voucher_id BIGINT UNSIGNED,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Bank Reconciliation
CREATE TABLE bank_reconciliations (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    company_id BIGINT UNSIGNED NOT NULL,
    masjid_id BIGINT UNSIGNED NOT NULL,
    bank_account VARCHAR(20) NOT NULL,
    reconciliation_date DATE NOT NULL,
    statement_balance DECIMAL(15,2) NOT NULL,
    system_balance DECIMAL(15,2) NOT NULL,
    difference_amount DECIMAL(15,2) NOT NULL,
    status ENUM('draft', 'in_progress', 'completed') DEFAULT 'draft',
    remarks TEXT,
    completed_by BIGINT UNSIGNED,
    completed_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id),
    FOREIGN KEY (masjid_id) REFERENCES masjids(id),
    FOREIGN KEY (completed_by) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- Bank Reconciliation Items
CREATE TABLE bank_reconciliation_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    reconciliation_id BIGINT UNSIGNED NOT NULL,
    daybook_id BIGINT UNSIGNED NOT NULL,
    status ENUM('matched', 'unmatched', 'cleared') DEFAULT 'unmatched',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reconciliation_id) REFERENCES bank_reconciliations(id) ON DELETE CASCADE,
    FOREIGN KEY (daybook_id) REFERENCES daybook(id)
);
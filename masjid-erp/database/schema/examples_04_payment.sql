-- Scenario: Payment to supplier for maintenance
-- Amount: SAR 5,000
-- Voucher Type: PAYMENT
-- Voucher No: PAY-2024-001

-- Entry 1: Debit Maintenance Expense
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks) VALUES
(1, 1, '404', '102', 5000.00, 'dr', 'payment', 'PAY-2024-001', '2024-01-20', 'Maintenance payment');

-- Entry 2: Credit Bank
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks) VALUES
(1, 2, '102', '404', 5000.00, 'cr', 'payment', 'PAY-2024-001', '2024-01-20', 'Maintenance payment');
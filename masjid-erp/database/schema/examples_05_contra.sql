-- Scenario: Transfer from Main Bank to Savings Bank
-- Amount: SAR 10,000
-- Voucher Type: CONTRA
-- Voucher No: CTR-2024-001

-- Entry 1: Debit Savings Bank
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks) VALUES
(1, 1, '103', '102', 10000.00, 'dr', 'contra', 'CTR-2024-001', '2024-01-28', 'Transfer to savings');

-- Entry 2: Credit Main Bank
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks) VALUES
(1, 2, '102', '103', 10000.00, 'cr', 'contra', 'CTR-2024-001', '2024-01-28', 'Transfer from main');
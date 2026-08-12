-- Scenario: Debit note issued to member for additional charges
-- Amount: SAR 200
-- Voucher Type: DEBIT_NOTE
-- Voucher No: DN-2024-001

-- Entry 1: Debit Receivable
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks, member_id) VALUES
(1, 1, '105', '301', 200.00, 'dr', 'debit_note', 'DN-2024-001', '2024-01-26', 'Debit note for additional charges', 1);

-- Entry 2: Credit Revenue
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks, member_id) VALUES
(1, 2, '301', '105', 200.00, 'cr', 'debit_note', 'DN-2024-001', '2024-01-26', 'Additional revenue', 1);
-- Scenario: Credit note issued to member for overpayment
-- Amount: SAR 500
-- Voucher Type: CREDIT_NOTE
-- Voucher No: CN-2024-001

-- Entry 1: Debit Donation Revenue (Reduce Income)
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks, member_id) VALUES
(1, 1, '301', '205', 500.00, 'dr', 'credit_note', 'CN-2024-001', '2024-01-25', 'Credit note for overpayment', 1);

-- Entry 2: Credit Advance Payment (Liability)
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks, member_id) VALUES
(1, 2, '205', '301', 500.00, 'cr', 'credit_note', 'CN-2024-001', '2024-01-25', 'Credit note liability', 1);
-- Scenario: Monthly salary payment
-- Amount: SAR 50,000
-- Voucher Type: JOURNAL
-- Voucher No: JNL-2024-001

-- Entry 1: Debit Staff Salaries (Expense)
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks) VALUES
(1, 1, '401', '206', 50000.00, 'dr', 'journal', 'JNL-2024-001', '2024-01-31', 'Monthly salary expense');

-- Entry 2: Credit Salary Payable (Liability)
INSERT INTO daybook (company_id, sno, accode, opaccode, amount, drcr, voucher_type, voucher_no, voucher_date, remarks) VALUES
(1, 2, '206', '401', 50000.00, 'cr', 'journal', 'JNL-2024-001', '2024-01-31', 'Salary payable');
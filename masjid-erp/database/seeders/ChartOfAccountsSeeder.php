<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Default chart of accounts for a single company (id 1), taken verbatim from
 * the INSERT statements in the source dump's accounting schema file
 * (deepseek_sql_20260728_f189e9.sql). These rows sit outside any CREATE
 * TABLE block so the migration generator didn't pick them up automatically;
 * kept here as a seeder instead of losing them.
 */
class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('accountgbs')->insert([
            ['company_id' => 1, 'bshead' => 'ASSETS', 'bshead_name' => 'Assets'],
            ['company_id' => 1, 'bshead' => 'LIABILITIES', 'bshead_name' => 'Liabilities'],
            ['company_id' => 1, 'bshead' => 'INCOME', 'bshead_name' => 'Income'],
            ['company_id' => 1, 'bshead' => 'EXPENSES', 'bshead_name' => 'Expenses'],
        ]);

        $bsheadIds = DB::table('accountgbs')->pluck('id', 'bshead');

        DB::table('accountg')->insert([
            ['company_id' => 1, 'grcode' => 'CA', 'grname' => 'Current Assets', 'bshead_id' => $bsheadIds['ASSETS'], 'parent_grcode' => null],
            ['company_id' => 1, 'grcode' => 'FA', 'grname' => 'Fixed Assets', 'bshead_id' => $bsheadIds['ASSETS'], 'parent_grcode' => null],
            ['company_id' => 1, 'grcode' => 'CL', 'grname' => 'Current Liabilities', 'bshead_id' => $bsheadIds['LIABILITIES'], 'parent_grcode' => null],
            ['company_id' => 1, 'grcode' => 'LL', 'grname' => 'Long Term Liabilities', 'bshead_id' => $bsheadIds['LIABILITIES'], 'parent_grcode' => null],
            ['company_id' => 1, 'grcode' => 'OP', 'grname' => 'Operating Income', 'bshead_id' => $bsheadIds['INCOME'], 'parent_grcode' => null],
            ['company_id' => 1, 'grcode' => 'OI', 'grname' => 'Other Income', 'bshead_id' => $bsheadIds['INCOME'], 'parent_grcode' => null],
            ['company_id' => 1, 'grcode' => 'OE', 'grname' => 'Operating Expenses', 'bshead_id' => $bsheadIds['EXPENSES'], 'parent_grcode' => null],
            ['company_id' => 1, 'grcode' => 'NE', 'grname' => 'Non-Operating Expenses', 'bshead_id' => $bsheadIds['EXPENSES'], 'parent_grcode' => null],
        ]);

        DB::table('accountm')->insert([
            // Asset Accounts
            ['company_id' => 1, 'accode' => '101', 'name' => 'Cash in Hand', 'grcode' => 'CA', 'bshead' => 'ASSETS', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '102', 'name' => 'Bank Account - Main', 'grcode' => 'CA', 'bshead' => 'ASSETS', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '103', 'name' => 'Bank Account - Savings', 'grcode' => 'CA', 'bshead' => 'ASSETS', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '104', 'name' => 'Bank Account - Waqf', 'grcode' => 'CA', 'bshead' => 'ASSETS', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '105', 'name' => 'Accounts Receivable', 'grcode' => 'CA', 'bshead' => 'ASSETS', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '106', 'name' => 'Security Deposit Receivable', 'grcode' => 'CA', 'bshead' => 'ASSETS', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '107', 'name' => 'Masjid Property', 'grcode' => 'FA', 'bshead' => 'ASSETS', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '108', 'name' => 'Waqf Properties', 'grcode' => 'FA', 'bshead' => 'ASSETS', 'actype' => 'debit', 'opening_balance' => 0],

            // Liability Accounts
            ['company_id' => 1, 'accode' => '201', 'name' => 'Accounts Payable', 'grcode' => 'CL', 'bshead' => 'LIABILITIES', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '202', 'name' => 'Security Deposit Payable', 'grcode' => 'CL', 'bshead' => 'LIABILITIES', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '203', 'name' => 'GST Payable - CGST', 'grcode' => 'CL', 'bshead' => 'LIABILITIES', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '204', 'name' => 'GST Payable - SGST', 'grcode' => 'CL', 'bshead' => 'LIABILITIES', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '205', 'name' => 'Advance Payments Received', 'grcode' => 'CL', 'bshead' => 'LIABILITIES', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '206', 'name' => 'Staff Salary Payable', 'grcode' => 'CL', 'bshead' => 'LIABILITIES', 'actype' => 'credit', 'opening_balance' => 0],

            // Income Accounts
            ['company_id' => 1, 'accode' => '301', 'name' => 'Donation Revenue', 'grcode' => 'OP', 'bshead' => 'INCOME', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '302', 'name' => 'Zakat Revenue', 'grcode' => 'OP', 'bshead' => 'INCOME', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '303', 'name' => 'Sadaqah Revenue', 'grcode' => 'OP', 'bshead' => 'INCOME', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '304', 'name' => 'Membership Fees', 'grcode' => 'OP', 'bshead' => 'INCOME', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '305', 'name' => 'Madrassa Fees', 'grcode' => 'OP', 'bshead' => 'INCOME', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '306', 'name' => 'Waqf Rental Income', 'grcode' => 'OP', 'bshead' => 'INCOME', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '307', 'name' => 'Event Income', 'grcode' => 'OI', 'bshead' => 'INCOME', 'actype' => 'credit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '308', 'name' => 'Investment Income', 'grcode' => 'OI', 'bshead' => 'INCOME', 'actype' => 'credit', 'opening_balance' => 0],

            // Expense Accounts
            ['company_id' => 1, 'accode' => '401', 'name' => 'Staff Salaries', 'grcode' => 'OE', 'bshead' => 'EXPENSES', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '402', 'name' => 'Utilities - Electricity', 'grcode' => 'OE', 'bshead' => 'EXPENSES', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '403', 'name' => 'Utilities - Water', 'grcode' => 'OE', 'bshead' => 'EXPENSES', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '404', 'name' => 'Maintenance - Masjid', 'grcode' => 'OE', 'bshead' => 'EXPENSES', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '405', 'name' => 'Maintenance - Waqf', 'grcode' => 'OE', 'bshead' => 'EXPENSES', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '406', 'name' => 'Education Materials', 'grcode' => 'OE', 'bshead' => 'EXPENSES', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '407', 'name' => 'Insurance', 'grcode' => 'OE', 'bshead' => 'EXPENSES', 'actype' => 'debit', 'opening_balance' => 0],
            ['company_id' => 1, 'accode' => '408', 'name' => 'Bank Charges', 'grcode' => 'NE', 'bshead' => 'EXPENSES', 'actype' => 'debit', 'opening_balance' => 0],
        ]);
    }
}

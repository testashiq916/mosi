<?php

namespace App\Services\Accounting;

use App\Models\Daybook;
use App\Models\Donation;
use App\Models\Voucher;
use App\Models\VoucherDetail;
use Illuminate\Support\Facades\DB;

/**
 * Posts double-entry accounting records for member-facing transactions.
 *
 * Referenced by MemberDonationController but not included in the source
 * dump — implemented here from the worked examples that were provided
 * (examples_01_receipt_donation.sql): debit the cash/bank account (102),
 * credit the donation revenue account (301), one daybook row per side.
 */
class AccountingService
{
    protected const CASH_ACCOUNT = '102';       // Bank Account - Main
    protected const DONATION_REVENUE_ACCOUNT = '301'; // Donation Revenue

    public function createDonationEntries(Donation $donation): Voucher
    {
        return DB::transaction(function () use ($donation) {
            $voucher = Voucher::create([
                'company_id' => $donation->company_id,
                'voucher_no' => 'RCP-' . $donation->donation_id,
                'voucher_type' => 'receipt',
                'voucher_date' => $donation->donation_date,
                'narration' => $donation->purpose ?? 'Donation received',
                'total_amount' => $donation->amount,
                'is_posted' => true,
                'posted_by' => $donation->created_by,
                'posted_at' => now(),
                'created_by' => $donation->created_by,
            ]);

            VoucherDetail::create([
                'voucher_id' => $voucher->id,
                'slno' => 1,
                'accode' => self::CASH_ACCOUNT,
                'dr_amount' => $donation->amount,
                'cr_amount' => 0,
                'remarks' => 'Donation received from member',
                'member_id' => $donation->member_id,
                'donation_id' => $donation->id,
            ]);

            VoucherDetail::create([
                'voucher_id' => $voucher->id,
                'slno' => 2,
                'accode' => self::DONATION_REVENUE_ACCOUNT,
                'dr_amount' => 0,
                'cr_amount' => $donation->amount,
                'remarks' => 'Donation revenue',
                'member_id' => $donation->member_id,
                'donation_id' => $donation->id,
            ]);

            $this->postDaybookPair(
                companyId: $donation->company_id,
                voucher: $voucher,
                debitAccode: self::CASH_ACCOUNT,
                creditAccode: self::DONATION_REVENUE_ACCOUNT,
                amount: $donation->amount,
                remarks: 'Donation received from member',
                memberId: $donation->member_id,
                donationId: $donation->id,
            );

            $donation->update(['voucher_id' => $voucher->id]);

            return $voucher;
        });
    }

    protected function postDaybookPair(
        int $companyId,
        Voucher $voucher,
        string $debitAccode,
        string $creditAccode,
        float $amount,
        string $remarks,
        ?int $memberId = null,
        ?int $donationId = null,
    ): void {
        Daybook::create([
            'company_id' => $companyId,
            'sno' => 1,
            'accode' => $debitAccode,
            'opaccode' => $creditAccode,
            'amount' => $amount,
            'drcr' => 'dr',
            'voucher_type' => $voucher->voucher_type,
            'voucher_no' => $voucher->voucher_no,
            'voucher_date' => $voucher->voucher_date,
            'remarks' => $remarks,
            'member_id' => $memberId,
            'donation_id' => $donationId,
            'created_by' => $voucher->created_by,
        ]);

        Daybook::create([
            'company_id' => $companyId,
            'sno' => 2,
            'accode' => $creditAccode,
            'opaccode' => $debitAccode,
            'amount' => $amount,
            'drcr' => 'cr',
            'voucher_type' => $voucher->voucher_type,
            'voucher_no' => $voucher->voucher_no,
            'voucher_date' => $voucher->voucher_date,
            'remarks' => $remarks,
            'member_id' => $memberId,
            'donation_id' => $donationId,
            'created_by' => $voucher->created_by,
        ]);
    }
}

<?php
// Member Donation Controller

namespace App\Http\Controllers\Member;

use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\Receipt;
use App\Services\Accounting\AccountingService;
use Illuminate\Http\Request;

class MemberDonationController extends Controller
{
    protected $accountingService;

    public function __construct(AccountingService $accountingService)
    {
        $this->accountingService = $accountingService;
    }

    public function index()
    {
        $member = Member::where('user_id', auth()->id())->first();
        $categories = DonationCategory::where('masjid_id', $member->masjid_id)->get();
        
        return view('member.donate', [
            'categories' => $categories,
            'member' => $member
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:donation_categories,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:cash,bank_transfer,online,card,cheque',
            'is_anonymous' => 'boolean',
            'is_recurring' => 'boolean',
            'purpose' => 'nullable|string|max:255'
        ]);

        $member = Member::where('user_id', auth()->id())->first();

        $donation = Donation::create([
            'company_id' => $member->company_id,
            'masjid_id' => $member->masjid_id,
            'member_id' => $member->id,
            'donor_id' => $member->id,
            'category_id' => $request->category_id,
            'donation_id' => $this->generateDonationId(),
            'amount' => $request->amount,
            'donation_date' => now(),
            'payment_method' => $request->payment_method,
            'is_anonymous' => $request->is_anonymous ?? false,
            'is_recurring' => $request->is_recurring ?? false,
            'purpose' => $request->purpose,
            'status' => 'received',
            'created_by' => auth()->id()
        ]);

        // Create accounting entries
        $this->accountingService->createDonationEntries($donation);

        // Generate receipt
        $receipt = $this->generateReceipt($donation);

        // Send confirmation
        $this->sendDonationConfirmation($donation);

        return redirect()->route('member.donations')
            ->with('success', 'Donation processed successfully. Receipt #' . $receipt->receipt_no);
    }

    public function recurringDonations()
    {
        $member = Member::where('user_id', auth()->id())->first();
        
        $recurringDonations = Donation::where('member_id', $member->id)
            ->where('is_recurring', true)
            ->where('status', 'received')
            ->get();

        return view('member.recurring-donations', compact('recurringDonations'));
    }

    public function cancelRecurring($id)
    {
        $member = Member::where('user_id', auth()->id())->first();
        $donation = Donation::where('member_id', $member->id)
            ->where('is_recurring', true)
            ->findOrFail($id);

        $donation->update([
            'is_recurring' => false,
            'status' => 'cancelled'
        ]);

        return redirect()->route('member.recurring-donations')
            ->with('success', 'Recurring donation cancelled successfully');
    }

    public function donationHistory()
    {
        $member = Member::where('user_id', auth()->id())->first();
        
        $donations = Donation::where('member_id', $member->id)
            ->with(['category', 'receipt'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('member.donation-history', compact('donations'));
    }

    public function downloadReceipt($id)
    {
        $member = Member::where('user_id', auth()->id())->first();
        
        $receipt = Receipt::where('member_id', $member->id)
            ->where('id', $id)
            ->firstOrFail();

        return $this->generateReceiptPDF($receipt);
    }

    protected function generateDonationId()
    {
        return 'DON-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    protected function generateReceipt($donation)
    {
        $receipt = Receipt::create([
            'company_id' => $donation->company_id,
            'masjid_id' => $donation->masjid_id,
            'receipt_no' => $this->generateReceiptNumber(),
            'member_id' => $donation->member_id,
            'donor_id' => $donation->donor_id,
            'receipt_date' => now(),
            'receipt_type' => 'donation',
            'amount' => $donation->amount,
            'payment_method' => $donation->payment_method,
            'description' => $donation->purpose ?? 'Donation',
            'created_by' => auth()->id()
        ]);

        $donation->update(['receipt_id' => $receipt->id]);

        return $receipt;
    }

    protected function generateReceiptNumber()
    {
        $prefix = 'RCP';
        $year = date('Y');
        $month = date('m');
        
        $last = Receipt::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $last ? intval(substr($last->receipt_no, -5)) + 1 : 1;
        
        return $prefix . '-' . $year . '-' . $month . '-' . str_pad($sequence, 5, '0', STR_PAD_LEFT);
    }

    protected function sendDonationConfirmation($donation)
    {
        // Send email confirmation
        \Mail::to($donation->member->email)->send(new \App\Mail\DonationConfirmation($donation));
        
        // Send SMS
        $this->sendSMS($donation->member->mobile, "Thank you for your donation of SAR {$donation->amount} to {$donation->masjid->name}. Receipt #{$donation->receipt->receipt_no}");
    }

    protected function sendSMS($phone, $message)
    {
        // SMS integration
        // ...
    }

    protected function generateReceiptPDF($receipt)
    {
        $pdf = \PDF::loadView('member.receipt-pdf', compact('receipt'));
        return $pdf->download('receipt-' . $receipt->receipt_no . '.pdf');
    }
}
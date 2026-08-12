<?php
// Member Dashboard Controller

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Donation;
use App\Models\Receipt;
use App\Models\CreditNote;
use App\Models\DebitNote;
use App\Models\Student;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class MemberDashboardController extends Controller
{
    public function index(Request $request)
    {
        $member = Member::where('user_id', auth()->id())->first();

        return view('member.dashboard', [
            'member' => $member,
            'totalDonations' => Donation::where('member_id', $member->id)->sum('amount'),
            'totalReceipts' => Receipt::where('member_id', $member->id)->count(),
            'activeStudents' => Student::where('guardian_email', $member->email)->where('status', 'active')->count(),
            'upcomingEvents' => EventRegistration::where('email', $member->email)
                ->whereHas('event', function($q) {
                    $q->where('event_date', '>=', now());
                })
                ->count(),
            'creditBalance' => $this->getCreditBalance($member),
            'recentTransactions' => $this->getRecentTransactions($member),
            'children' => Student::where('guardian_email', $member->email)->get(),
            'familyMembers' => $member->family,
            'donationTrend' => $this->getDonationTrend($member),
            'upcomingPayments' => $this->getUpcomingPayments($member)
        ]);
    }

    protected function getCreditBalance($member)
    {
        $credits = CreditNote::where('member_id', $member->id)
            ->where('status', 'issued')
            ->sum('amount');

        $usedCredits = CreditNote::where('member_id', $member->id)
            ->where('status', 'used')
            ->sum('amount');

        return $credits - $usedCredits;
    }

    protected function getRecentTransactions($member)
    {
        return Donation::where('member_id', $member->id)
            ->orWhere('donor_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    protected function getDonationTrend($member)
    {
        return Donation::where('member_id', $member->id)
            ->selectRaw('MONTH(donation_date) as month, YEAR(donation_date) as year, SUM(amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
    }

    protected function getUpcomingPayments($member)
    {
        // Get upcoming madrassa fee payments
        $feePayments = [];

        $students = Student::where('guardian_email', $member->email)->get();
        foreach ($students as $student) {
            $feePayments[] = [
                'type' => 'Madrassa Fee',
                'student' => $student->full_name,
                'amount' => $student->currentClass->fee_amount,
                'due_date' => now()->addDays(5)->format('Y-m-d')
            ];
        }

        return $feePayments;
    }
}

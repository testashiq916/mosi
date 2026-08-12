<?php
// Member Profile Controller

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberDocument;
use App\Models\Donation;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MemberProfileController extends Controller
{
    public function show()
    {
        $member = Member::where('user_id', auth()->id())
            ->with(['family', 'documents', 'memberType'])
            ->first();

        return view('member.profile', compact('member'));
    }

    public function update(Request $request)
    {
        $member = Member::where('user_id', auth()->id())->first();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
            'family_members' => 'nullable|array',
            'family_members.*.name' => 'required|string',
            'family_members.*.relationship' => 'required|string',
            'family_members.*.dob' => 'nullable|date',
            'family_members.*.gender' => 'nullable|in:male,female'
        ]);

        // Handle profile image
        if ($request->hasFile('profile_image')) {
            if ($member->profile_image) {
                Storage::delete($member->profile_image);
            }
            $path = $request->file('profile_image')->store('members/profile', 'public');
            $validated['profile_image'] = $path;
        }

        $member->update($validated);

        // Update family members
        if (isset($validated['family_members'])) {
            $member->family()->delete();
            foreach ($validated['family_members'] as $family) {
                $member->family()->create($family);
            }
        }

        return redirect()->route('member.profile')
            ->with('success', 'Profile updated successfully');
    }

    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document' => 'required|file|max:5120',
            'document_number' => 'nullable|string',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:issue_date'
        ]);

        $member = Member::where('user_id', auth()->id())->first();

        $path = $request->file('document')->store('members/documents', 'public');

        $document = $member->documents()->create([
            'document_type' => $request->document_type,
            'document_name' => $request->file('document')->getClientOriginalName(),
            'document_path' => $path,
            'document_number' => $request->document_number,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date
        ]);

        return response()->json([
            'success' => true,
            'document' => $document
        ]);
    }

    public function deleteDocument($id)
    {
        $member = Member::where('user_id', auth()->id())->first();
        $document = $member->documents()->findOrFail($id);

        Storage::delete($document->document_path);
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);
    }

    public function getDonations()
    {
        $member = Member::where('user_id', auth()->id())->first();

        $donations = Donation::where('member_id', $member->id)
            ->orWhere('donor_id', $member->id)
            ->with(['category', 'receipt'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('member.donations', compact('donations'));
    }

    public function getReceipts()
    {
        $member = Member::where('user_id', auth()->id())->first();

        $receipts = Receipt::where('member_id', $member->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('member.receipts', compact('receipts'));
    }

    public function downloadReceipt($id)
    {
        $member = Member::where('user_id', auth()->id())->first();
        $receipt = Receipt::where('member_id', $member->id)->findOrFail($id);

        return $this->generateReceiptPDF($receipt);
    }

    protected function generateReceiptPDF($receipt)
    {
        $pdf = \PDF::loadView('member.receipt-pdf', compact('receipt'));
        return $pdf->download('receipt-' . $receipt->receipt_no . '.pdf');
    }
}

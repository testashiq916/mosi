<?php

namespace App\Http\Controllers\API\V1\Marriage;

use App\Http\Controllers\API\V1\AdminApiController;
use App\Models\MarriageNocRequest;
use App\Models\MarriageRegistration;
use Illuminate\Http\Request;

/**
 * Backs the /api/v1/marriage/* routes defined in routes/api.php. Only the
 * route list existed in the source dump; implementation is new, built
 * against marriage_registrations / marriage_noc_requests
 * (database/schema/04_marriage.sql).
 */
class MarriageController extends AdminApiController
{
    public function registrations(Request $request)
    {
        $query = MarriageRegistration::where('masjid_id', $this->currentMasjidId($request))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->orderBy('marriage_date', 'desc');

        return response()->json($this->paginate($query, $request));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'groom_name' => 'required|string|max:255',
            'groom_father_name' => 'nullable|string|max:255',
            'groom_mobile' => 'nullable|string|max:20',
            'groom_address' => 'nullable|string',
            'bride_name' => 'required|string|max:255',
            'bride_father_name' => 'nullable|string|max:255',
            'bride_mobile' => 'nullable|string|max:20',
            'bride_address' => 'nullable|string',
            'marriage_date' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'officiant_name' => 'nullable|string|max:255',
            'dowry_amount' => 'nullable|numeric|min:0',
            'witness_1_name' => 'nullable|string|max:255',
            'witness_2_name' => 'nullable|string|max:255',
            'nok_name' => 'nullable|string|max:255',
            'nok_contact' => 'nullable|string|max:20',
        ]);

        $registration = MarriageRegistration::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'registration_id' => $this->generateRegistrationId(),
            'status' => 'pending',
            'created_by' => $request->user()->id,
        ]));

        return response()->json($registration, 201);
    }

    public function showRegistration(Request $request, int $id)
    {
        $registration = MarriageRegistration::where('masjid_id', $this->currentMasjidId($request))
            ->with('nocRequests')
            ->findOrFail($id);

        return response()->json($registration);
    }

    public function approve(Request $request, int $id)
    {
        $registration = MarriageRegistration::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $registration->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return response()->json($registration);
    }

    public function requestNOC(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_father_name' => 'nullable|string|max:255',
            'applicant_mobile' => 'nullable|string|max:20',
            'applicant_address' => 'nullable|string',
            'marriage_registration_id' => 'nullable|exists:marriage_registrations,id',
            'purpose' => 'nullable|string|max:255',
            'requested_date' => 'required|date',
        ]);

        $noc = MarriageNocRequest::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'noc_id' => $this->generateNocId(),
            'status' => 'pending',
            'created_by' => $request->user()->id,
        ]));

        return response()->json($noc, 201);
    }

    public function showNOC(Request $request, int $id)
    {
        $noc = MarriageNocRequest::where('masjid_id', $this->currentMasjidId($request))
            ->with('marriageRegistration')
            ->findOrFail($id);

        return response()->json($noc);
    }

    protected function generateRegistrationId(): string
    {
        return 'MRG-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    protected function generateNocId(): string
    {
        return 'NOC-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}

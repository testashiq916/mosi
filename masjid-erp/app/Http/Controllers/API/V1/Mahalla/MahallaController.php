<?php

namespace App\Http\Controllers\API\V1\Mahalla;

use App\Http\Controllers\API\V1\AdminApiController;
use App\Models\MahallaResident;
use Illuminate\Http\Request;

/**
 * Backs the /api/v1/mahalla/residents/* routes defined in routes/api.php.
 * Only the route list existed in the source dump; this implementation is
 * new, built against the mahalla_residents / resident_dependents schema
 * that was provided (database/schema/03_mahalla.sql).
 */
class MahallaController extends AdminApiController
{
    public function index(Request $request)
    {
        $query = MahallaResident::where('masjid_id', $this->currentMasjidId($request))
            ->with(['residentType', 'dependents'])
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->input('search');
                $q->where(function ($inner) use ($term) {
                    $inner->where('first_name', 'like', "%{$term}%")
                        ->orWhere('last_name', 'like', "%{$term}%")
                        ->orWhere('resident_id', 'like', "%{$term}%")
                        ->orWhere('mobile', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('is_active'), fn ($q) => $q->where('is_active', $request->boolean('is_active')))
            ->orderBy('first_name');

        return response()->json($this->paginate($query, $request));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'resident_type_id' => 'nullable|exists:resident_types,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'arabic_name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:20',
            'alternate_mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'id_proof_type' => 'nullable|string|max:50',
            'id_proof_number' => 'nullable|string|max:100',
            'subscription_status' => 'nullable|in:active,inactive,expired',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date|after_or_equal:subscription_start_date',
        ]);

        $resident = MahallaResident::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'resident_id' => $this->generateResidentId(),
            'is_member' => (bool) ($validated['member_id'] ?? null),
            'created_by' => $request->user()->id,
        ]));

        return response()->json($resident, 201);
    }

    public function show(Request $request, int $id)
    {
        $resident = MahallaResident::where('masjid_id', $this->currentMasjidId($request))
            ->with(['residentType', 'dependents', 'member'])
            ->findOrFail($id);

        return response()->json($resident);
    }

    public function update(Request $request, int $id)
    {
        $resident = MahallaResident::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'resident_type_id' => 'nullable|exists:resident_types,id',
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'arabic_name' => 'nullable|string|max:255',
            'gender' => 'sometimes|required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:255',
            'mobile' => 'sometimes|required|string|max:20',
            'alternate_mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'subscription_status' => 'nullable|in:active,inactive,expired',
            'subscription_start_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date|after_or_equal:subscription_start_date',
        ]);

        $resident->update($validated);

        return response()->json($resident);
    }

    public function destroy(Request $request, int $id)
    {
        $resident = MahallaResident::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);
        $resident->update(['is_active' => false]);

        return response()->json(['success' => true, 'message' => 'Resident deactivated']);
    }

    public function addDependent(Request $request, int $id)
    {
        $resident = MahallaResident::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'relationship' => 'required|string|max:50',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ]);

        $dependent = $resident->dependents()->create($validated);

        return response()->json($dependent, 201);
    }

    protected function generateResidentId(): string
    {
        return 'RES-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}

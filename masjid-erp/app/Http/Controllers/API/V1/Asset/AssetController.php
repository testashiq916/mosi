<?php

namespace App\Http\Controllers\API\V1\Asset;

use App\Http\Controllers\API\V1\AdminApiController;
use App\Models\FixedAsset;
use Illuminate\Http\Request;

/**
 * Backs the /api/v1/assets/* routes defined in routes/api.php. Only the
 * route list existed in the source dump; implementation is new, built
 * against fixed_assets / asset_categories / asset_maintenance
 * (database/schema/08_assets.sql).
 */
class AssetController extends AdminApiController
{
    public function index(Request $request)
    {
        $query = FixedAsset::where('masjid_id', $this->currentMasjidId($request))
            ->with('category')
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->input('category_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->orderBy('name');

        return response()->json($this->paginate($query, $request));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:asset_categories,id',
            'name' => 'required|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'manufacturer' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'depreciation_method' => 'nullable|in:straight_line,declining_balance,units_of_production',
            'depreciation_rate' => 'nullable|numeric|min:0|max:100',
            'salvage_value' => 'nullable|numeric|min:0',
            'useful_life_years' => 'nullable|integer|min:0',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'warranty_expiry' => 'nullable|date',
        ]);

        $asset = FixedAsset::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'asset_id' => $this->generateAssetId(),
            'current_value' => $validated['purchase_price'] ?? 0,
            'status' => 'active',
            'created_by' => $request->user()->id,
        ]));

        return response()->json($asset, 201);
    }

    public function show(Request $request, int $id)
    {
        $asset = FixedAsset::where('masjid_id', $this->currentMasjidId($request))
            ->with(['category', 'maintenanceRecords'])
            ->findOrFail($id);

        return response()->json($asset);
    }

    public function update(Request $request, int $id)
    {
        $asset = FixedAsset::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'current_value' => 'nullable|numeric|min:0',
            'location' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:active,in_maintenance,disposed,lost,inactive',
        ]);

        $asset->update($validated);

        return response()->json($asset);
    }

    public function destroy(Request $request, int $id)
    {
        $asset = FixedAsset::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);
        $asset->update(['status' => 'disposed']);

        return response()->json(['success' => true, 'message' => 'Asset marked as disposed']);
    }

    public function scheduleMaintenance(Request $request, int $id)
    {
        $asset = FixedAsset::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'maintenance_type' => 'required|in:routine,repair,emergency,preventive',
            'description' => 'required|string',
            'scheduled_date' => 'required|date',
            'vendor_name' => 'nullable|string|max:255',
            'vendor_contact' => 'nullable|string|max:20',
        ]);

        $maintenance = $asset->maintenanceRecords()->create(array_merge($validated, [
            'company_id' => $asset->company_id,
            'masjid_id' => $asset->masjid_id,
            'maintenance_id' => $this->generateMaintenanceId(),
            'status' => 'scheduled',
            'created_by' => $request->user()->id,
        ]));

        $asset->update(['status' => 'in_maintenance']);

        return response()->json($maintenance, 201);
    }

    protected function generateAssetId(): string
    {
        return 'AST-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    protected function generateMaintenanceId(): string
    {
        return 'MNT-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}

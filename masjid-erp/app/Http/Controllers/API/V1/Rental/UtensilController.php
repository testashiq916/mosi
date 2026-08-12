<?php

namespace App\Http\Controllers\API\V1\Rental;

use App\Http\Controllers\API\V1\AdminApiController;
use App\Models\RentalItem;
use App\Models\Utensil;
use App\Models\UtensilRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Backs the /api/v1/utensils/* routes defined in routes/api.php. Only the
 * route list existed in the source dump; implementation is new, built
 * against utensils / utensil_categories / utensil_rentals / rental_items
 * (database/schema/05_utensil_rental.sql). Each utensil tracks its own
 * quantity/available_quantity, so rent()/return() keep those counters in
 * sync with the rental_items rows they create.
 */
class UtensilController extends AdminApiController
{
    public function index(Request $request)
    {
        $query = Utensil::where('masjid_id', $this->currentMasjidId($request))
            ->with('category')
            ->when($request->filled('category_id'), fn ($q) => $q->where('category_id', $request->input('category_id')))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->orderBy('name');

        return response()->json($this->paginate($query, $request));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:utensil_categories,id',
            'name' => 'required|string|max:255',
            'arabic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit' => 'nullable|string|max:20',
            'rental_fee' => 'nullable|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'min_rental_period' => 'nullable|integer|min:1',
            'max_rental_period' => 'nullable|integer|min:1',
            'location' => 'nullable|string|max:255',
        ]);

        $utensil = Utensil::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'item_code' => $this->generateItemCode(),
            'available_quantity' => $validated['quantity'],
            'damaged_quantity' => 0,
            'status' => 'available',
            'created_by' => $request->user()->id,
        ]));

        return response()->json($utensil, 201);
    }

    public function show(Request $request, int $id)
    {
        $utensil = Utensil::where('masjid_id', $this->currentMasjidId($request))
            ->with('category')
            ->findOrFail($id);

        return response()->json($utensil);
    }

    public function rent(Request $request, int $id)
    {
        $utensil = Utensil::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'resident_id' => 'nullable|exists:mahalla_residents,id',
            'member_id' => 'nullable|exists:members,id',
            'rental_date' => 'required|date',
            'expected_return_date' => 'required|date|after_or_equal:rental_date',
            'purpose' => 'nullable|string|max:255',
            'event_type' => 'nullable|string|max:100',
        ]);

        if ($validated['quantity'] > $utensil->available_quantity) {
            throw new HttpException(422, "Only {$utensil->available_quantity} of {$utensil->name} available.");
        }

        return response()->json(
            DB::transaction(function () use ($utensil, $validated, $request) {
                $totalFee = ($utensil->rental_fee ?? 0) * $validated['quantity'];
                $deposit = ($utensil->security_deposit ?? 0) * $validated['quantity'];

                $rental = UtensilRental::create([
                    'company_id' => $utensil->company_id,
                    'masjid_id' => $utensil->masjid_id,
                    'rental_id' => $this->generateRentalId(),
                    'resident_id' => $validated['resident_id'] ?? null,
                    'member_id' => $validated['member_id'] ?? null,
                    'rental_date' => $validated['rental_date'],
                    'expected_return_date' => $validated['expected_return_date'],
                    'purpose' => $validated['purpose'] ?? null,
                    'event_type' => $validated['event_type'] ?? null,
                    'security_deposit' => $deposit,
                    'total_rental_fee' => $totalFee,
                    'status' => 'active',
                    'checked_out_by' => $request->user()->id,
                    'checked_out_at' => now(),
                    'created_by' => $request->user()->id,
                ]);

                RentalItem::create([
                    'rental_id' => $rental->id,
                    'utensil_id' => $utensil->id,
                    'quantity' => $validated['quantity'],
                ]);

                $utensil->decrement('available_quantity', $validated['quantity']);
                if ($utensil->available_quantity <= 0) {
                    $utensil->update(['status' => 'rented']);
                }

                return $rental->load('items.utensil');
            }),
            201
        );
    }

    public function return(Request $request, int $id)
    {
        $rental = UtensilRental::with('items.utensil')->findOrFail($id);

        if ($rental->masjid_id !== $this->currentMasjidId($request)) {
            throw new HttpException(404, 'Rental not found.');
        }

        $validated = $request->validate([
            'damaged_quantity' => 'nullable|integer|min:0',
            'damage_charge' => 'nullable|numeric|min:0',
            'condition_notes' => 'nullable|string',
        ]);

        return response()->json(
            DB::transaction(function () use ($rental, $validated) {
                $damagedQty = $validated['damaged_quantity'] ?? 0;
                $returnDate = now();
                $lateFee = 0;

                if ($returnDate->greaterThan($rental->expected_return_date->copy()->endOfDay())) {
                    $daysLate = $rental->expected_return_date->diffInDays($returnDate);
                    $lateFee = $daysLate * 5; // flat per-day late fee, not specified in the source schema
                }

                foreach ($rental->items as $item) {
                    $returnedQty = $item->quantity - $damagedQty;
                    $item->update([
                        'returned_quantity' => max($returnedQty, 0),
                        'damaged_quantity' => $damagedQty,
                    ]);

                    $item->utensil->increment('available_quantity', max($returnedQty, 0));
                    $item->utensil->increment('damaged_quantity', $damagedQty);
                    if ($item->utensil->available_quantity > 0 && $item->utensil->status === 'rented') {
                        $item->utensil->update(['status' => 'available']);
                    }
                }

                $rental->update([
                    'return_date' => $returnDate->toDateString(),
                    'late_fee' => $lateFee,
                    'damage_charge' => $validated['damage_charge'] ?? 0,
                    'condition_notes' => $validated['condition_notes'] ?? null,
                    'status' => $damagedQty > 0 ? 'damaged' : 'returned',
                    'checked_in_by' => request()->user()->id,
                    'checked_in_at' => $returnDate,
                ]);

                return $rental->fresh('items.utensil');
            })
        );
    }

    public function getRentals(Request $request)
    {
        $query = UtensilRental::where('masjid_id', $this->currentMasjidId($request))
            ->with('items.utensil')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->orderBy('rental_date', 'desc');

        return response()->json($this->paginate($query, $request));
    }

    protected function generateItemCode(): string
    {
        return 'UTN-' . strtoupper(uniqid());
    }

    protected function generateRentalId(): string
    {
        return 'RNT-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}

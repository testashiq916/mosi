<?php

namespace App\Http\Controllers\API\V1\Staff;

use App\Http\Controllers\API\V1\AdminApiController;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffPayroll;
use Illuminate\Http\Request;

/**
 * Backs the /api/v1/staff/* routes defined in routes/api.php. Only the
 * route list existed in the source dump; implementation is new, built
 * against staff / staff_roles / staff_payroll / staff_attendance
 * (database/schema/09_staff.sql).
 */
class StaffController extends AdminApiController
{
    public function index(Request $request)
    {
        $query = Staff::where('masjid_id', $this->currentMasjidId($request))
            ->with('role')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('staff_role_id'), fn ($q) => $q->where('staff_role_id', $request->input('staff_role_id')))
            ->orderBy('first_name');

        return response()->json($this->paginate($query, $request));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'staff_role_id' => 'required|exists:staff_roles,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'arabic_name' => 'nullable|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'email' => 'nullable|email|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
            'qualification' => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'joining_date' => 'nullable|date',
            'contract_type' => 'nullable|in:permanent,contract,temporary,volunteer',
            'basic_salary' => 'nullable|numeric|min:0',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc' => 'nullable|string|max:20',
        ]);

        $staff = Staff::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'staff_id' => $this->generateStaffId(),
            'status' => 'active',
            'created_by' => $request->user()->id,
        ]));

        return response()->json($staff, 201);
    }

    public function show(Request $request, int $id)
    {
        $staff = Staff::where('masjid_id', $this->currentMasjidId($request))
            ->with('role')
            ->findOrFail($id);

        return response()->json($staff);
    }

    public function update(Request $request, int $id)
    {
        $staff = Staff::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'staff_role_id' => 'sometimes|required|exists:staff_roles,id',
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'email' => 'nullable|email|max:255',
            'mobile' => 'sometimes|required|string|max:20',
            'basic_salary' => 'nullable|numeric|min:0',
            'status' => 'sometimes|required|in:active,inactive,on_leave,resigned,terminated',
        ]);

        $staff->update($validated);

        return response()->json($staff);
    }

    public function processPayroll(Request $request, int $id)
    {
        $staff = Staff::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2000',
            'overtime_hours' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|array',
        ]);

        $basicSalary = (float) $staff->basic_salary;
        $allowances = collect($staff->allowances ?? [])->sum();
        $overtimeAmount = ($validated['overtime_hours'] ?? 0) * ($validated['overtime_rate'] ?? 0);
        $totalDeductions = collect($validated['deductions'] ?? [])->sum();
        $totalEarnings = $basicSalary + $allowances + $overtimeAmount;
        $netSalary = $totalEarnings - $totalDeductions;

        $payroll = StaffPayroll::updateOrCreate(
            ['staff_id' => $staff->id, 'month' => $validated['month'], 'year' => $validated['year']],
            [
                'company_id' => $staff->company_id,
                'masjid_id' => $staff->masjid_id,
                'payroll_id' => $this->generatePayrollId(),
                'basic_salary' => $basicSalary,
                'allowances' => $allowances,
                'overtime_hours' => $validated['overtime_hours'] ?? 0,
                'overtime_amount' => $overtimeAmount,
                'deductions' => $validated['deductions'] ?? [],
                'total_earnings' => $totalEarnings,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'status' => 'processed',
                'created_by' => $request->user()->id,
            ]
        );

        return response()->json($payroll, 201);
    }

    public function getAttendance(Request $request, int $id)
    {
        $staff = Staff::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $query = $staff->attendance()
            ->when($request->filled('from'), fn ($q) => $q->whereDate('attendance_date', '>=', $request->input('from')))
            ->when($request->filled('to'), fn ($q) => $q->whereDate('attendance_date', '<=', $request->input('to')))
            ->orderBy('attendance_date', 'desc');

        return response()->json($this->paginate($query, $request));
    }

    public function markAttendance(Request $request, int $id)
    {
        $staff = Staff::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'attendance_date' => 'required|date',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'status' => 'required|in:present,absent,late,leave,holiday',
            'remarks' => 'nullable|string',
        ]);

        $workingHours = null;
        if (! empty($validated['check_in_time']) && ! empty($validated['check_out_time'])) {
            $in = \Carbon\Carbon::createFromFormat('H:i', $validated['check_in_time']);
            $out = \Carbon\Carbon::createFromFormat('H:i', $validated['check_out_time']);
            $workingHours = round($out->diffInMinutes($in) / 60, 2);
        }

        $attendance = StaffAttendance::updateOrCreate(
            ['staff_id' => $staff->id, 'attendance_date' => $validated['attendance_date']],
            [
                'company_id' => $staff->company_id,
                'check_in_time' => $validated['check_in_time'] ?? null,
                'check_out_time' => $validated['check_out_time'] ?? null,
                'status' => $validated['status'],
                'working_hours' => $workingHours,
                'remarks' => $validated['remarks'] ?? null,
            ]
        );

        return response()->json($attendance, 201);
    }

    protected function generateStaffId(): string
    {
        return 'STF-' . date('Ymd') . '-' . strtoupper(uniqid());
    }

    protected function generatePayrollId(): string
    {
        return 'PAY-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}

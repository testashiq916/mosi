<?php

namespace App\Http\Controllers\API\V1\Meeting;

use App\Http\Controllers\API\V1\AdminApiController;
use App\Models\Meeting;
use App\Models\MeetingAction;
use Illuminate\Http\Request;

/**
 * Backs the /api/v1/meetings/* routes defined in routes/api.php. Only the
 * route list existed in the source dump; implementation is new, built
 * against meetings / meeting_actions (database/schema/07_meetings.sql).
 */
class MeetingController extends AdminApiController
{
    public function index(Request $request)
    {
        $query = Meeting::where('masjid_id', $this->currentMasjidId($request))
            ->with('committee')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('meeting_type'), fn ($q) => $q->where('meeting_type', $request->input('meeting_type')))
            ->orderBy('meeting_date', 'desc');

        return response()->json($this->paginate($query, $request));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'committee_id' => 'nullable|exists:committees,id',
            'title' => 'required|string|max:255',
            'meeting_type' => 'required|in:committee,management,annual,emergency,regular,other',
            'meeting_date' => 'required|date',
            'end_time' => 'nullable|date|after:meeting_date',
            'venue' => 'nullable|string|max:255',
            'agenda' => 'nullable|string',
        ]);

        $meeting = Meeting::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'meeting_id' => $this->generateMeetingId(),
            'status' => 'scheduled',
            'created_by' => $request->user()->id,
        ]));

        return response()->json($meeting, 201);
    }

    public function show(Request $request, int $id)
    {
        $meeting = Meeting::where('masjid_id', $this->currentMasjidId($request))
            ->with(['committee', 'actionItems'])
            ->findOrFail($id);

        return response()->json($meeting);
    }

    public function update(Request $request, int $id)
    {
        $meeting = Meeting::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'meeting_date' => 'sometimes|required|date',
            'end_time' => 'nullable|date|after:meeting_date',
            'venue' => 'nullable|string|max:255',
            'agenda' => 'nullable|string',
            'minutes_text' => 'nullable|string',
            'decisions' => 'nullable|string',
            'attendance' => 'nullable|array',
            'status' => 'sometimes|required|in:scheduled,held,cancelled,rescheduled',
        ]);

        $meeting->update($validated);

        return response()->json($meeting);
    }

    public function addAction(Request $request, int $id)
    {
        $meeting = Meeting::where('masjid_id', $this->currentMasjidId($request))->findOrFail($id);

        $validated = $request->validate([
            'action_description' => 'required|string',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date',
            'priority' => 'nullable|in:low,medium,high,urgent',
        ]);

        $action = $meeting->actionItems()->create(array_merge($validated, [
            'status' => 'pending',
        ]));

        return response()->json($action, 201);
    }

    public function completeAction(Request $request, int $id)
    {
        $action = MeetingAction::whereHas(
            'meeting',
            fn ($q) => $q->where('masjid_id', $this->currentMasjidId($request))
        )->findOrFail($id);

        $action->update([
            'status' => 'completed',
            'completed_at' => now(),
            'remarks' => $request->input('remarks', $action->remarks),
        ]);

        return response()->json($action);
    }

    protected function generateMeetingId(): string
    {
        return 'MTG-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}

<?php

namespace App\Http\Controllers\API\V1\Notification;

use App\Http\Controllers\API\V1\AdminApiController;
use App\Models\NotificationQueue;
use App\Models\NotificationType;
use Illuminate\Http\Request;

/**
 * Backs the /api/v1/notifications/* routes defined in routes/api.php. Only
 * the route list existed in the source dump; implementation is new, built
 * against notification_types / notification_queues
 * (database/schema/10_notifications.sql). Actual delivery (email/SMS/push)
 * is out of scope here — same as the source dump's own MemberDonationController,
 * which left sendSMS() as a no-op — send() only writes queue rows with
 * status 'pending' for a real dispatcher to pick up.
 */
class NotificationController extends AdminApiController
{
    public function index(Request $request)
    {
        $query = NotificationQueue::where('masjid_id', $this->currentMasjidId($request))
            ->with('type')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('channel'), fn ($q) => $q->where('channel', $request->input('channel')))
            ->when($request->filled('recipient_type'), fn ($q) => $q->where('recipient_type', $request->input('recipient_type')))
            ->orderBy('created_at', 'desc');

        return response()->json($this->paginate($query, $request));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'notification_type_id' => 'required|exists:notification_types,id',
            'recipient_type' => 'required|in:member,staff,resident,donor,student,parent,all',
            'recipient_id' => 'nullable|integer',
            'channel' => 'required|in:email,sms,whatsapp,push,in_app',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'data' => 'nullable|array',
        ]);

        $notification = NotificationQueue::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'status' => 'pending',
            'retry_count' => 0,
            'created_by' => $request->user()->id,
        ]));

        return response()->json($notification, 201);
    }

    public function getTemplates(Request $request)
    {
        $templates = NotificationType::where('masjid_id', $this->currentMasjidId($request))
            ->whereNotNull('default_template')
            ->orderBy('name')
            ->get();

        return response()->json($templates);
    }

    public function createTemplate(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
            'description' => 'nullable|string',
            'default_template' => 'required|string',
            'channels' => 'nullable|array',
        ]);

        $type = NotificationType::create(array_merge($validated, [
            'company_id' => $this->currentCompanyId($request),
            'masjid_id' => $this->currentMasjidId($request),
            'is_active' => true,
        ]));

        return response()->json($type, 201);
    }

    public function getTypes(Request $request)
    {
        $types = NotificationType::where('masjid_id', $this->currentMasjidId($request))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($types);
    }
}

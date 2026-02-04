<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Service\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);
        $isRead  = $request->filled('is_read') ? $request->boolean('is_read') : null;

        $notifications = $this->service->paginateFor(
            $request->user(),
            $perPage,
            $isRead
        );

        return $this->complet(NotificationResource::collection($notifications));
    }

    public function summary(Request $request)
    {
        return response()->json(
            $this->service->summaryFor($request->user())
        );
    }

    public function markAllRead(Request $request)
    {
        $this->service->markAllRead($request->user());

        return response()->json([
            'message' => 'All notifications marked as read'
        ]);
    }

    public function markRead(Request $request, string $id)
    {
        $notification = $this->service->markOneRead($request->user(), $id);

        return $this->complet(new NotificationResource($notification));
    }
}

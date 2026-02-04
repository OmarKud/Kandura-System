<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Service\NotificationService;
use App\Service\NotificationActionResolver;
use Illuminate\Http\Request;

class NotificationWebController extends Controller
{
    public function __construct(
        protected NotificationService $service,
        protected NotificationActionResolver $resolver
    ) {}


    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 20);

        $filter = $request->get('filter', 'all'); // all|unread|read
        $isRead = null;
        if ($filter === 'unread') $isRead = false;
        if ($filter === 'read')   $isRead = true;

        $user = $request->user();

        $notifications = $this->service->paginateFor($user, $perPage, $isRead);
        $summary = $this->service->summaryFor($user);

        return view('notifications.index', compact('notifications', 'summary', 'filter'));
    }

    public function markAllRead(Request $request)
    {
        $this->service->markAllRead($request->user());
        return back()->with('status', 'All notifications marked as read.');
    }

    public function markRead(Request $request, string $id)
    {
        $this->service->markOneRead($request->user(), $id);
        return back();
    }

   public function open(Request $request, string $id)
{
    $notification = $this->service->markOneRead($request->user(), $id);

    $action = $notification->data['action'] ?? null;
    $url = $this->resolver->resolve($action);

    if (!$url) {
        return redirect()
            ->route('dashboard.notifications.index')
            ->with('status', 'Notification action is not configured.');
    }

    return redirect($url);
}

}

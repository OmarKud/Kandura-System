<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class NotificationService
{
    public function paginateFor(User $user, int $perPage = 20, ?bool $isRead = null): LengthAwarePaginator
    {
        $q = $user->notifications()->latest();

        if (!is_null($isRead)) {
            $isRead ? $q->whereNotNull('read_at') : $q->whereNull('read_at');
        }

        return $q->paginate($perPage);
    }

    public function summaryFor(User $user): array
    {
        return [
            'unread' => $user->unreadNotifications()->count(),
            'read'   => $user->readNotifications()->count(),
            'total'  => $user->notifications()->count(),
        ];
    }

    public function markAllRead(User $user): int
    {
        return $user->unreadNotifications()->update(['read_at' => now()]);
    }

    public function markOneRead(User $user, string $notificationId): \Illuminate\Notifications\DatabaseNotification
    {
        $notification = $user->notifications()->where('id', $notificationId)->firstOrFail();

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return $notification;
    }
}

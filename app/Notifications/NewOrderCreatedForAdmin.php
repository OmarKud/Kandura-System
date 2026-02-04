<?php


namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewOrderCreatedForAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Order $order)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'order created',
            'title' => 'New Order Created',
            'body' => 'A new order has been placed. Tap to view the order list.',
            'action' => [
                'type' => 'go_to_order_list',
                'params' => []
            ],
        ];
    }
}

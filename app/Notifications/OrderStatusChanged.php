<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        protected Order $order,
        protected string $newStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => 'order status changed',
            'title' => 'Order Status Updated',
            'body'  => "The status of your order #{$this->order->id} has been updated to {$this->newStatus}. Tap to view details.",
        
        ];
    }
}

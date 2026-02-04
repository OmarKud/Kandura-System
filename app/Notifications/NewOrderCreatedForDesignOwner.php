<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class NewOrderCreatedForDesignOwner extends Notification
{
    use Queueable;

    public function __construct(
        protected Order $order,
        protected Collection $designOrdersForOwner // Collection<DesignOrder>
    ) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        $designIds = $this->designOrdersForOwner->pluck('design_id')->unique()->values();

        return [
            'type' => 'order created',
            'title' => 'New Order Created',
            'body'  => 'A new order has been placed for your design. Tap to view the order details.',
          
        ];
    }
}

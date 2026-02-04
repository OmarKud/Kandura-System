<?php

namespace App\Listeners;

use App\Events\OrderDesignsAttached;
use App\Notifications\NewOrderCreatedForDesignOwner;

class NotifyDesignOwnersOfNewOrder
{
    public function handle(OrderDesignsAttached $event): void
    {
        $order = $event->order->load('designOrders.design.user');

        $groups = $order->designOrders
            ->filter(fn($do) => $do->design && $do->design->user)
            ->groupBy(fn($do) => $do->design->user_id);

        foreach ($groups as $ownerId => $designOrders) {
            $owner = $designOrders->first()->design->user;

            $owner->notify(new NewOrderCreatedForDesignOwner($order, $designOrders));
        }
    }
}

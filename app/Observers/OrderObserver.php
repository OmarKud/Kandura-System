<?php


namespace App\Observers;

use App\Models\Order;
use App\service\Invoice\InvoiceService;
class OrderObserver
{
    public bool $afterCommit = true;

    public function updated(Order $order): void
    {
        if (!$order->wasChanged('status')) {
            return;
        }

        if (strtolower((string) $order->status) !== 'completed') {
            return;
        }

        app(InvoiceService::class)->ensureInvoiceForCompletedOrder($order);
    }
}

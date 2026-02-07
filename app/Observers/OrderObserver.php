<?php


namespace App\Observers;

use App\Models\FcmToken;
use App\Models\Order;
use App\Models\User;
use App\Notifications\NewOrderCreatedForAdmin;
use App\Notifications\OrderStatusChanged;
use App\service\FcmV1Service;
use App\service\Invoice\InvoiceService;
use App\service\WalletTransactionService;
use Illuminate\Support\Facades\Notification;


class OrderObserver
{
    public bool $afterCommit = true;


     public function created(Order $order): void
    {
        $admins = User::permission('admin.order.manage')->get();
$adminIds = User::permission('admin.order.manage')->pluck('id')->all(); //

        if ($admins->isEmpty()) {
            return;
        }
         $tokens = FcmToken::whereIn('user_id', $adminIds)->pluck('token')->toArray();
        if (!$tokens) return;

        app(FcmV1Service::class)->sendToTokens(
            $tokens,
            "New Order 🧾",
            "New Order created",
            ['type' => 'order', 'order_id' => $order->id]
        );
    


        Notification::send($admins, new NewOrderCreatedForAdmin($order));
         if (strtolower((string) $order->status) !== 'completed') {
            return;
        }
         if (strtolower((string) $order->payment_status) !== 'paid') {
            return;
        }

        app(InvoiceService::class)->ensureInvoiceForCompletedOrder($order);
        app(WalletTransactionService::class)->recordOrderPayment($order);
    }
    public function updated(Order $order): void
    {
        if (!$order->wasChanged('status')) {
            return;
        }
          $user = $order->user; // 
        if (!$user) {
            return;
        }
         $user->notify(new OrderStatusChanged($order, $order->status));

        if (strtolower((string) $order->status) !== 'completed') {
            return;
        }
         if (strtolower((string) $order->payment_status) !== 'paid') {
            return;
        }

        app(InvoiceService::class)->ensureInvoiceForCompletedOrder($order);
       app(WalletTransactionService::class)->recordOrderPayment($order);

    }
}

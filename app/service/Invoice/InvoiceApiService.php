<?php

namespace App\service\Invoice;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class InvoiceApiService
{
   public function listMyInvoices(): Collection
{
 return Invoice::query()
     ->whereHas('order', fn($q) => $q->where('user_id', auth()->id()))
  ->with([
  'order:id,user_id,address_id,price,final_price,payment_method,notes,status,created_at',
  'order.user:id,name',
  'order.address:id,city,street,build',
  'order.designOrders:id,order_id,design_id,measurement_id',
  'order.designOrders.design:id,name,price',
  'order.designOrders.measurement:id,size',
  'order.designOrders.options:id,name,type',
])

    ->latest()
    ->get();

}


    public function getInvoiceByOrderForMe(Order $order): Invoice
    {
        if ((int)$order->user_id !== (int)Auth::id()) {
            abort(403);
        }

        $order->loadMissing([
            'invoice',
            'user:id,name,email,phone',
        ]);

        $invoice = $order->invoice;
        if (!$invoice) {
            abort(404, 'Invoice not found for this order');
        }

    $invoices = Invoice::query()
  ->whereHas('order', fn($q) => $q->where('user_id', auth()->id()))
  ->with([
  'order:id,user_id,address_id,price,final_price,payment_method,notes,status,created_at',
  'order.user:id,name',
  'order.address:id,city,street,build',
  'order.designOrders:id,order_id,design_id,measurement_id',
  'order.designOrders.design:id,name,price',
  'order.designOrders.measurement:id,size',
  'order.designOrders.options:id,name,type',
])

  ->latest()
  ->paginate(15);



        return $invoice;
    }
}

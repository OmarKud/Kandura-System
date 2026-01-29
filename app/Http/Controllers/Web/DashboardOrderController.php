<?php

namespace App\Http\Controllers\Web;

use App\Enum\StatusEnumOrder;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardOrderController extends Controller
{
    public array $statuses = [
    \App\Enum\StatusEnumOrder::COMPLETED,
    StatusEnumOrder::CANCELLED,
    \App\Enum\StatusEnumOrder::PENDING,
    \App\Enum\StatusEnumOrder::PROCESSING,
    \App\Enum\StatusEnumOrder::BLOCKED,
];

   
    public function index(Request $request)
    {
    
        $q = Order::query()
            ->with([
                'user',
                'coupon',
                'address',
                'designOrders.design',
                'designOrders.options',
            ]);

        // 🔍 search (id / user name / email)
        if ($search = trim((string) $request->input('search', ''))) {
            $q->where(function ($x) use ($search) {
                $x->where('id', $search)
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 🎯 filters
        if ($status = $request->input('status')) {
            $q->where('status', $status);
        }
        if ($paymentStatus = $request->input('payment_status')) {
            $q->where('payment_status', $paymentStatus);
        }
        if ($method = $request->input('payment_method')) {
            $q->where('payment_method', $method);
        }
        if (($min = $request->input('min_price')) !== null && $min !== '') {
            $q->where('price', '>=', (float) $min);
        }
        if (($max = $request->input('max_price')) !== null && $max !== '') {
            $q->where('price', '<=', (float) $max);
        }

        // 🔃 sort
        $allowedSort = ['id', 'price', 'created_at', 'updated_at', 'status', 'payment_status', 'payment_method'];
        $sortBy  = $request->input('sort_by', 'id');
        $sortDir = strtolower($request->input('sort_dir', 'desc'));

        if (!in_array($sortBy, $allowedSort, true)) $sortBy = 'id';
        if (!in_array($sortDir, ['asc','desc'], true)) $sortDir = 'desc';

        $q->orderBy($sortBy, $sortDir);

        // per page
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) $perPage = 10;
        if ($perPage > 50) $perPage = 50;

        $orders = $q->paginate($perPage)->withQueryString();

  $statuses = [
    StatusEnumOrder::COMPLETED,
    StatusEnumOrder::CANCELLED,
    StatusEnumOrder::PENDING,
    StatusEnumOrder::PROCESSING,
    StatusEnumOrder::BLOCKED,
];

       $paymentStatuses = ['paid','inpaid'];
        $paymentMethods = ['stripe','wallet','delivery'];

        return view('dashboard.orders.index', compact('orders', 'statuses', 'paymentStatuses', 'paymentMethods'));
    }

public function show(Order $order)
{
    $order->load([
        'user',
        'coupon',
        'address',
        'designOrders.design.images',
        'designOrders.options',
        'designOrders.measurement', // 
    ]);
  $statuses = [
    StatusEnumOrder::COMPLETED,
    StatusEnumOrder::CANCELLED,
    StatusEnumOrder::PENDING,
    StatusEnumOrder::PROCESSING,
    StatusEnumOrder::BLOCKED,
];
   $paymentStatuses = ['paid','inpaid'];

    return view('dashboard.orders.show', compact('order', 'statuses', 'paymentStatuses'));
}


   public function updateStatus(Request $request, Order $order)
{
    $paymentStatuses = ['paid','inpaid'];
      $statuses = [
    StatusEnumOrder::COMPLETED,
    StatusEnumOrder::CANCELLED,
    StatusEnumOrder::PENDING,
    StatusEnumOrder::PROCESSING,
    StatusEnumOrder::BLOCKED,
];

    $data = $request->validate([
        'status' => ['required', Rule::in($statuses)],
        'payment_status' => ['required', Rule::in($paymentStatuses)],
    ]);

   DB::transaction(function () use ($order, $data) {

    $order = Order::whereKey($order->id)->lockForUpdate()->first();
    $oldCouponId = $order->coupon_id;

    $order->status = $data['status'];
    $order->payment_status = $data['payment_status'];

    if ($order->status === 'blocked' && $oldCouponId) {

        $coupon = Coupon::whereKey($oldCouponId)->lockForUpdate()->first();
        if ($coupon && $coupon->used_count > 0) {
            $coupon->used_count -= 1;
            $coupon->save();
        }

        $order->coupon_id = null; //    
    }

    $order->save();
});


    return back()->with('success', 'Order updated successfully.');
}

}

<?php

namespace App\service\User;

use App\Events\OrderDesignsAttached;
use App\Models\Coupon;
use App\Models\Design;
use App\Models\DesignOptionSelection;
use App\Models\DesignOrder;
use App\Models\Order;
use App\Models\Wallet;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct()
    {
    }

    public function create(array $data)
    {
        $userId = Auth::id();
        $items = $data['items'] ?? [];

        $designIds = collect($items)->pluck('design_id')->unique()->values();
        $designs = Design::whereIn('id', $designIds)->get()->keyBy('id');

        $total = 0;
        foreach ($items as $item) {
            $design = $designs[$item['design_id']] ?? null;
            if ($design) {
                $total += (float) ($design->price ?? 0);
            }
        }

        $couponCode = $data['coupon_code'] ?? null;
        $couponId = null;

        $originalTotal = $total; // القديم
        $finalTotal = $total;    // النهائي
        $discountAmount = 0;

        if (!empty($couponCode)) {

            $coupon = Coupon::where('code', $couponCode)->first();

            if (!$coupon || !$coupon->is_active) {
                throw new Exception("Coupon invalid");
            }

            if (now()->toDateString() > $coupon->expiry_date->toDateString()) {
                throw new Exception("Coupon expired");
            }

            if ($coupon->discount_type === 'fixed' && (float) $coupon->discount_value > (float) $originalTotal) {
                throw new Exception("Coupon value is greater than order total");
            }

            $usedBefore = Order::where('user_id', $userId)
                ->where('coupon_id', $coupon->id)
                ->exists();

            if ($usedBefore) {
                throw new Exception("You already used this coupon before");
            }

            if ($coupon->used_count >= $coupon->usage_limit) {
                throw new Exception("Coupon usage limit reached");
            }

            if ($coupon->discount_type === 'percent') {
                $discountAmount = round($originalTotal * ((float) $coupon->discount_value / 100), 2);
            } else {
                $discountAmount = (float) $coupon->discount_value;
            }

            $discountAmount = min($discountAmount, $originalTotal);
            $finalTotal = $originalTotal - $discountAmount;

            $coupon->used_count += 1;
            $coupon->save();

            $couponId = $coupon->id;
        }

        $paymentMethod = $data["payment_method"];

        if ($paymentMethod == "wallet") {
            $wallet = Wallet::where("user_id", $userId)->first();
            if (!$wallet) {
                throw new Exception("create wallet before");
            }

            if ($wallet->amount < $finalTotal) {
                throw new Exception("please charge your wallet and try again");
            }

            $wallet->amount -= $finalTotal;
            $wallet->save();

            $orderstatus = "completed";
            $paymentstatus = "paid";
        } elseif ($paymentMethod == "delivery") {
            $orderstatus = "pending";
            $paymentstatus = "inpaid";
        } elseif ($paymentMethod == "stripe") {
            $orderstatus = "pending";
            $paymentstatus = "inpaid";
        } else {
            throw new Exception("invalid payment method");
        }

        $order = Order::create([
            'user_id' => $userId,
            'address_id' => $data['address_id'],
            'payment_method' => $paymentMethod,
            'notes' => $data['notes'] ?? null,

            'price' => $originalTotal,
            'final_price' => ($finalTotal < $originalTotal) ? $finalTotal : $originalTotal,
            'coupon_id' => $couponId,
            "discount_amount" => $discountAmount,

            'status' => $orderstatus,
            'payment_status' => $paymentstatus,
        ]);
$allowed = Design::where('id', $item['design_id'])
    ->whereHas('measurements', fn($q) => $q->where('measurements.id', $item['measurement_id']))
    ->exists();

if (!$allowed) {
    throw new Exception("هذا القياس غير متاح لهذا التصميم");
}
        foreach ($items as $item) {
            $designOrder = DesignOrder::create([
                'order_id' => $order->id,
                'design_id' => $item['design_id'],
                'measurement_id' => $item['measurement_id'],
            ]);

            $optionIds = $item['design_option_ids'] ?? [];
            if (!empty($optionIds)) {
                $designOrder->options()->sync($optionIds);
            }
        }
        
event(new OrderDesignsAttached($order));

        if ($paymentMethod == "stripe") {
            $session = app(StripePaymentService::class)->createCheckoutSession($order);

            $order->stripe_session_id = $session->id;
            $order->save();

            $order->setAttribute('checkout_url', $session->url);
        }

        return $order->load("user", "address", 'designOrders.design', 'designOrders.options', "designOrders.measurement", );
    }


    public function CancelOrder(array $request,Order $order){
       if ($order->user_id !== Auth::id()) {
        throw new Exception("Not your order");
    }

    if ($order->status !== 'pending') {
        throw new Exception("Only pending orders can be cancelled");
    }
     $order->update($request);
     DB::transaction(function () use ($order, $request) {

    $order = Order::whereKey($order->id)->lockForUpdate()->first();
    $oldCouponId = $order->coupon_id;

    $order->status = $request['status'];

    if ($order->status === 'cancelled' && $oldCouponId) {

        $coupon = Coupon::whereKey($oldCouponId)->lockForUpdate()->first();
        if ($coupon && $coupon->used_count > 0) {
            $coupon->used_count -= 1;
            $coupon->save();
        }

        $order->coupon_id = null; //    
    }

    $order->save();
});
    return $order;


    }
}

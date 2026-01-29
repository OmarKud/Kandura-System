<?php

namespace App\Service\User;

use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripePaymentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    public function createCheckoutSession($order): Session
    {
        $key = config('services.stripe.secret');

        if (!$key) {
            throw new \Exception("Stripe secret key is missing. Check .env STRIPE_SECRET and config/services.php");
        }
        Stripe::setApiKey(config('services.stripe.secret'));

        return Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [
                    [
                        'quantity' => 1,
                        'price_data' => [
                            'currency' => 'usd', // 
                            'unit_amount' => (int) round($order->final_price * 100),
                            'product_data' => [
                                'name' => "Order #{$order->id}",
                            ],
                        ],
                    ]
                ],
            'metadata' => [
                'order_id' => (string) $order->id,
            ],
            'success_url' => config('app.url') . '/stripe/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => config('app.url') . '/stripe/cancel?session_id={CHECKOUT_SESSION_ID}',

        ]);
    }
}
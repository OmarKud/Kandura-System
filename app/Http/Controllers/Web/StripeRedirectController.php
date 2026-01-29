<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class StripeRedirectController extends Controller
{
public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        $order = null;
        if ($sessionId) {
            $order = Order::where('stripe_session_id', $sessionId)->first();
        }

       
        return view('stripe.success', [
            'order' => $order,
            'sessionId' => $sessionId,
        ]);
    }

    public function cancel(Request $request)
    {
        $sessionId = $request->query('session_id');

        $order = null;
        if ($sessionId) {
            $order = Order::where('stripe_session_id', $sessionId)->first();
        }

        return view('stripe.cancel', [
            'order' => $order,
            'sessionId' => $sessionId,
        ]);
    }
}

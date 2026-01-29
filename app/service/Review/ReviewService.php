<?php

namespace App\service\Review;

use App\Models\Order;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    public function createMyReview(array $data): Review
    {
        $userId = (int) auth()->id();

        return DB::transaction(function () use ($data, $userId) {

            $order = Order::whereKey((int)$data['order_id'])->lockForUpdate()->firstOrFail();

            if ((int)$order->user_id !== $userId) {
                abort(403, 'You are not allowed to review this order.');
            }

            if (strtolower((string)$order->status) !== 'completed') {
                abort(422, 'You can review only completed orders.');
            }

            $existing = Review::where('order_id', $order->id)->first();
            if ($existing) {
                abort(409, 'Review already exists for this order.');
            }

            return Review::create([
                'user_id'  => $userId,
                'order_id' => $order->id,
                'rating'   => (int) $data['rating'],
                'comment'  => $data['comment'] ?? null,
            ]);
        });
    }

    public function getMyReviewForOrder(Order $order): ?Review
    {
        $userId = (int) auth()->id();

        if ((int)$order->user_id !== $userId) {
            abort(403, 'You are not allowed to view this review.');
        }

        return Review::query()
            ->where('order_id', $order->id)
            ->where('user_id', $userId)
            ->first();
    }
}

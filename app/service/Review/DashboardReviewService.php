<?php

namespace App\service\Review;

use App\Models\Review;
use Illuminate\Http\Request;

class DashboardReviewService
{
    public function list(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        $rating = $request->get('rating'); // 1..5 أو null
        $perPage = (int)$request->get('per_page', 15);
        $perPage = max(5, min(100, $perPage));

        return Review::query()
            ->with([
                'user:id,name,email,phone',
            ])
            ->select(['id','user_id','order_id','rating','comment','created_at']) // ids موجودة فقط للاستعلام، ما راح نعرضها
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('user', function ($uq) use ($q) {
                    $uq->where('name', 'like', "%{$q}%")
                       ->orWhere('email', 'like', "%{$q}%")
                       ->orWhere('phone', 'like', "%{$q}%");
                })->orWhere('comment', 'like', "%{$q}%");
            })
            ->when(in_array((string)$rating, ['1','2','3','4','5'], true), function ($query) use ($rating) {
                $query->where('rating', (int)$rating);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}

<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Order;
use App\service\Review\ReviewService;
use Exception;

class ReviewController extends Controller
{
    public function __construct(private ReviewService $service) {}

    public function store(StoreReviewRequest $request)
    {
        $review = $this->service->createMyReview($request->validated());

        return $this->complet($review);

        
    }

    public function myByOrder(Order $order)
    {
        $review = $this->service->getMyReviewForOrder($order);
       
 if ($review==null){
            throw new Exception("no review founded");

        }
        return $this->complet($review);

    }
}

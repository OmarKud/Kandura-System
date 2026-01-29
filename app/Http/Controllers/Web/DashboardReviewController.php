<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\service\Review\DashboardReviewService;
use Illuminate\Http\Request;

class DashboardReviewController extends Controller
{
    public function __construct(private DashboardReviewService $service) {}

    private function assertAdmin(): void
    {
        $u = auth()->user();
        if (!$u || !in_array((int)$u->role_id, [3, 4], true)) {
            abort(403);
        }
    }

    public function index(Request $request)
    {
        $this->assertAdmin();

        $reviews = $this->service->list($request);

        $q = trim((string)$request->get('q', ''));
        $rating = $request->get('rating', '');
        $perPage = (int)$request->get('per_page', 15);

        return view('dashboard.reviews.index', compact('reviews', 'q', 'rating', 'perPage'));
    }
}

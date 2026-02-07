<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardTransactionController extends Controller
{
    public function index(Request $request)
    {
        $q = Transaction::query()->with(['user', 'order', 'admin']);

        if ($search = trim((string) $request->input('search', ''))) {
            $q->where(function ($qq) use ($search) {
                $qq->where('reference', 'like', "%{$search}%")

                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    })

                    ->orWhereHas('admin', function ($a) use ($search) {
                        $a->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    })

                    ->orWhereHas('order', function ($o) use ($search) {
                        $o->where('id', $search);
                    });
            });
        }

        // filter by type
        if ($type = $request->input('type')) {
            $q->where('type', $type);
        }

        $sortBy  = $request->input('sort_by', 'created_at');
        $sortDir = strtolower($request->input('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowedSort = ['id', 'amount', 'type', 'created_at'];

        if (!in_array($sortBy, $allowedSort, true)) $sortBy = 'created_at';
        $q->orderBy($sortBy, $sortDir);

        $perPage = (int) $request->input('per_page', 15);
        if ($perPage <= 0) $perPage = 15;
        if ($perPage > 100) $perPage = 100;

        $transactions = $q->paginate($perPage)->withQueryString();

        return view('dashboard.transactions.index', compact('transactions'));
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use App\service\WalletTransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardWalletController extends Controller
{
    public function index(Request $request)
{
    $q = \App\Models\Wallet::query()
        ->with('user');

    // 🔍 search by user name/email
    if ($search = trim((string) $request->input('search', ''))) {
        $q->whereHas('user', function ($u) use ($search) {
            $u->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // 🎯 filter by amount
    if (($min = $request->input('min_amount')) !== null && $min !== '') {
        $q->where('amount', '>=', (float) $min);
    }
    if (($max = $request->input('max_amount')) !== null && $max !== '') {
        $q->where('amount', '<=', (float) $max);
    }

    // 🔃 sort
    $allowedSort = ['id', 'amount', 'created_at', 'updated_at'];
    $sortBy  = $request->input('sort_by', 'id');
    $sortDir = strtolower($request->input('sort_dir', 'desc'));

    if (!in_array($sortBy, $allowedSort, true)) $sortBy = 'id';
    if (!in_array($sortDir, ['asc','desc'], true)) $sortDir = 'desc';

    $q->orderBy($sortBy, $sortDir);

    // 📄 per page
    $perPage = (int) $request->input('per_page', 10);
    if ($perPage <= 0) $perPage = 10;
    if ($perPage > 50) $perPage = 50;

    $wallets = $q->paginate($perPage)->withQueryString();

    return view('dashboard.wallets.index', compact('wallets'));
}
public function show(User $user)
    {
        $user->load('wallet');
        $wallet = $user->wallet;

        return view('dashboard.wallets.show', compact('user', 'wallet'));
    }


    public function charge(Request $request, User $user)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $chargeAmount = (float) $data['amount'];

        DB::transaction(function () use ($user, $chargeAmount) {
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['amount' => 0]
            );

            $wallet = Wallet::where('id', $wallet->id)->lockForUpdate()->first();
            $wallet->amount = (float) $wallet->amount + $chargeAmount;
            $wallet->save();
            
         app(WalletTransactionService::class)->recordDeposit($user, $chargeAmount, auth()->user());
    });

        return back()->with('success', "Wallet charged successfully (+{$chargeAmount}).");
    }
}

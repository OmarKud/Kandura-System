<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');

        $coupons = Coupon::query()
            ->when($q, fn($query) => $query->where('code', 'like', "%{$q}%"))
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.coupons.index', compact('coupons', 'q'));
    }

    public function create()
    {
        return view('dashboard.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'discount_type' => ['required', 'in:percent,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'usage_limit' => ['required', 'integer', 'min:1'],
            'expiry_date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        // optional: percent max 100
        if ($validated['discount_type'] === 'percent' && (float)$validated['discount_value'] > 100) {
            return back()->withErrors(['discount_value' => 'Percent discount can not be more than 100'])->withInput();
        }

        // set defaults if not in db defaults
        $validated['used_count'] = 0;

        Coupon::create($validated);

        return redirect()->route('dashboard.coupons.index')->with('success', 'Coupon created successfully');
    }

    public function edit(Coupon $coupon)
    {
        return view('dashboard.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'is_active' => ['required', 'in:0,1'],
        ]);

        $coupon->update($validated);

        return redirect()->route('dashboard.coupons.index')->with('success', 'Coupon updated successfully');
    }
}

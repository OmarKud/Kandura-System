@extends('layouts.dashboard')

@section('title','Create Coupon')

@section('content')
<style>
    .wrap{max-width:820px;margin:0 auto;}
    .head{display:flex;justify-content:space-between;gap:10px;align-items:flex-start;flex-wrap:wrap;margin-bottom:14px;}
    .head h1{margin:0;font-size:20px;font-weight:950;}
    .head p{margin:6px 0 0;color:var(--muted);font-weight:700;font-size:13px;}
    .back{border:1px solid var(--border);background:#fff;border-radius:14px;padding:10px 12px;font-weight:900;text-decoration:none;color:var(--text);}
    .back:hover{background: rgba(15,23,42,.03);}

    .card{background:#fff;border:1px solid var(--border);border-radius: var(--radius);padding:16px;box-shadow: var(--shadow2);}
    .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;}
    @media(max-width:720px){.grid{grid-template-columns:1fr;}}
    label{font-size:12px;color:var(--muted);font-weight:900;display:block;margin-bottom:6px;}
    .inp,.sel{width:100%;padding:11px 12px;border-radius:14px;border:1px solid var(--border);font-weight:850;font-size:13px;outline:none;}
    .err{margin-top:6px;color:#b91c1c;font-weight:900;font-size:12px;}

    .btn-primary{
        background: var(--primary);
        color:#fff;
        border:1px solid var(--primary-border);
        border-radius:999px;
        padding:11px 15px;
        font-weight:950;
        cursor:pointer;
        font-size:13px;
    }
    .btn-primary:hover{background: var(--primary-600);}
</style>

<div class="wrap">
    <div class="head">
        <div>
            <h1>Create Coupon</h1>
            <p>Unique code + discount + limit + expiry date.</p>
        </div>
        <a class="back" href="{{ route('dashboard.coupons.index') }}">← Back</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('dashboard.coupons.store') }}">
            @csrf

            <div class="grid">
                <div>
                    <label>Code (Unique)</label>
                    <input class="inp" name="code" value="{{ old('code') }}" placeholder="NEWYEAR10">
                    @error('code') <div class="err">{{ $message }}</div> @enderror
                </div>

              

                <div>
                    <label>Discount Type</label>
                    <select class="sel" name="discount_type">
                        <option value="percent" {{ old('discount_type','percent')=='percent' ? 'selected' : '' }}>percent</option>
                        <option value="fixed" {{ old('discount_type')=='fixed' ? 'selected' : '' }}>fixed</option>
                    </select>
                    @error('discount_type') <div class="err">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label>Discount Value</label>
                    <input class="inp" type="number" step="0.01" name="discount_value" value="{{ old('discount_value') }}" placeholder="10">
                    @error('discount_value') <div class="err">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label>Usage Limit</label>
                    <input class="inp" type="number" name="usage_limit" value="{{ old('usage_limit', 10) }}" placeholder="10">
                    @error('usage_limit') <div class="err">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label>Expiry Date</label>
                    <input class="inp" type="date" name="expiry_date" value="{{ old('expiry_date') }}">
                    @error('expiry_date') <div class="err">{{ $message }}</div> @enderror
                </div>

                <div style="display:flex;align-items:end;">
                    <button class="btn-primary" type="submit">Create</button>
                </div>
            </div>

            <div style="margin-top:12px;color:var(--muted);font-weight:800;font-size:12.5px;">
                * If discount_type is percent, value should be <= 100.
            </div>
        </form>
    </div>
</div>
@endsection

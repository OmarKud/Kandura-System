@extends('layouts.dashboard')

@section('title','Edit Coupon')

@section('content')
<style>
    .wrap{max-width:720px;margin:0 auto;}
    .head{display:flex;justify-content:space-between;gap:10px;align-items:flex-start;flex-wrap:wrap;margin-bottom:14px;}
    .head h1{margin:0;font-size:20px;font-weight:950;}
    .head p{margin:6px 0 0;color:var(--muted);font-weight:700;font-size:13px;}
    .back{border:1px solid var(--border);background:#fff;border-radius:14px;padding:10px 12px;font-weight:900;text-decoration:none;color:var(--text);}
    .back:hover{background: rgba(15,23,42,.03);}

    .card{background:#fff;border:1px solid var(--border);border-radius: var(--radius);padding:16px;box-shadow: var(--shadow2);}
    label{font-size:12px;color:var(--muted);font-weight:900;display:block;margin-bottom:6px;}
    .sel{width:100%;padding:11px 12px;border-radius:14px;border:1px solid var(--border);font-weight:850;font-size:13px;outline:none;}
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
            <h1>Edit Coupon</h1>
            <p>Code: <b>{{ $coupon->code }}</b> • Expiry: <b>{{ $coupon->expiry_date?->format('Y-m-d') ?? '-' }}</b></p>
        </div>
        <a class="back" href="{{ route('dashboard.coupons.index') }}">← Back</a>
    </div>

    <div class="card">
        <form method="POST" action="{{ route('dashboard.coupons.update', $coupon->id) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom:12px;">
                <label>Is Active</label>
                <select class="sel" name="is_active">
                    <option value="1" {{ old('is_active', (string)$coupon->is_active) == '1' ? 'selected' : '' }}>(Active)</option>
                    <option value="0" {{ old('is_active', (string)$coupon->is_active) == '0' ? 'selected' : '' }}>(Inactive)</option>
                </select>
                @error('is_active') <div class="err">{{ $message }}</div> @enderror
            </div>

            <button class="btn-primary" type="submit">Save</button>
        </form>
    </div>
</div>
@endsection

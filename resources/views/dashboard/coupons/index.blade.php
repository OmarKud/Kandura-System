@extends('layouts.dashboard')

@section('title','Coupons')

@section('content')
<style>
    .head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:14px;}
    .head h1{margin:0;font-size:20px;font-weight:950;}
    .head p{margin:6px 0 0;color:var(--muted);font-weight:800;font-size:13px;}
    .actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center;}

    .btn-primary{
        background: var(--primary);
        color:#fff;
        border:1px solid var(--primary-border);
        border-radius:999px;
        padding:10px 14px;
        font-weight:950;
        cursor:pointer;
        font-size:13px;
        text-decoration:none;
    }
    .btn-primary:hover{background: var(--primary-600);}

    .btn{
        border-radius: 999px;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--text);
        font-size: 13px;
        padding: 10px 14px;
        cursor:pointer;
        font-weight:900;
        text-decoration:none;
    }
    .btn:hover{background: rgba(15,23,42,.03)}

    .filters{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:14px;}
    .inp{padding:10px 12px;border-radius:14px;border:1px solid var(--border);font-weight:850;font-size:13px;min-width:260px;}

    .grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;}
    @media(max-width:900px){.grid{grid-template-columns:1fr;}}

    .card{
        background:#fff;border:1px solid var(--border);
        border-radius: var(--radius);
        box-shadow: var(--shadow2);
        padding:14px;
        display:flex;
        flex-direction:column;
        gap:10px;
    }
    .top{display:flex;justify-content:space-between;align-items:flex-start;gap:10px;flex-wrap:wrap;}
    .code{font-weight:1000;font-size:16px;letter-spacing:.5px}
    .meta{color:var(--muted);font-weight:800;font-size:12.5px;}

    .chips{display:flex;gap:8px;flex-wrap:wrap;}
    .chip{
        display:inline-flex;align-items:center;gap:6px;
        padding:6px 10px;border-radius:999px;
        font-weight:950;font-size:12px;
        border:1px solid var(--border);
        background: rgba(15,23,42,.03);
    }
    .chip.blue{background: rgba(37,99,235,.10); border-color: rgba(37,99,235,.22); color:#2563eb;}
    .chip.gold{background: rgba(212,160,23,.14); border-color: rgba(212,160,23,.25); color:#9a6d00;}
    .chip.green{background: rgba(16,185,129,.12); border-color: rgba(16,185,129,.25); color:#047857;}
    .chip.red{background: rgba(185,28,28,.12); border-color: rgba(185,28,28,.25); color:#b91c1c;}
    .chip.gray{background: rgba(100,116,139,.12); border-color: rgba(100,116,139,.22); color:#334155;}

    .row{display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;}
    .small{font-size:12.5px;color:var(--muted);font-weight:800;}
    .right{display:flex;gap:8px;flex-wrap:wrap;align-items:center;}
</style>

<div class="head">
    <div>
        <h1>Coupons</h1>
        <p>Manage coupons + expiry date + active status.</p>
    </div>
    <div class="actions">
        <a class="btn-primary" href="{{ route('dashboard.coupons.create') }}">+ Create Coupon</a>
    </div>
</div>

<form class="filters" method="GET" action="{{ route('dashboard.coupons.index') }}">
    <input class="inp" name="q" value="{{ $q ?? '' }}" placeholder="Search by code...">
    <button class="btn" type="submit">Search</button>
    <a class="btn" href="{{ route('dashboard.coupons.index') }}">Reset</a>
</form>

@if(session('success'))
    <div style="margin-bottom:12px;padding:10px 12px;border-radius:14px;border:1px solid rgba(16,185,129,.25);background:rgba(16,185,129,.10);color:#047857;font-weight:900;">
        {{ session('success') }}
    </div>
@endif

<div class="grid">
    @forelse($coupons as $c)
        @php
            $expired = $c->expiry_date ? now()->toDateString() > $c->expiry_date->toDateString() : true;
            $remaining = max(0, (int)$c->usage_limit - (int)$c->used_count);

            $typeLabel = $c->discount_type === 'percent'
                ? ((float)$c->discount_value).'%'
                : ((float)$c->discount_value).' fixed';

            $active = (int)$c->is_active === 1;
        @endphp

        <div class="card">
            <div class="top">
                <div>
                    <div class="code">{{ $c->code }}</div>
                    <div class="meta">
                        Expiry: <b>{{ $c->expiry_date?->format('Y-m-d') ?? '-' }}</b>
                        • Used: <b>{{ $c->used_count }}</b> / <b>{{ $c->usage_limit }}</b>
                        • Remaining: <b>{{ $remaining }}</b>
                    </div>
                </div>

                <div class="chips">
                    <span class="chip gold">Discount: {{ $typeLabel }}</span>

                    <span class="chip {{ $active ? 'green' : 'gray' }}">
                        {{ $active ? 'Active' : 'Inactive' }}
                    </span>

                    <span class="chip {{ $expired ? 'red' : 'blue' }}">
                        {{ $expired ? 'Expired' : 'Valid' }}
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="small">
                    Created: <b>{{ $c->created_at?->format('Y-m-d') ?? '-' }}</b>
                </div>

                <div class="right">
                    <a class="btn" href="{{ route('dashboard.coupons.edit', $c->id) }}">Activate</a>
                </div>
            </div>
        </div>
    @empty
        <div style="padding:14px;border:1px dashed var(--border);border-radius:16px;color:var(--muted);font-weight:900;">
            No coupons found.
        </div>
    @endforelse
</div>

<div style="margin-top:14px;">
    {{ $coupons->links() }}
</div>
@endsection

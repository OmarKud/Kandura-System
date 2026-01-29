{{-- resources/views/dashboard/orders/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Orders')

@section('content')
<style>
  .filters{
    margin-bottom:12px;
    display:flex;gap:10px;flex-wrap:wrap;
    padding:14px;border-radius:18px;
    border:1px solid rgba(15,23,42,.10);
    background:rgba(255,255,255,.78);
    box-shadow: 0 12px 26px rgba(15,23,42,.06);
  }
  .inp,.sel{
    padding:12px 12px;border-radius:14px;border:1px solid rgba(15,23,42,.12);
    background:#fff;color:#0f172a;font-size:14px;outline:none;min-width:190px;
  }
  .btn-primary{
    border:none;border-radius:999px;padding:11px 14px;cursor:pointer;
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    color:#fff;font-weight:900;font-size:13.5px;
  }
  .btn-secondary{
    border:1px solid rgba(15,23,42,.12);
    border-radius:999px;
    padding:11px 14px;
    cursor:pointer;
    background:#fff;
    color:#0f172a;
    font-weight:900;
    text-decoration:none;
    display:inline-flex;align-items:center;justify-content:center;
    font-size:13.5px;
  }
  .cards{
    display:grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap:14px;
  }
  @media (max-width: 980px){ .cards{grid-template-columns: 1fr;} }

  .card{
    border:1px solid rgba(15,23,42,.10);
    border-radius:18px;
    background:#fff;
    box-shadow: 0 16px 44px rgba(15,23,42,.07);
    overflow:hidden;
  }
  .card-head{
    padding:14px 14px 12px;
    background:
      radial-gradient(900px 260px at 15% 0%, rgba(79,209,255,.18), transparent 60%),
      linear-gradient(180deg, #ffffff, rgba(37,99,235,.05));
    border-bottom:1px solid rgba(15,23,42,.06);
  }
  .title{margin:0;font-size:16px;font-weight:900;color:#0f172a;}
  .sub{margin-top:6px;color:#64748b;font-size:13px;font-weight:700;}
  .body{padding:12px 14px 14px;}
  .chip{
    display:inline-flex;align-items:center;gap:6px;
    padding:7px 10px;border-radius:999px;
    font-size:12px;font-weight:900;white-space:nowrap;
    border:1px solid rgba(15,23,42,.10);
    background: rgba(15,23,42,.05);
    color:#334155;
  }
  .chip.blue{background: rgba(59,130,246,.10);border-color: rgba(59,130,246,.18);color:#1d4ed8;}
  .chip.gold{background: rgba(212,160,23,.12);border-color: rgba(212,160,23,.20);color:#8a6a10;}
  .chip.ok{background: rgba(34,197,94,.10);border-color: rgba(34,197,94,.18);color:#166534;}
  .chip.bad{background: rgba(239,68,68,.10);border-color: rgba(239,68,68,.18);color:#991b1b;}

  .row{display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:center;margin-top:10px;}
  .box{
    margin-top:12px;
    padding:12px;
    border-radius:16px;
    border:1px dashed rgba(15,23,42,.14);
    background: rgba(15,23,42,.02);
  }

  .price-wrap{display:flex;gap:8px;flex-wrap:wrap;align-items:center;}
  .old-price{
    text-decoration: line-through;
    opacity:.75;
  }
</style>

<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:12px;">
  <div>
    <h2 style="margin:0;font-size:20px;font-weight:900;">Orders</h2>
    <div style="margin-top:4px;color:#64748b;font-size:13px;font-weight:700;">
      Browse all orders + update status/payment status
    </div>
  </div>
  <a class="btn-secondary" href="{{ route('dashboard.orders.index') }}">Reset</a>
</div>

@if(session('success'))
  <div style="margin-bottom:12px;padding:12px 14px;border-radius:16px;border:1px solid rgba(34,197,94,.25);background:rgba(34,197,94,.08);color:#166534;font-size:14px;font-weight:700;">
    {{ session('success') }}
  </div>
@endif

@if ($errors->any())
  <div style="margin-bottom:12px;padding:12px 14px;border-radius:16px;border:1px solid rgba(239,68,68,.25);background:rgba(239,68,68,.08);color:#991b1b;font-size:14px;font-weight:700;">
    {{ $errors->first() }}
  </div>
@endif

<form method="GET" action="{{ route('dashboard.orders.index') }}" class="filters">
  <input class="inp" type="text" name="search" placeholder="Search id / user..." value="{{ request('search') }}">

  <select class="sel" name="status">
    <option value="">All Status</option>
    @foreach($statuses as $st)
      <option value="{{ $st }}" {{ request('status')===$st?'selected':'' }}>{{ strtoupper($st) }}</option>
    @endforeach
  </select>

  <select class="sel" name="payment_status">
    <option value="">All Payment</option>
    @foreach($paymentStatuses as $ps)
      <option value="{{ $ps }}" {{ request('payment_status')===$ps?'selected':'' }}>{{ strtoupper($ps) }}</option>
    @endforeach
  </select>

  <select class="sel" name="payment_method">
    <option value="">All Methods</option>
    @foreach($paymentMethods as $pm)
      <option value="{{ $pm }}" {{ request('payment_method')===$pm?'selected':'' }}>{{ strtoupper($pm) }}</option>
    @endforeach
  </select>

  <input class="inp" type="number" step="0.01" name="min_price" placeholder="Min price" value="{{ request('min_price') }}">
  <input class="inp" type="number" step="0.01" name="max_price" placeholder="Max price" value="{{ request('max_price') }}">

  <select class="sel" name="per_page">
    @foreach([10,20,50] as $pp)
      <option value="{{ $pp }}" {{ (int)request('per_page',10)===$pp?'selected':'' }}>{{ $pp }}/page</option>
    @endforeach
  </select>

  <button class="btn-primary" type="submit">Apply</button>
</form>

<div class="cards">
  @forelse($orders as $o)
    @php
      $orig = (float) ($o->price ?? 0);
      $final = (float) ($o->final_price ?? $o->price ?? 0);
      $hasDiscount = ($o->final_price !== null) && ($final < $orig);
      $discountVal = max(0, $orig - $final);
      $payChip = ((string)$o->payment_status === 'paid') ? 'ok' : 'bad';
    @endphp

    <div class="card">
      <div class="card-head">
        <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:flex-start;">
          <div>
            <div class="title">Order #{{ $o->id }}</div>
            <div class="sub">👤 {{ $o->user->name ?? '—' }} — {{ $o->user->email ?? '' }}</div>
          </div>
          <span class="chip blue">{{ strtoupper($o->payment_method) }}</span>
        </div>

        <div class="row">
          <div class="price-wrap">
            @if($hasDiscount)
              <span class="chip gold old-price">💰 {{ number_format($orig, 2) }}</span>
              <span class="chip blue">✅ {{ number_format($final, 2) }}</span>
              <span class="chip">Discount: {{ number_format($discountVal, 2) }}</span>
            @else
              <span class="chip gold">💰 {{ number_format($orig, 2) }}</span>
            @endif
          </div>

          <span class="chip">📅 {{ optional($o->created_at)->format('Y-m-d') }}</span>
        </div>

        <div class="row" style="margin-top:8px;">
          <span class="chip">Status: {{ $o->status }}</span>
          <span class="chip {{ $payChip }}">Payment: {{ $o->payment_status }}</span>
        </div>
      </div>

      <div class="body">
        <div class="box">
          <div style="font-weight:900;margin-bottom:8px;">Status For Order & Payment</div>

          <form method="POST" action="{{ route('dashboard.orders.updateStatus', $o->id) }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
            @csrf
            @method('PUT')

            <select class="sel" name="status" style="min-width:180px;">
              @foreach($statuses as $st)
                <option value="{{ $st }}" {{ $o->status===$st?'selected':'' }}>{{ strtoupper($st) }}</option>
              @endforeach
            </select>

            <select class="sel" name="payment_status" style="min-width:180px;">
              @foreach($paymentStatuses as $ps)
                <option value="{{ $ps }}" {{ $o->payment_status===$ps?'selected':'' }}>{{ strtoupper($ps) }}</option>
              @endforeach
            </select>

            <button class="btn-primary" type="submit">Save</button>

            <a class="btn-secondary" href="{{ route('dashboard.orders.show', $o->id) }}">Details</a>
          </form>
        </div>

       @if(!empty($o->notes))
  <div class="box" style="margin-top:10px;border-style:solid;">
    <div style="font-weight:900;margin-bottom:6px;">📝 Notes</div>
    <div style="color:#0f172a;font-weight:800;line-height:1.6;">
      {{ \Illuminate\Support\Str::limit($o->notes, 160) }}
    </div>
  </div>
@endif

      </div>
    </div>
  @empty
    <div style="padding:16px;color:#64748b;font-weight:800;">No orders found.</div>
  @endforelse
</div>

<div style="margin-top:12px;">
  {{ $orders->links() }}
</div>
@endsection

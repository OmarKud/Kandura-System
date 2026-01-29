@extends('layouts.dashboard')

@section('title', 'Order Details')

@section('content')
<style>
  .grid{
    display:grid;
    grid-template-columns: 1.25fr .75fr;
    gap:14px;
  }
  @media (max-width: 980px){ .grid{grid-template-columns: 1fr;} }

  .panel{
    border:1px solid rgba(15,23,42,.10);
    border-radius:18px;
    background:#fff;
    box-shadow: 0 16px 44px rgba(15,23,42,.07);
    overflow:hidden;
  }

  .head{
    padding:14px 14px 12px;
    background:
      radial-gradient(900px 260px at 15% 0%, rgba(79,209,255,.18), transparent 60%),
      linear-gradient(180deg, #ffffff, rgba(37,99,235,.05));
    border-bottom:1px solid rgba(15,23,42,.06);
  }

  .title{margin:0;font-size:18px;font-weight:900;color:#0f172a;}
  .sub{margin-top:6px;color:#64748b;font-size:13px;font-weight:800;}

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

  .body{padding:14px;}
  .row{display:flex;flex-wrap:wrap;gap:8px;align-items:center;margin-top:10px;}

  .section-title{
    font-weight:900;margin:0 0 10px;color:#0f172a;font-size:14px;
    display:flex;align-items:center;justify-content:space-between;gap:10px;
  }

  .box{
    padding:12px;
    border-radius:16px;
    border:1px dashed rgba(15,23,42,.14);
    background: rgba(15,23,42,.02);
  }

  .sel{
    padding:12px 12px;border-radius:14px;border:1px solid rgba(15,23,42,.12);
    background:#fff;color:#0f172a;font-size:14px;outline:none;width:100%;
  }
  .btn-primary{
    border:none;border-radius:999px;padding:12px 16px;cursor:pointer;
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    color:#fff;font-weight:900;font-size:14px;
    width:100%;
  }
  .btn-secondary{
    border:1px solid rgba(15,23,42,.12);
    border-radius:999px;padding:12px 16px;
    background:#fff;color:#0f172a;font-weight:900;
    text-decoration:none;display:inline-flex;align-items:center;justify-content:center;
    font-size:14px;cursor:pointer;
  }

  .item{
    border:1px solid rgba(15,23,42,.10);
    border-radius:16px;
    padding:12px;
    background:#fff;
    box-shadow: 0 10px 22px rgba(15,23,42,.05);
    margin-bottom:10px;
  }

  .kv{
    display:grid;
    grid-template-columns: 130px 1fr;
    gap:10px;
    font-size:13px;
    color:#334155;
    margin-top:10px;
  }
  .kv b{color:#0f172a;}

  .muted{color:#64748b;font-weight:800;font-size:12.5px;}
</style>

@php
  $pm = (string) ($order->payment_method ?? '');
  $payStatus = (string) ($order->payment_status ?? '');
  $orderStatus = (string) ($order->status ?? '');

  $payChip = $payStatus === 'paid' ? 'ok' : 'bad';

  // stripe info optional
  $stripeSessionId = $order->stripe_session_id ?? null;
  $checkoutUrl = $order->checkout_url ?? null;
@endphp

<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:12px;">
  <div>
    <h2 style="margin:0;font-size:20px;font-weight:900;">Order #{{ $order->id }}</h2>
    <div style="margin-top:4px;color:#64748b;font-size:13px;font-weight:700;">
    </div>
  </div>

  <a class="btn-secondary" href="{{ route('dashboard.orders.index') }}">← Back</a>
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

<div class="grid">
  {{-- LEFT: ORDER + ITEMS --}}
  <div class="panel">
    <div class="head">
      <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:flex-start;">
        <div>
          <div class="title">Order Summary</div>
          <div class="sub">Created: {{ optional($order->created_at)->format('Y-m-d H:i') }} • Updated: {{ optional($order->updated_at)->format('Y-m-d H:i') }}</div>
        </div>

        <div class="row" style="margin-top:0;">
@php
  $original = (float) ($order->price ?? 0);
  $final    = (float) ($order->final_price ?? $order->price ?? 0);
  $hasCoupon = !empty($order->coupon_id);
  $isDiscounted = $hasCoupon && ($final < $original);
  $discount = max(0, $original - $final);
@endphp

@php
  $hasDiscount = ($order->final_price !== null) && ((float)$order->final_price < (float)$order->price);
@endphp

@if($hasDiscount)
  <span class="chip gold">💰 {{ number_format((float)$order->price, 2) }}</span>
  <span class="chip blue">✅ {{ number_format((float)$order->final_price, 2) }}</span>
  <span class="chip">Discount: {{ number_format((float)($order->price - $order->final_price), 2) }}</span>
@else
  <span class="chip gold">💰 {{ number_format((float)$order->price, 2) }}</span>
@endif


@if($hasCoupon)
  <span class="chip">Coupon ID: {{ $order->coupon_id }}</span>
  @if($order->coupon)
    <span class="chip blue">Code: {{ $order->coupon->code }}</span>
  @endif
@endif
          <span class="chip blue">{{ strtoupper($pm) }}</span>
          <span class="chip">Status: {{ $orderStatus }}</span>
          <span class="chip {{ $payChip }}">Payment: {{ $payStatus }}</span>
        </div>
      </div>

    @if(!empty($order->notes))
  <div style="margin-top:12px;">
    <div class="box" style="border-style:solid;">
      <div style="font-weight:900;margin-bottom:6px;">📝 Notes</div>
      <div style="color:#0f172a;font-weight:800;line-height:1.6;">
        {{ $order->notes }}
      </div>
    </div>
  </div>
@endif

    </div>

    <div class="body">
      <div class="section-title">
        <span>Items</span>
        <span class="chip">Total: {{ ($order->designOrders ?? collect())->count() }}</span>
      </div>

      @forelse($order->designOrders ?? [] as $do)
       @php
  $design = $do->design;
  $size = optional($do->measurement)->size; // ✅ المقاس المختار
@endphp


        <div class="item">
          <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:flex-start;">
            <div style="font-weight:900;color:#0f172a;">
              🎨 {{ $design->name ?? 'Design' }}
              <div class="muted">Design Number: {{ $design->id ?? '—' }}</div>
            </div>

            <span class="chip gold">
              {{ number_format((float)($design->price ?? 0), 2) }}
            </span>
          </div>

          <div class="kv">
            <b>Size</b>
            <div>{{ $size ?? '—' }}</div>

            <b>Description</b>
            <div>{{ $design->description ?? '—' }}</div>

            <b>Options</b>
            <div>
              @if(($do->options ?? collect())->count())
                @foreach($do->options as $opt)
                  <span class="chip" style="margin:0 6px 6px 0;">{{ $opt->name ?? ('Option #'.$opt->id) }}</span>
                @endforeach
              @else
                <span class="muted">No options</span>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="muted">No items found.</div>
      @endforelse
    </div>
  </div>

  {{-- RIGHT: USER + ADDRESS + UPDATE --}}
  <div style="display:flex;flex-direction:column;gap:14px;">
    {{-- USER --}}
    <div class="panel">
      <div class="head">
        <div class="title">User</div>
        <div class="sub">Owner of this order</div>
      </div>
      <div class="body">
        <div class="row" style="margin-top:0;">
          <span class="chip blue">User ID: {{ $order->user_id }}</span>
        </div>

        <div style="margin-top:10px;font-weight:900;color:#0f172a;">
          👤 {{ $order->user->name ?? '—' }}
        </div>
        <div class="muted" style="margin-top:6px;">
          {{ $order->user->email ?? '' }}
        </div>
      </div>
    </div>

    {{-- ADDRESS --}}
    <div class="panel">
      <div class="head">
        <div class="title">Address</div>
        <div class="sub">Delivery details</div>
      </div>
      <div class="body">
        <div class="row" style="margin-top:0;">
        </div>

        <div class="box" style="margin-top:10px;">
          @if($order->address)
            <div class="kv" style="margin-top:0;">
              <b>City</b><div>{{ $order->address->city ?? '—' }}</div>
              <b>Street</b><div>{{ $order->address->street ?? '—' }}</div>
              <b>Build</b><div>{{ $order->address->build ?? '—' }}</div>
              <b>Latitude</b><div>{{ $order->address->latitude ?? '—' }}</div>
              <b>Longitude</b><div>{{ $order->address->longitude ?? '—' }}</div>
            </div>
          @else
            <div class="muted">No address loaded.</div>
          @endif
        </div>
      </div>
    </div>

    {{-- UPDATE --}}
    <div class="panel">
      <div class="head">
        <div class="title">Update</div>
        <div class="sub"></div>
      </div>

      <div class="body">
        <form method="POST" action="{{ route('dashboard.orders.updateStatus', $order->id) }}">
          @csrf
          @method('PUT')

          <div class="box">
            <div style="display:flex;flex-direction:column;gap:10px;">
              <div>
                <div class="muted" style="margin-bottom:6px;">Order Status</div>
                <select class="sel" name="status">
                  @foreach($statuses as $st)
                    <option value="{{ $st }}" {{ $order->status===$st ? 'selected' : '' }}>
                      {{ strtoupper($st) }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <div class="muted" style="margin-bottom:6px;">Payment Status</div>
                <select class="sel" name="payment_status">
                  @foreach($paymentStatuses as $ps)
                    <option value="{{ $ps }}" {{ $order->payment_status===$ps ? 'selected' : '' }}>
                      {{ strtoupper($ps) }}
                    </option>
                  @endforeach
                </select>
              </div>

              <button class="btn-primary" type="submit">Save Changes</button>
            </div>
          </div>
        </form>

        {{-- Stripe info (اختياري) --}}
        @if($pm === 'stripe')
          <div class="box" style="margin-top:12px;">
            <div style="font-weight:900;margin-bottom:8px;">Stripe Info</div>

            @if($stripeSessionId)
              <div class="muted" style="margin-bottom:6px;">Session ID</div>
              <div style="font-weight:800;font-size:13px;color:#0f172a;word-break:break-all;">{{ $stripeSessionId }}</div>
            @else
              <div class="muted">No stripe_session_id saved.</div>
            @endif

            @if($checkoutUrl)
              <a class="btn-secondary" target="_blank" rel="noopener"
                 href="{{ $checkoutUrl }}" style="width:100%;justify-content:center;margin-top:10px;">
                Open Checkout
              </a>
            @endif
          </div>
        @endif
      </div>
    </div>

  </div>
</div>
@endsection

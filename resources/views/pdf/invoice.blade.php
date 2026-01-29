<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Invoice {{ $invoice->invoice_number }}</title>
  <style>
    body{font-family: DejaVu Sans, sans-serif; color:#0f172a; font-size:12px; margin:0; padding:18px;}
    .top{display:flex; justify-content:space-between; align-items:flex-start; gap:14px; margin-bottom:14px;}
    .brand{font-weight:900; font-size:16px;}
    .muted{color:#64748b;}
    .box{border:1px solid #e2e8f0; border-radius:12px; padding:12px; margin-bottom:12px;}
    .title{font-weight:900; font-size:13px; margin:0 0 8px;}
    table{width:100%; border-collapse:collapse;}
    th,td{border:1px solid #e2e8f0; padding:8px; text-align:left; vertical-align:top;}
    th{background:#f8fafc; font-weight:900; color:#334155;}
    .right{text-align:right;}
    .badge{display:inline-block; padding:4px 10px; border-radius:999px; background:#f1f5f9; font-weight:900;}
    .items th{font-size:11px;}
    .items td{font-size:11px;}
    .note{white-space:pre-wrap;}
  </style>
</head>
<body>
@php
  $order = $invoice->order;

  $user = $order?->user;
  $address = $order?->address;
  $items = $order?->designOrders ?? collect([]);

  $price = (float)($order?->price ?? 0);
  $final = (float)($order?->final_price ?? $order?->price ?? 0);
  $discount = $price - $final;
@endphp

<div class="top">
  <div>
    <div class="brand">Invoice</div>
    <div class="muted">Invoice Number: <b>{{ $invoice->invoice_number }}</b></div>
    <div class="muted">Date: <b>{{ optional($invoice->created_at)->format('Y-m-d H:i') }}</b></div>
  </div>

  <div style="min-width:240px">
    <div class="box" style="margin-bottom:0">
      <div class="muted">Total</div>
      <div style="font-size:20px;font-weight:900" class="right">{{ number_format((float)$invoice->total, 2) }}</div>
    </div>
  </div>
</div>

<div class="box">
  <div class="title">Order Summary</div>
  <table>
    <tr>
      <th style="width:180px">Status</th>
      <td><span class="badge">{{ $order?->status ?? '-' }}</span></td>
    </tr>
    <tr>
      <th>Payment Method</th>
      <td>{{ $order?->payment_method ?? '-' }}</td>
    </tr>
    <tr>
      <th>Price</th>
      <td>{{ number_format($price, 2) }}</td>
    </tr>
    <tr>
      <th>Final Price</th>
      <td>{{ number_format($final, 2) }}</td>
    </tr>
    <tr>
      <th>Discount</th>
      <td>{{ number_format($discount, 2) }}</td>
    </tr>
    <tr>
      <th>Notes</th>
      <td class="note">{{ $order?->notes ?: '-' }}</td>
    </tr>
  </table>
</div>

<div class="box">
  <div class="title">Customer</div>
  <table>
    <tr>
      <th style="width:180px">Name</th>
      <td>{{ $user?->name ?? '-' }}</td>
    </tr>
  </table>
</div>

<div class="box">
  <div class="title">Address</div>
  <table>
    <tr>
      <th style="width:180px">City</th>
      <td>{{ $address?->city ?? '-' }}</td>
    </tr>
    <tr>
      <th>Street</th>
      <td>{{ $address?->street ?? '-' }}</td>
    </tr>
    <tr>
      <th>Building</th>
      <td>{{ $address?->build ?? '-' }}</td>
    </tr>
  </table>
</div>

<div class="box">
  <div class="title">Items</div>

  @if($items->count())
    <table class="items">
      <thead>
        <tr>
          <th style="width:28px">#</th>
          <th>Design</th>
          <th style="width:120px">Design Price</th>
          <th style="width:120px">Measurement</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $idx => $designOrder)
          @php
            $design = $designOrder->design;
            $measurement = $designOrder->measurement;
            $options = $designOrder->options ?? collect([]);
          @endphp
          <tr>
            <td>{{ $idx + 1 }}</td>
            <td>{{ $design?->name ?? '-' }}</td>
            <td class="right">{{ number_format((float)($design?->price ?? 0), 2) }}</td>
            <td>{{ $measurement?->size ?? '-' }}</td>
            <td>
              @if($options->count())
                @foreach($options as $o)
                  <div>
                    <b>{{ $o->name }}</b>
                    <span class="muted">({{ $o->type ?? '-' }})</span>
                  </div>
                @endforeach
              @else
                <span class="muted">No options</span>
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <div class="muted">No items found.</div>
  @endif
</div>

<div class="muted" style="text-align:center;margin-top:10px">
  Generated automatically by the system.
</div>

</body>
</html>

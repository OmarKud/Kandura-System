@extends('layouts.dashboard')

@section('title', 'Payment Canceled')

@section('head')
<style>
  .sidebar, .topbar, .mobile-toggle { display:none !important; }
  .main { margin-left:0 !important; }
  .content-card { max-width:720px; margin:32px auto; }
</style>
@endsection

@section('content')
<div class="content-card">
  <div style="display:flex; gap:14px; align-items:center; margin-bottom:14px;">
    <div style="width:44px;height:44px;border-radius:14px;display:grid;place-items:center;background:rgba(239,68,68,.12);color:#7f1d1d;font-weight:900;">✕</div>
    <div>
      <div style="font-weight:900;font-size:18px;">لم تكتمل عملية الدفع</div>
      <div style="color:var(--muted);font-size:13px;">تم الإلغاء أو لم يتم الدفع.</div>
    </div>
  </div>

  @if($order)
    <div style="border:1px solid var(--border); padding:12px; border-radius:14px; background:var(--card);">
      <div style="font-weight:800;margin-bottom:8px;">تفاصيل الطلب</div>
      <div style="color:var(--muted);font-size:13px;line-height:1.8">
        رقم الطلب: <b style="color:var(--text)">#{{ $order->id }}</b><br>
        الحالة: <b style="color:var(--text)">{{ $order->status }}</b><br>
        حالة الدفع: <b style="color:var(--text)">{{ $order->payment_status }}</b>
      </div>
    </div>
  @endif

 
</div>
@endsection

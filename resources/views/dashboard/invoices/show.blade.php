@extends('layouts.dashboard')
@section('title','Invoice Details')

@section('head')
<style>
  .wrap{max-width:900px;margin:0 auto}
  .card{background:#fff;border:1px solid rgba(15,23,42,.12);border-radius:16px;box-shadow:0 10px 30px rgba(2,6,23,.06);padding:14px}
  .top{display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;align-items:flex-end}
  .title{font-size:18px;font-weight:900;margin:0}
  .muted{color:#64748b;font-weight:800}
  .btn{height:38px;padding:0 14px;border-radius:999px;border:1px solid rgba(15,23,42,.14);background:rgba(37,99,235,.10);font-weight:900;text-decoration:none;color:#0f172a;display:inline-flex;align-items:center;justify-content:center}
  .btn.ghost{background:#fff}
  table{width:100%;border-collapse:collapse;margin-top:12px}
  th,td{padding:12px 14px;border:1px solid rgba(15,23,42,.08);text-align:left}
  th{background:rgba(37,99,235,.06);width:220px}
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="card">
    <div class="top">
      <div>
        <h2 class="title">Invoice {{ $invoice->invoice_number }}</h2>
        <div class="muted">Created: {{ optional($invoice->created_at)->format('Y-m-d H:i') }}</div>
      </div>

      <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a class="btn ghost" href="{{ route('dashboard.invoices.index') }}">Back</a>
        <a class="btn" href="{{ route('dashboard.invoices.download', $invoice) }}">Download PDF</a>
      </div>
    </div>

    <table>
      <tr>
        <th>Invoice Number</th>
        <td>{{ $invoice->invoice_number }}</td>
      </tr>
      <tr>
        <th>Order ID</th>
        <td>{{ $invoice->order_id }}</td>
      </tr>
      <tr>
        <th>Total</th>
        <td>{{ number_format((float)$invoice->total, 2) }}</td>
      </tr>
      <tr>
        <th>PDF URL</th>
        <td>{{ $invoice->pdf_url ?? '-' }}</td>
      </tr>
      <tr>
        <th>Created</th>
        <td>{{ optional($invoice->created_at)->format('Y-m-d H:i') }}</td>
      </tr>
    </table>
  </div>
</div>
@endsection

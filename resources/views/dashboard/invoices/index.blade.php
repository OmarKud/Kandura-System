@extends('layouts.dashboard')
@section('title','Invoices')

@section('head')
<style>
  .wrap{max-width:1100px;margin:0 auto}
  .card{background:#fff;border:1px solid rgba(15,23,42,.12);border-radius:16px;box-shadow:0 10px 30px rgba(2,6,23,.06);overflow:hidden}
  .head{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:14px;border-bottom:1px solid rgba(15,23,42,.08)}
  .head h2{margin:0;font-size:18px;font-weight:900}
  .muted{color:#64748b;font-weight:800;font-size:12px}
  .filters{display:flex;gap:8px;flex-wrap:wrap}
  .in{height:38px;border-radius:10px;border:1px solid rgba(15,23,42,.14);padding:0 10px;font-weight:800}
  .btn{height:38px;padding:0 14px;border-radius:999px;border:1px solid rgba(15,23,42,.14);background:rgba(37,99,235,.10);font-weight:900;text-decoration:none;color:#0f172a;display:inline-flex;align-items:center;justify-content:center}
  .btn.ghost{background:#fff}
  table{width:100%;border-collapse:collapse}
  th,td{padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.06);text-align:left}
  th{background:rgba(37,99,235,.06);font-size:12px;color:#475569}
  td{font-size:13px}
  .right{text-align:right}
  .actions{display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap}
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="card">
    <div class="head">
      <div>
        <h2>Invoices</h2>
        <div class="muted">Browse invoices (table fields only) and download PDF.</div>
      </div>

      <form class="filters" method="GET" action="{{ route('dashboard.invoices.index') }}">
        <input class="in" type="text" name="q" value="{{ $q }}" placeholder="Search invoice_number or order_id">
        <select class="in" name="per_page">
          @foreach([15,25,50,100] as $n)
            <option value="{{ $n }}" @selected($perPage==$n)>{{ $n }}/page</option>
          @endforeach
        </select>
        <button class="btn" type="submit">Apply</button>
        <a class="btn ghost" href="{{ route('dashboard.invoices.index') }}">Reset</a>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>Invoice Number</th>
          <th>Order</th>
          <th>Total</th>
          <th>Created</th>
          <th class="right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($invoices as $inv)
          <tr>
            <td style="font-weight:900">{{ $inv->invoice_number }}</td>
            <td>{{ $inv->order_id }}</td>
            <td style="font-weight:900">{{ number_format((float)$inv->total, 2) }}</td>
          
            <td>{{ optional($inv->created_at)->format('Y-m-d H:i') }}</td>
            <td class="right">
              <div class="actions">
                <a class="btn ghost" href="{{ route('dashboard.invoices.show', $inv) }}">Show</a>
                <a class="btn" href="{{ route('dashboard.invoices.download', $inv) }}">Download</a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="muted" style="text-align:center;padding:18px">No invoices found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:12px 14px">
      {{ $invoices->links() }}
    </div>
  </div>
</div>
@endsection

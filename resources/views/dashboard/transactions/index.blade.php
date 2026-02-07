@extends('layouts.dashboard')
@section('title','Transactions')

@section('content')
<style>
  .filters{
    background: rgba(15,23,42,.02);
    border: 1px solid rgba(15,23,42,.10);
    border-radius: 16px;
    padding: 12px;
    margin-bottom: 14px;
  }
  .row{display:flex;flex-wrap:wrap;gap:10px;align-items:end}
  .field{display:flex;flex-direction:column;gap:6px;min-width:200px;flex:1}
  .field label{font-size:12px;color:var(--muted);font-weight:900}
  .input, .select{
    height:40px;border-radius:12px;border:1px solid rgba(15,23,42,.14);
    padding:0 12px;outline:none;background:#fff;font-weight:800;
  }
  .btn{height:40px;border-radius:12px;border:1px solid rgba(15,23,42,.14);background:#fff;padding:0 12px;cursor:pointer;font-weight:1000;}
  .btn.primary{background: rgba(37,99,235,.10);border-color: rgba(37,99,235,.22);color:#1d4ed8;}
  table{width:100%;border-collapse:separate;border-spacing:0 10px}
  th{font-size:12px;color:var(--muted);text-align:left;font-weight:1000;padding:0 10px}
  td{
    background:#fff;border:1px solid rgba(15,23,42,.10);
    padding:12px 10px;font-weight:900;
  }
  tr td:first-child{border-radius:14px 0 0 14px}
  tr td:last-child{border-radius:0 14px 14px 0}
  .muted{color:var(--muted);font-weight:800;font-size:12px}
  .pill{display:inline-flex;align-items:center;gap:6px;padding:6px 10px;border-radius:999px;border:1px solid rgba(15,23,42,.12);background: rgba(15,23,42,.03);font-size:12px;font-weight:1000;}
  .pill.ok{border-color: rgba(34,197,94,.25);background: rgba(34,197,94,.12);color:#166534;}
  .pill.bad{border-color: rgba(220,38,38,.25);background: rgba(220,38,38,.12);color:#7f1d1d;}
</style>

<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:12px;">
  <div>
    <h2 style="margin:0; font-weight:1100;">💳 Transactions</h2>
    <div class="muted">Index</div>
  </div>
</div>

<div class="filters">
  <form method="GET" action="{{ route('dashboard.transactions.index') }}">
    <div class="row">
      <div class="field" style="min-width:280px;flex:2">
        <label>Search</label>
        <input class="input" name="search" value="{{ request('search') }}" placeholder="reference / user / email / order number">
      </div>

      <div class="field">
        <label>Type</label>
        <select class="select" name="type">
          <option value="">All</option>
          <option value="deposit" @selected(request('type')==='deposit')>deposit</option>
          <option value="payment" @selected(request('type')==='payment')>payment</option>
        </select>
      </div>

      <div class="field">
        <label>Per page</label>
        <select class="select" name="per_page">
          @foreach([10,15,25,50,100] as $pp)
            <option value="{{ $pp }}" @selected((int)request('per_page',15)===$pp)>{{ $pp }}</option>
          @endforeach
        </select>
      </div>

      <button class="btn primary" type="submit">Apply</button>
      <a class="btn" href="{{ route('dashboard.transactions.index') }}" style="text-decoration:none;display:inline-flex;align-items:center">Reset</a>
    </div>
  </form>
</div>

<table>
  <thead>
    <tr>
   <th>User</th>
<th>Reference</th>
<th>Order</th>
<th>Type</th>
<th>Amount</th>
<th>Deposit by</th>
<th>Created at</th>

    </tr>
  </thead>
  <tbody>
    @forelse($transactions as $t)
      <tr>
        <td>
          <div style="display:flex; flex-direction:column; gap:2px;">
            <span>{{ $t->user?->name ?? '—' }}</span>
            <span class="muted">{{ $t->user?->email ?? '' }}</span>
          </div>
        </td>

        <td style="font-family: ui-monospace, SFMono-Regular, Menlo, monospace;">
          {{ $t->reference }}
        </td>

        <td>
          @if($t->order_id)
            <span class="pill">Order #{{ $t->order_id }}</span>
          @else
            <span class="muted">—</span>
          @endif
        </td>

        <td>
          @if($t->type === 'deposit')
            <span class="pill ok">➕ deposit</span>
          @elseif($t->type === 'payment')
            <span class="pill bad">➖ payment</span>
          @else
            <span class="pill">{{ $t->type }}</span>
          @endif
        </td>

        <td>
          {{ number_format((float)$t->amount, 2) }}
        </td>
        <td>
  @if($t->type === 'deposit')
    @if($t->admin)
      <div style="display:flex; flex-direction:column; gap:2px;">
        <span>{{ $t->admin->name }}</span>
        <span class="muted">{{ $t->admin->email }}</span>
      </div>
    @else
      <span class="muted">—</span>
    @endif
  @else
    <span class="muted">—</span>
  @endif
</td>


        <td class="muted">
          {{ $t->created_at?->format('Y-m-d H:i') }}
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="6" class="muted" style="background:transparent;border:none;padding:6px 2px;">
          No transactions.
        </td>
      </tr>
    @endforelse
  </tbody>
</table>

<div style="margin-top:14px;display:flex;justify-content:center;">
  {{ $transactions->links() }}
</div>
@endsection

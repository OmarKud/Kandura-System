@extends('layouts.dashboard')

@section('title', 'Wallets')

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
    background:#fff;color:#0f172a;font-size:14px;outline:none;min-width:220px;
  }
  .btn-primary{
    border:none;border-radius:999px;padding:12px 16px;cursor:pointer;
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    color:#fff;font-weight:900;font-size:14px;
  }
  .btn-secondary{
    border:1px solid rgba(15,23,42,.12);
    border-radius:999px;
    padding:12px 16px;
    cursor:pointer;
    background:#fff;
    color:#0f172a;
    font-weight:900;
    text-decoration:none;
    display:inline-flex;align-items:center;justify-content:center;
    font-size:14px;
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

  .row{display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:center;margin-top:10px;}
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

  .charge-box{
    margin-top:12px;
    padding:12px;
    border-radius:16px;
    border:1px dashed rgba(15,23,42,.14);
    background: rgba(15,23,42,.02);
  }
  .charge-input{
    width:100%;
    padding:11px 12px;
    border-radius:14px;
    border:1px solid rgba(15,23,42,.12);
    font-size:14px;
    outline:none;
  }
</style>

<div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:12px;">
  <div>
    <h2 style="margin:0;font-size:20px;font-weight:900;">Wallets</h2>
    <div style="margin-top:4px;color:#64748b;font-size:13px;font-weight:700;">
      Show Wallets & Quick charge
    </div>
  </div>
  <a class="btn-secondary" href="{{ route('dashboard.wallets.index') }}">Reset</a>
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

<form method="GET" action="{{ route('dashboard.wallets.index') }}" class="filters">
  <input class="inp" type="text" name="search" placeholder="Search name/email..." value="{{ request('search') }}">

  <input class="inp" type="number" step="0.01" name="min_amount" placeholder="Min amount" value="{{ request('min_amount') }}">
  <input class="inp" type="number" step="0.01" name="max_amount" placeholder="Max amount" value="{{ request('max_amount') }}">

  <select class="sel" name="sort_by">
    <option value="amount" {{ request('sort_by')=='amount'?'selected':'' }}>Sort by Amount</option>
    <option value="created_at" {{ request('sort_by')=='created_at'?'selected':'' }}>Sort by Created</option>
    <option value="updated_at" {{ request('sort_by')=='updated_at'?'selected':'' }}>Sort by Updated</option>
  </select>

  <select class="sel" name="sort_dir">
    <option value="asc" {{ request('sort_dir')=='asc'?'selected':'' }}>ASC</option>
    <option value="desc" {{ request('sort_dir','desc')=='desc'?'selected':'' }}>DESC</option>
  </select>

  <select class="sel" name="per_page">
    @foreach([10,20,50] as $pp)
      <option value="{{ $pp }}" {{ (int)request('per_page',10)===$pp?'selected':'' }}>{{ $pp }}/page</option>
    @endforeach
  </select>

  <button class="btn-primary" type="submit">Apply</button>
</form>

<div class="cards">
  @forelse($wallets as $w)
    <div class="card">
      <div class="card-head">
        <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;align-items:flex-start;">
          <div>
            <div class="title">👤 {{ $w->user->name ?? '—' }}</div>
            <div class="sub">{{ $w->user->email ?? '' }}</div>
          </div>
        </div>

        <div class="row">
          <span class="chip blue">User ID: {{ $w->user_id }}</span>
          <span class="chip gold">💰 Amount: {{ number_format((float)$w->amount, 2) }}</span>
        </div>

        <div class="row" style="margin-top:8px;">
          <span class="chip">📅 {{ optional($w->created_at)->format('Y-m-d') }}</span>
          <span class="chip">↻ {{ optional($w->updated_at)->format('Y-m-d') }}</span>
        </div>
      </div>

      <div class="body">
        <div class="charge-box">
          <div style="font-weight:900;margin-bottom:8px;color:#0f172a;">Quick Charge</div>

          <form method="POST" action="{{ route('dashboard.wallets.charge', $w->user_id) }}">
            @csrf
            <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
              <input class="charge-input" type="number" step="0.01" min="0.01" name="amount" placeholder="Enter amount (e.g. 50)" required>

              <button class="btn-primary" type="submit" style="padding:11px 14px;">
                Charge
              </button>

              <a class="btn-secondary" href="{{ route('dashboard.wallets.show', $w->user_id) }}" style="padding:11px 14px;">
                Details
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  @empty
    <div style="padding:16px;color:#64748b;font-weight:800;">No wallets found.</div>
  @endforelse
</div>

<div style="margin-top:12px;">
  {{ $wallets->links() }}
</div>
@endsection

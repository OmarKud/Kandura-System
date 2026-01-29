@extends('layouts.dashboard')

@section('title', 'Wallet Details')

@section('content')
<style>
  .panel{
    border:1px solid rgba(15,23,42,.10);
    border-radius:18px;
    background:#fff;
    box-shadow: 0 16px 44px rgba(15,23,42,.07);
    overflow:hidden;
  }
  .head{
    padding:14px;
    background:
      radial-gradient(900px 260px at 15% 0%, rgba(79,209,255,.18), transparent 60%),
      linear-gradient(180deg, #ffffff, rgba(37,99,235,.05));
    border-bottom:1px solid rgba(15,23,42,.06);
  }
  .chip{
    display:inline-flex;align-items:center;gap:6px;
    padding:7px 10px;border-radius:999px;
    font-size:12px;font-weight:900;white-space:nowrap;
    border:1px solid rgba(15,23,42,.10);
    background: rgba(15,23,42,.05);
    color:#334155;
  }
  .chip.gold{background: rgba(212,160,23,.12);border-color: rgba(212,160,23,.20);color:#8a6a10;}
  .btn-primary{
    border:none;border-radius:999px;padding:12px 16px;cursor:pointer;
    background: linear-gradient(135deg, #60a5fa, #2563eb);
    color:#fff;font-weight:900;font-size:14px;
  }
  .inp{
    width:100%;
    padding:12px 12px;
    border-radius:14px;
    border:1px solid rgba(15,23,42,.12);
    font-size:14px;
    outline:none;
  }
</style>

<div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:12px;">
  <div>
    <h2 style="margin:0;font-size:20px;font-weight:900;">Wallet Details</h2>
    <div style="margin-top:4px;color:#64748b;font-size:13px;font-weight:700;">User wallet profile + manual charge</div>
  </div>

  <a href="{{ route('dashboard.wallets.index') }}" style="text-decoration:none;" class="btn-primary">
    ← Back to Wallets
  </a>
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

<div class="panel">
  <div class="head">
    <div style="display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;">
      <div>
        <div style="font-size:16px;font-weight:900;color:#0f172a;">👤 {{ $user->name }}</div>
        <div style="margin-top:6px;color:#64748b;font-size:13px;font-weight:800;">{{ $user->email }}</div>
      </div>

      <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
        <span class="chip">User ID: {{ $user->id }}</span>
        <span class="chip gold">Amount: {{ number_format((float)($wallet->amount ?? 0), 2) }}</span>
      </div>
    </div>
  </div>

  <div style="padding:14px;">
    <div style="font-weight:900;margin-bottom:10px;">Manual Charge</div>

    <form method="POST" action="{{ route('dashboard.wallets.charge', $user->id) }}">
      @csrf

      <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">
        <div style="flex:1;min-width:260px;">
          <input class="inp" type="number" min="0.01" step="0.01" name="amount" placeholder="Enter charge amount..." required>
        </div>

        <button class="btn-primary" type="submit">Charge Wallet</button>
      </div>

      <div style="margin-top:10px;color:#64748b;font-size:12.5px;font-weight:700;">
      </div>
    </form>
  </div>
</div>
@endsection

@extends('layouts.dashboard')

@section('title', 'User Details')

@section('head')
<style>
  :root{
    --s-ink:#0f172a;
    --s-muted:#64748b;
    --s-border: rgba(15,23,42,.10);
    --s-shadow: 0 16px 48px rgba(15,23,42,.10);
    --s-blue: rgba(37,99,235,.14);
    --s-green: rgba(34,197,94,.14);
    --s-red: rgba(239,68,68,.14);
  }
  .s-wrap{max-width:1100px;margin:0 auto;}
  .s-hero{
    padding:18px;border-radius:18px;
    border:1px solid rgba(37,99,235,.18);
    background:
      radial-gradient(900px 320px at 20% 0%, rgba(37,99,235,.18), transparent 60%),
      linear-gradient(180deg, #fff, rgba(37,99,235,.06));
    box-shadow: var(--s-shadow);
    margin-bottom:14px;
    display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;
  }
  .s-left{display:flex;align-items:center;gap:12px;min-width:0}
  .s-avatar{
    width:68px;height:68px;border-radius:16px;object-fit:cover;
    border:1px solid rgba(15,23,42,.10);background:#fff;flex:0 0 auto;
  }
  .s-avatar-fallback{
    width:68px;height:68px;border-radius:16px;
    display:flex;align-items:center;justify-content:center;
    background: rgba(37,99,235,.10);
    border:1px solid rgba(37,99,235,.22);
    font-weight:900;color:var(--s-ink);font-size:22px;flex:0 0 auto;
  }
  .s-title{font-size:20px;font-weight:900;margin:0;color:var(--s-ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .s-sub{margin-top:6px;display:flex;flex-wrap:wrap;gap:8px}
  .s-chip{
    display:inline-flex;align-items:center;gap:6px;
    border-radius:999px;padding:6px 10px;
    border:1px solid rgba(15,23,42,.10);
    background: rgba(15,23,42,.03);
    font-size:12px;font-weight:900;color:var(--s-ink);
    white-space:nowrap;
  }
  .s-chip-green{background: var(--s-green);border-color: rgba(34,197,94,.22);}
  .s-chip-red{background: var(--s-red);border-color: rgba(239,68,68,.22);}

  .s-btn{
    height:40px;padding:0 14px;border-radius:999px;cursor:pointer;
    border:1px solid rgba(15,23,42,.14);
    background: rgba(37,99,235,.10);
    color: var(--s-ink);
    font-weight:900;
    transition: background .12s ease, border-color .12s ease, transform .05s ease;
    white-space:nowrap;
  }
  .s-btn:hover{background: rgba(37,99,235,.16);border-color: rgba(37,99,235,.25);}
  .s-btn:active{transform: translateY(1px);}
  .s-btn-ghost{background:transparent;}
  .s-btn-danger{background: rgba(239,68,68,.10);border-color: rgba(239,68,68,.20);}
  .s-btn-danger:hover{background: rgba(239,68,68,.16);border-color: rgba(239,68,68,.26);}
  .s-btn-success{background: rgba(34,197,94,.10);border-color: rgba(34,197,94,.20);}
  .s-btn-success:hover{background: rgba(34,197,94,.16);border-color: rgba(34,197,94,.26);}

  .s-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
  .s-card{
    border-radius:16px;padding:14px;
    border:1px solid var(--s-border);
    background: rgba(255,255,255,.94);
    box-shadow: 0 12px 34px rgba(15,23,42,.08);
  }
  .s-card h3{margin:0 0 10px 0;font-size:13px;color:var(--s-muted);font-weight:900}
  .s-k{color:var(--s-muted);font-size:12px;font-weight:900;margin-bottom:4px}
  .s-v{color:var(--s-ink);font-weight:900}
  .s-row{display:flex;justify-content:space-between;gap:12px;padding:10px 0;border-bottom:1px solid rgba(15,23,42,.06)}
  .s-row:last-child{border-bottom:none}

  .s-table{
    margin-top:12px;
    border-radius:16px; overflow:hidden;
    border:1px solid var(--s-border);
    background: rgba(255,255,255,.94);
    box-shadow: 0 12px 34px rgba(15,23,42,.08);
  }
  .s-thead,.s-tr{
    display:grid;grid-template-columns: .7fr 1fr 1fr 1fr 1.3fr;
    gap:10px;align-items:center;padding:12px 14px;
  }
  .s-thead{
    background: linear-gradient(180deg, rgba(37,99,235,.08), rgba(255,255,255,0));
    border-bottom:1px solid rgba(15,23,42,.08);
    font-size:12px;color:var(--s-muted);font-weight:900;
  }
  .s-tr{border-bottom:1px solid rgba(15,23,42,.06);font-size:13px}
  .s-tr:last-child{border-bottom:none}
  .s-muted{color:var(--s-muted);font-size:12px;font-weight:900}

  @media (max-width: 980px){
    .s-grid{grid-template-columns:1fr;}
    .s-thead{display:none}
    .s-tr{grid-template-columns:1fr;gap:6px}
  }
  .img-box{
    border:1px solid rgba(15,23,42,.10);
    border-radius:14px;
    overflow:hidden;
    background:#fff;
  }
  .img-toolbar{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
    align-items:center;
    margin-bottom:10px;
  }
  .img-btn{
    height:36px;
    padding:0 12px;
    border-radius:999px;
    border:1px solid rgba(15,23,42,.14);
    background:rgba(37,99,235,.10);
    font-weight:900;
    cursor:pointer;
  }
  .img-btn:hover{ background:rgba(37,99,235,.16); }
  .img-btn.active{
    border-color: rgba(37,99,235,.40);
    background: rgba(37,99,235,.22);
  }

  .img-size-sm{ width: 240px; height: 160px; }
  .img-size-md{ width: 100%;  height: 240px; }
  .img-size-lg{ width: 100%;  height: 360px; }

  .img-fit-cover img{ object-fit: cover; }
  .img-fit-contain img{ object-fit: contain; background: rgba(15,23,42,.02); }

  .img-box img{
    width:100%;
    height:100%;
    display:block;
  }
</style>

@endsection

@section('content')
@php
$img = optional($user->profileImage)->full_url;
  $initial = mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1));
  $isActive = ($user->status === 'active');
@endphp

<div class="s-wrap">
  <div class="s-hero">
    <div class="s-left">
      @if($img)
        <img class="s-avatar" src="{{ $img }}" alt="profile">
      @else
        <div class="s-avatar-fallback">{{ $initial }}</div>
      @endif

      <div style="min-width:0">
        <h2 class="s-title">{{ $user->name }}</h2>
        <div class="s-sub">
          <span class="s-chip">#{{ $user->id }}</span>
          <span class="s-chip">USER</span>
          @if($isActive)
            <span class="s-chip s-chip-green">Active</span>
          @else
            <span class="s-chip s-chip-red">Inactive</span>
          @endif
        </div>
      </div>
    </div>

    <div style="display:flex;gap:10px;flex-wrap:wrap">
      <a class="s-btn s-btn-ghost" href="{{ route('dashboard.users.index') }}">Back</a>

      <form method="POST" action="{{ route('dashboard.users.updateStatus', $user) }}">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="{{ $isActive ? 'inactive' : 'active' }}">
        <button
          class="s-btn {{ $isActive ? 's-btn-danger' : 's-btn-success' }}"
          type="submit"
          onclick="return confirm('Change user status?')"
        >
          {{ $isActive ? 'Deactivate' : 'Activate' }}
        </button>
      </form>
    </div>
  </div>

  @if(session('success'))
    <div style="margin-bottom:14px;padding:12px 14px;border-radius:14px;border:1px solid rgba(34,197,94,.22);background: rgba(34,197,94,.10);font-weight:900;color:var(--s-ink);">
      {{ session('success') }}
    </div>
  @endif

  <div class="s-grid">
    <div class="s-card">
      <h3>Contact</h3>
      <div style="margin-bottom:10px">
        <div class="s-k">Email</div>
        <div class="s-v">{{ $user->email }}</div>
      </div>
      <div>
        <div class="s-k">Phone</div>
        <div class="s-v">{{ $user->phone ?? '-' }}</div>
      </div>
    </div>

    <div class="s-card">
      <h3>Account</h3>
      <div style="margin-bottom:10px">
        <div class="s-k">Created At</div>
        <div class="s-v">{{ optional($user->created_at)->format('Y-m-d H:i') }}</div>
      </div>
      <div>
        <div class="s-k">Updated At</div>
        <div class="s-v">{{ optional($user->updated_at)->format('Y-m-d H:i') }}</div>
      </div>
    </div>

    <div class="s-card">
      <h3>Stats</h3>
      <div class="s-row">
        <div class="s-muted">All Orders</div>
        <div class="s-v">{{ $ordersCount }}</div>
      </div>
      <div class="s-row">
        <div class="s-muted">Paid Orders</div>
        <div class="s-v">{{ $paidOrdersCount }}</div>
      </div>
    </div>

    <div class="s-card">
     @php
  $img = optional($user->profileImage)->full_url ?? optional($user->profileImage)->url;
@endphp

<div class="s-card">
  <h3>Profile Image</h3>

  @if($img)
    <div class="img-toolbar">
    

    <div id="profileImageBox"
         class="img-box img-fit-contain img-size-md">
      <img src="{{ $img }}" alt="profile image">
    </div>
  @else
    <div class="s-muted">No image uploaded.</div>
  @endif
</div>

  </div>

  <div class="s-table">
    <div class="s-thead">
      <div>#</div>
      <div>Final Price</div>
      <div>Status</div>
      <div>Payment</div>
      <div>Created</div>
    </div>

    @forelse($recentOrders as $o)
      <div class="s-tr">
        <div style="font-weight:900;color:var(--s-ink)">#{{ $o->id }}</div>
        <div style="font-weight:900;color:var(--s-ink)">{{ $o->final_price }}</div>
        <div><span class="s-chip">{{ $o->status }}</span></div>
        <div>
          @if($o->payment_status === 'paid')
            <span class="s-chip s-chip-green">paid</span>
          @else
            <span class="s-chip">{{ $o->payment_status }}</span>
          @endif
        </div>
        <div class="s-muted">{{ optional($o->created_at)->format('Y-m-d H:i') }}</div>
      </div>
    @empty
      <div style="padding:18px;text-align:center;color:var(--s-muted);font-weight:900">
        No recent orders.
      </div>
    @endforelse
  </div>
</div>
@endsection

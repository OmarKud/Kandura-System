@extends('layouts.dashboard')

@section('title', 'Users')

@section('head')
<style>
  :root{
    --u-ink:#0f172a;
    --u-muted:#64748b;
    --u-border: rgba(15,23,42,.10);
    --u-shadow: 0 14px 40px rgba(15,23,42,.08);
    --u-blue: rgba(37,99,235,.14);
    --u-green: rgba(34,197,94,.14);
    --u-red: rgba(239,68,68,.14);
  }
  .u-wrap{max-width:1200px;margin:0 auto;}
  .u-hero{
    padding:18px 18px;border-radius:18px;
    border:1px solid rgba(37,99,235,.18);
    background:
      radial-gradient(900px 320px at 20% 0%, rgba(37,99,235,.18), transparent 60%),
      linear-gradient(180deg, #fff, rgba(37,99,235,.06));
    box-shadow: var(--u-shadow);
    margin-bottom:14px;
  }
  .u-title{font-size:20px;font-weight:900;margin:0;color:var(--u-ink);letter-spacing:.2px}
  .u-sub{margin-top:4px;color:var(--u-muted);font-size:13px;font-weight:700}

  .u-panel{
    padding:14px;border-radius:16px;
    border:1px solid var(--u-border);
    background: rgba(255,255,255,.92);
    box-shadow: 0 10px 28px rgba(15,23,42,.06);
    margin-bottom:14px;
  }
  .u-form{display:flex;gap:10px;flex-wrap:wrap;align-items:end}
  .u-field{display:flex;flex-direction:column;gap:6px;min-width:180px;flex:1}
  .u-label{font-size:12px;color:var(--u-muted);font-weight:900}
  .u-input,.u-select{
    height:40px;border-radius:12px;padding:0 12px;
    border:1px solid rgba(15,23,42,.14);
    background:#fff;color:var(--u-ink);outline:none;
  }
  .u-input:focus,.u-select:focus{border-color:rgba(37,99,235,.55);box-shadow:0 0 0 4px rgba(37,99,235,.12)}
  .u-actions{display:flex;gap:10px}
  .u-btn{
    height:40px;padding:0 14px;border-radius:999px;cursor:pointer;
    border:1px solid rgba(15,23,42,.14);
    background: rgba(37,99,235,.10);
    color: var(--u-ink);
    font-weight:900;
    transition: background .12s ease, border-color .12s ease, transform .05s ease;
    white-space:nowrap;
  }
  .u-btn:hover{background: rgba(37,99,235,.16);border-color: rgba(37,99,235,.25);}
  .u-btn:active{transform: translateY(1px);}
  .u-btn-ghost{background:transparent;}
  .u-btn-danger{background: rgba(239,68,68,.10);border-color: rgba(239,68,68,.20);}
  .u-btn-danger:hover{background: rgba(239,68,68,.16);border-color: rgba(239,68,68,.26);}
  .u-btn-success{background: rgba(34,197,94,.10);border-color: rgba(34,197,94,.20);}
  .u-btn-success:hover{background: rgba(34,197,94,.16);border-color: rgba(34,197,94,.26);}

  .u-card{
    border-radius:16px; overflow:hidden;
    border:1px solid var(--u-border);
    background: rgba(255,255,255,.94);
    box-shadow: var(--u-shadow);
  }

  .u-thead, .u-row{
    display:grid;
    grid-template-columns: 1.4fr 1.2fr .55fr .6fr .65fr .75fr .95fr 1.2fr;
    gap:10px;
    align-items:center;
    padding:12px 14px;
  }
  .u-thead{
    background: linear-gradient(180deg, rgba(37,99,235,.08), rgba(255,255,255,0));
    border-bottom:1px solid rgba(15,23,42,.08);
    font-size:12px;color:var(--u-muted);font-weight:900;
  }
  .u-row{border-bottom:1px solid rgba(15,23,42,.06);font-size:13px}
  .u-row:last-child{border-bottom:none}

  .u-user{display:flex;gap:10px;align-items:center;min-width:0}
  .u-avatar-sm{
    width:34px;height:34px;border-radius:50%;
    object-fit:cover;border:1px solid rgba(15,23,42,.10);background:#fff;flex:0 0 auto;
  }
  .u-avatar-fallback{
    width:34px;height:34px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    background: rgba(37,99,235,.10);
    border:1px solid rgba(37,99,235,.22);
    font-weight:900;color:var(--u-ink);flex:0 0 auto;
  }
  .u-name{font-weight:900;color:var(--u-ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .u-id{color:var(--u-muted);font-size:12px;font-weight:800}

  .u-chip{
    display:inline-flex;align-items:center;gap:6px;
    border-radius:999px;padding:6px 10px;
    border:1px solid rgba(15,23,42,.10);
    background: rgba(15,23,42,.03);
    font-size:12px;font-weight:900;color:var(--u-ink);
    white-space:nowrap;
  }
  .u-chip-green{background: var(--u-green);border-color: rgba(34,197,94,.22);}
  .u-chip-red{background: var(--u-red);border-color: rgba(239,68,68,.22);}

  .u-profile-thumb{
    width:54px;height:40px;border-radius:12px;
    object-fit:cover;border:1px solid rgba(15,23,42,.10);background:#fff;
  }
  .u-profile-empty{
    width:54px;height:40px;border-radius:12px;
    border:1px dashed rgba(15,23,42,.18);
    display:flex;align-items:center;justify-content:center;
    color:var(--u-muted);font-size:12px;font-weight:900;
    background: rgba(15,23,42,.02);
  }

  .u-right{display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap}

  .u-alert{
    margin:0 0 14px 0;padding:12px 14px;border-radius:14px;
    border:1px solid rgba(34,197,94,.22);
    background: rgba(34,197,94,.10);
    color:var(--u-ink);font-weight:900;
  }

  @media (max-width: 980px){
    .u-thead{display:none}
    .u-row{
      grid-template-columns: 1fr;
      gap:10px;
    }
    .u-right{justify-content:flex-start}
  }
</style>
@endsection

@section('content')
<div class="u-wrap">
  <div class="u-hero">
    <div class="u-title">Users</div>
  </div>

  @if(session('success'))
    <div class="u-alert">{{ session('success') }}</div>
  @endif

  <div class="u-panel">
    <form method="GET" action="{{ route('dashboard.users.index') }}" class="u-form">
      <div class="u-field" style="min-width:280px;flex:2">
        <div class="u-label">Search</div>
        <input class="u-input" type="text" name="q" value="{{ $q ?? '' }}" placeholder="Name, email, or phone">
      </div>

      <div class="u-field">
        <div class="u-label">Status</div>
        <select class="u-select" name="status">
          <option value="">All</option>
          <option value="active"   {{ ($status ?? '')==='active' ? 'selected' : '' }}>Active</option>
          <option value="inactive" {{ ($status ?? '')==='inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
      </div>

      <div class="u-field">
        <div class="u-label">Sort</div>
        <select class="u-select" name="sort">
          <option value="created_at"   {{ ($sort ?? 'created_at')==='created_at' ? 'selected' : '' }}>Newest</option>
          <option value="name"         {{ ($sort ?? '')==='name' ? 'selected' : '' }}>Name</option>
          <option value="orders_count" {{ ($sort ?? '')==='orders_count' ? 'selected' : '' }}>Orders Count</option>
        </select>
      </div>

      <div class="u-field" style="min-width:120px;flex:0">
        <div class="u-label">Direction</div>
        <select class="u-select" name="dir">
          <option value="desc" {{ ($dir ?? 'desc')==='desc' ? 'selected' : '' }}>DESC</option>
          <option value="asc"  {{ ($dir ?? '')==='asc' ? 'selected' : '' }}>ASC</option>
        </select>
      </div>

      <div class="u-field" style="min-width:120px;flex:0">
        <div class="u-label">Per Page</div>
        <select class="u-select" name="per_page">
          @foreach([10,15,25,50] as $n)
            <option value="{{ $n }}" {{ (int)($perPage ?? 15)===$n ? 'selected' : '' }}>{{ $n }}</option>
          @endforeach
        </select>
      </div>

      <div class="u-actions">
        <button class="u-btn" type="submit">Apply</button>
        <a class="u-btn u-btn-ghost" href="{{ route('dashboard.users.index') }}">Reset</a>
      </div>
    </form>
  </div>

  <div class="u-card">
    <div class="u-thead">
      <div>User</div>
      <div>Contact</div>
      <div>Role</div>
      <div>Orders</div>
      <div>Status</div>
      <div>Profile Image</div>
      <div>Created</div>
      <div style="text-align:right">Actions</div>
    </div>

    @forelse($users as $user)
      @php
$img = optional($user->profileImage)->full_url;
        $initial = mb_strtoupper(mb_substr($user->name ?? 'U', 0, 1));
        $isActive = ($user->status === 'active');
      @endphp

      <div class="u-row">
        <div class="u-user">
          @if($img)
            <img class="u-avatar-sm" src="{{ $img }}" alt="avatar">
          @else
            <div class="u-avatar-fallback">{{ $initial }}</div>
          @endif

          <div style="min-width:0">
            <div class="u-name">{{ $user->name }}</div>
            <div class="u-id">#{{ $user->id }}</div>
          </div>
        </div>

        <div>
          <div style="font-weight:900;color:var(--u-ink)">{{ $user->email }}</div>
          <div class="u-id">{{ $user->phone ?? '-' }}</div>
        </div>

        <div>
          <span class="u-chip">USER</span>
        </div>

        <div>
          <span class="u-chip">{{ (int)($user->orders_count ?? 0) }}</span>
        </div>

        <div>
          @if($isActive)
            <span class="u-chip u-chip-green">Active</span>
          @else
            <span class="u-chip u-chip-red">Inactive</span>
          @endif
        </div>

        <div>
          @if($img)
            <img class="u-profile-thumb" src="{{ $img }}" alt="profile image">
          @else
            <div class="u-profile-empty">No Image</div>
          @endif
        </div>

        <div class="u-id">
          {{ optional($user->created_at)->format('Y-m-d H:i') }}
        </div>

        <div class="u-right">
          <a class="u-btn u-btn-ghost" href="{{ route('dashboard.users.show', $user) }}">View</a>

          <form method="POST" action="{{ route('dashboard.users.updateStatus', $user) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="{{ $isActive ? 'inactive' : 'active' }}">
            <button
              type="submit"
              class="u-btn {{ $isActive ? 'u-btn-danger' : 'u-btn-success' }}"
              onclick="return confirm('Change user status?')"
            >
              {{ $isActive ? 'Deactivate' : 'Activate' }}
            </button>
          </form>
        </div>
      </div>
    @empty
      <div style="padding:18px;text-align:center;color:var(--u-muted);font-weight:900">
        No users found for the current filters.
      </div>
    @endforelse
  </div>

  @if($users->hasPages())
    <div style="margin-top:12px">
      {{ $users->links() }}
    </div>
  @endif
</div>
@endsection

@extends('layouts.dashboard')
@section('title','Admins')

@section('head')
<style>
  .wrap{max-width:1200px;margin:0 auto}
  .hero{padding:16px;border-radius:18px;border:1px solid rgba(37,99,235,.18);
    background: radial-gradient(900px 320px at 20% 0%, rgba(37,99,235,.18), transparent 60%),
                linear-gradient(180deg, #fff, rgba(37,99,235,.06));
    box-shadow: var(--shadow);margin-bottom:14px;}
  .hero h2{margin:0;font-size:18px;font-weight:900}
  .hero p{margin:6px 0 0;color:var(--muted);font-weight:800;font-size:13px}
  .panel{background:#fff;border:1px solid var(--border);border-radius:18px;padding:12px;box-shadow: var(--shadow2);margin-bottom:14px;}
  .field{display:flex;flex-direction:column;gap:6px;min-width:220px;flex:1}
  .label{font-size:12px;color:var(--muted);font-weight:900}
  .input,.select{height:40px;border-radius:12px;padding:0 12px;border:1px solid rgba(15,23,42,.14);outline:none;background:#fff}
  .btnx{height:40px;padding:0 14px;border-radius:999px;border:1px solid rgba(15,23,42,.14);
        background: rgba(37,99,235,.10);font-weight:900;cursor:pointer;text-decoration:none;color:var(--text);
        display:inline-flex;align-items:center;justify-content:center}
  .btnx:hover{background: rgba(37,99,235,.16);border-color: rgba(37,99,235,.25);}
  .btnx-ghost{background:#fff}
  .table{border-radius:18px;overflow:hidden;border:1px solid var(--border);background:#fff;box-shadow: var(--shadow);}
  .thead,.row{display:grid;grid-template-columns: 1.2fr 1.2fr .7fr .8fr 1fr;gap:10px;align-items:center;padding:12px 14px}
  .thead{background: linear-gradient(180deg, rgba(37,99,235,.08), rgba(255,255,255,0));
         border-bottom:1px solid rgba(15,23,42,.08);font-weight:900;color:var(--muted);font-size:12px}
  .row{border-bottom:1px solid rgba(15,23,42,.06);font-size:13px}
  .row:last-child{border-bottom:none}
  .right{display:flex;gap:8px;justify-content:flex-end;flex-wrap:wrap}
  .chip{display:inline-flex;align-items:center;padding:6px 10px;border-radius:999px;font-weight:900;font-size:12px;
        border:1px solid rgba(15,23,42,.10);background: rgba(15,23,42,.03)}
  .chip-green{background: rgba(34,197,94,.14);border-color: rgba(34,197,94,.22)}
  .chip-red{background: rgba(239,68,68,.14);border-color: rgba(239,68,68,.22)}
  .alert{margin-bottom:14px;padding:12px 14px;border-radius:14px;border:1px solid rgba(34,197,94,.22);
         background: rgba(34,197,94,.10);font-weight:900;}
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="hero">
    <h2>Admins</h2>
    <p>Create admins and assign dynamic roles (admin2, etc.).</p>
  </div>

  @if(session('success'))
    <div class="alert">{{ session('success') }}</div>
  @endif

  <div class="panel">
    <form method="GET" action="{{ route('dashboard.superadmin.admins.index') }}" style="display:flex;gap:10px;flex-wrap:wrap;align-items:end;width:100%">
      <div class="field" style="flex:2;min-width:280px">
        <div class="label">Search</div>
        <input class="input" name="q" value="{{ $q }}" placeholder="Name, email, phone">
      </div>

      <div class="field" style="min-width:140px;flex:0">
        <div class="label">Per Page</div>
        <select class="select" name="per_page">
          @foreach([10,15,25,50] as $n)
            <option value="{{ $n }}" {{ (int)$perPage===$n ? 'selected' : '' }}>{{ $n }}</option>
          @endforeach
        </select>
      </div>

      <div class="right" style="margin-left:auto">
        <button class="btnx" type="submit">Apply</button>
        <a class="btnx btnx-ghost" href="{{ route('dashboard.superadmin.admins.index') }}">Reset</a>
        <a class="btnx" href="{{ route('dashboard.superadmin.admins.create') }}">+ Create</a>
      </div>
    </form>
  </div>

  <div class="table">
    <div class="thead">
      <div>Name</div>
      <div>Contact</div>
      <div>Status</div>
      <div>Role</div>
      <div style="text-align:right">Actions</div>
    </div>

    @forelse($admins as $a)
      @php
        $roleName = $a->roles->first()?->name ?? '-';
        $active = ($a->status === 'active');
      @endphp
      <div class="row">
        <div style="font-weight:900">{{ $a->name }}</div>
        <div>
          <div style="font-weight:900">{{ $a->email }}</div>
          <div style="color:var(--muted);font-weight:800;font-size:12px">{{ $a->phone }}</div>
        </div>
        <div>
          <span class="chip {{ $active ? 'chip-green' : 'chip-red' }}">{{ $active ? 'Active' : 'Inactive' }}</span>
        </div>
        <div>
          <span class="chip">{{ $roleName }}</span>
        </div>
        <div class="right">
<a class="btnx btnx-ghost" href="{{ route('dashboard.superadmin.admins.editRole', $a) }}">Edit Role</a>
        </div>
      </div>
    @empty
      <div style="padding:16px;text-align:center;color:var(--muted);font-weight:900">No admins found.</div>
    @endforelse
  </div>

  <div style="margin-top:12px">{{ $admins->links() }}</div>
</div>
@endsection

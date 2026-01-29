@extends('layouts.dashboard')
@section('title','Edit Admin Role')

@section('head')
<style>
  .wrap{max-width:860px;margin:0 auto}
  .hero{margin-bottom:14px}
  .hero h2{margin:0;font-size:18px;font-weight:900}
  .hero p{margin:6px 0 0;color:var(--muted);font-weight:800;font-size:13px}
  .card{background:#fff;border:1px solid var(--border);border-radius:18px;padding:14px;box-shadow:var(--shadow)}
  .field{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
  .label{font-size:12px;color:var(--muted);font-weight:900}
  .select{height:42px;border-radius:12px;padding:0 12px;border:1px solid rgba(15,23,42,.14);outline:none;background:#fff}
  .btnx{height:40px;padding:0 14px;border-radius:999px;border:1px solid rgba(15,23,42,.14);
        background: rgba(37,99,235,.10);font-weight:900;cursor:pointer;text-decoration:none;color:var(--text);
        display:inline-flex;align-items:center;justify-content:center}
  .btnx-ghost{background:#fff}
  .btnx-danger{background: rgba(239,68,68,.10);border-color: rgba(239,68,68,.22);}
  .btnx-danger:hover{background: rgba(239,68,68,.16);border-color: rgba(239,68,68,.30);}
  .row{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;margin-top:12px}
  .err{color:#b91c1c;font-weight:800;font-size:12px;margin-top:4px}
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="hero">
    <h2>Edit Admin Role</h2>
    <p>Change role only for: <b>{{ $admin->name }}</b> ({{ $admin->email }})</p>
  </div>

  <div class="card">
    <form method="POST" action="{{ route('dashboard.superadmin.admins.updateRole', $admin) }}">
      @csrf
      @method('PUT')

      <div class="field">
        <div class="label">Role</div>
        <select class="select" name="role_id" required>
          @foreach($roles as $r)
            <option value="{{ $r->id }}" {{ (int)old('role_id', $admin->role_id) === (int)$r->id ? 'selected' : '' }}>
              {{ $r->name }}
            </option>
          @endforeach
        </select>
        @error('role_id') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <a class="btnx btnx-ghost" href="{{ route('dashboard.superadmin.admins.index') }}">Back</a>
        <button class="btnx" type="submit">Save Role</button>
      </div>
    </form>

    <hr style="margin:14px 0; border:none; border-top:1px solid rgba(15,23,42,.08)">

    <form method="POST" action="{{ route('dashboard.superadmin.admins.destroy', $admin) }}"
          onsubmit="return confirm('Delete this admin permanently?')">
      @csrf
      @method('DELETE')

      <div class="row" style="justify-content:space-between">
        <div style="color:var(--muted);font-weight:800;font-size:13px">
          This will remove the admin account and its assigned roles.
        </div>
        <button class="btnx btnx-danger" type="submit">Delete Admin</button>
      </div>
    </form>
  </div>
</div>
@endsection

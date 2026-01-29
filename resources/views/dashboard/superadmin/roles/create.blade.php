@extends('layouts.dashboard')
@section('title','Create Role')

@section('head')
<style>
  .wrap{max-width:980px;margin:0 auto}
  .hero{margin-bottom:14px}
  .hero h2{margin:0;font-size:18px;font-weight:900}
  .hero p{margin:6px 0 0;color:var(--muted);font-weight:800;font-size:13px}
  .card{background:#fff;border:1px solid var(--border);border-radius:18px;padding:14px;box-shadow:var(--shadow)}
  .field{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
  .label{font-size:12px;color:var(--muted);font-weight:900}
  .input{height:42px;border-radius:12px;padding:0 12px;border:1px solid rgba(15,23,42,.14);outline:none}
  .grid{display:grid;grid-template-columns: repeat(3, minmax(0,1fr));gap:10px}
  .perm{
    border:1px solid rgba(15,23,42,.10);border-radius:14px;padding:10px;
    display:flex;gap:10px;align-items:flex-start;background: rgba(15,23,42,.02)
  }
  .perm input{margin-top:3px}
  .perm b{font-size:13px}
  .btnx{height:40px;padding:0 14px;border-radius:999px;border:1px solid rgba(15,23,42,.14);
        background: rgba(37,99,235,.10);font-weight:900;cursor:pointer;text-decoration:none;color:var(--text);
        display:inline-flex;align-items:center;justify-content:center}
  .btnx-ghost{background:#fff}
  .row{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;margin-top:12px}
  .err{color:#b91c1c;font-weight:800;font-size:12px;margin-top:4px}
  @media (max-width: 980px){ .grid{grid-template-columns: 1fr} }
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="hero">
    <h2>Create Role</h2>
    <p>Create a role and select its permissions.</p>
  </div>

  <div class="card">
    <form method="POST" action="{{ route('dashboard.superadmin.roles.store') }}">
      @csrf

      <div class="field">
        <div class="label">Role Name</div>
        <input class="input" name="name" value="{{ old('name') }}" placeholder="e.g. admin2" required>
        @error('name') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="field">
        <div class="label">Permissions</div>

        <div class="grid">
          @foreach($permissions as $p)
            <label class="perm">
              <input type="checkbox" name="permissions[]" value="{{ $p->id }}"
                {{ in_array($p->id, old('permissions', [])) ? 'checked' : '' }}>
              <div>
                <b>{{ $p->name }}</b>
                <div style="color:var(--muted);font-weight:800;font-size:12px">guard: api</div>
              </div>
            </label>
          @endforeach
        </div>

        @error('permissions') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <a class="btnx btnx-ghost" href="{{ route('dashboard.superadmin.roles.index') }}">Back</a>
        <button class="btnx" type="submit">Create</button>
      </div>
    </form>
  </div>
</div>
@endsection

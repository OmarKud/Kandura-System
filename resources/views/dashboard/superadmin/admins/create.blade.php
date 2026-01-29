@extends('layouts.dashboard')
@section('title','Create Admin')

@section('head')
<style>
  .wrap{max-width:900px;margin:0 auto}
  .hero{margin-bottom:14px}
  .hero h2{margin:0;font-size:18px;font-weight:900}
  .hero p{margin:6px 0 0;color:var(--muted);font-weight:800;font-size:13px}
  .card{background:#fff;border:1px solid var(--border);border-radius:18px;padding:14px;box-shadow:var(--shadow)}
  .grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
  .field{display:flex;flex-direction:column;gap:6px}
  .label{font-size:12px;color:var(--muted);font-weight:900}
  .input,.select{height:42px;border-radius:12px;padding:0 12px;border:1px solid rgba(15,23,42,.14);outline:none;background:#fff}
  .btnx{height:40px;padding:0 14px;border-radius:999px;border:1px solid rgba(15,23,42,.14);
        background: rgba(37,99,235,.10);font-weight:900;cursor:pointer;text-decoration:none;color:var(--text);
        display:inline-flex;align-items:center;justify-content:center}
  .btnx-ghost{background:#fff}
  .row{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end;margin-top:12px}
  .err{color:#b91c1c;font-weight:800;font-size:12px;margin-top:4px}
  @media (max-width: 980px){ .grid{grid-template-columns:1fr} }
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="hero">
    <h2>Create Admin</h2>
    <p>Create admin user and assign a dynamic role.</p>
  </div>

  <div class="card">
    <form method="POST" action="{{ route('dashboard.superadmin.admins.store') }}">
      @csrf

      <div class="grid">
        <div class="field">
          <div class="label">Name</div>
          <input class="input" name="name" value="{{ old('name') }}" required>
          @error('name') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <div class="label">Email</div>
          <input class="input" type="email" name="email" value="{{ old('email') }}" required>
          @error('email') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <div class="label">Phone</div>
          <input class="input" name="phone" value="{{ old('phone') }}" required>
          @error('phone') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <div class="label">Status</div>
          <select class="select" name="status" required>
            <option value="active" {{ old('status','active')==='active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status')==='inactive' ? 'selected' : '' }}>Inactive</option>
          </select>
          @error('status') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <div class="label">Role</div>
          <select class="select" name="role_id" required>
            <option value="">Select role</option>
            @foreach($roles as $r)
              <option value="{{ $r->id }}" {{ (string)old('role_id')===(string)$r->id ? 'selected' : '' }}>
                {{ $r->name }}
              </option>
            @endforeach
          </select>
          @error('role_id') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <div class="label">Password</div>
          <input class="input" type="password" name="password" required>
          @error('password') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <div class="label">Confirm Password</div>
          <input class="input" type="password" name="password_confirmation" required>
        </div>
      </div>

      <div class="row">
        <a class="btnx btnx-ghost" href="{{ route('dashboard.superadmin.admins.index') }}">Back</a>
        <button class="btnx" type="submit">Create</button>
      </div>
    </form>
  </div>
</div>
@endsection

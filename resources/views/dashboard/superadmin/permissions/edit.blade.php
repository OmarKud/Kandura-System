@extends('layouts.dashboard')
@section('title','Edit Permission')

@section('head')
<style>
  .wrap{max-width:780px;margin:0 auto}
  .card{background:#fff;border:1px solid var(--border);border-radius:18px;padding:14px;box-shadow:var(--shadow)}
  .hero{margin-bottom:14px}
  .hero h2{margin:0;font-size:18px;font-weight:900}
  .hero p{margin:6px 0 0;color:var(--muted);font-weight:800;font-size:13px}
  .field{display:flex;flex-direction:column;gap:6px;margin-bottom:12px}
  .label{font-size:12px;color:var(--muted);font-weight:900}
  .input{height:42px;border-radius:12px;padding:0 12px;border:1px solid rgba(15,23,42,.14);outline:none}
  .btnx{height:40px;padding:0 14px;border-radius:999px;border:1px solid rgba(15,23,42,.14);
        background: rgba(37,99,235,.10);font-weight:900;cursor:pointer;text-decoration:none;color:var(--text);
        display:inline-flex;align-items:center;justify-content:center}
  .btnx-ghost{background:#fff}
  .row{display:flex;gap:10px;flex-wrap:wrap;justify-content:flex-end}
  .err{color:#b91c1c;font-weight:800;font-size:12px;margin-top:4px}
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="hero">
    <h2>Edit Permission</h2>
    <p>Update permission name.</p>
  </div>

  <div class="card">
    <form method="POST" action="{{ route('dashboard.superadmin.permissions.update', $permission) }}">
      @csrf
      @method('PUT')

      <div class="field">
        <div class="label">Name</div>
        <input class="input" name="name" value="{{ old('name', $permission->name) }}" required>
        @error('name') <div class="err">{{ $message }}</div> @enderror
      </div>

      <div class="row">
        <a class="btnx btnx-ghost" href="{{ route('dashboard.superadmin.permissions.index') }}">Back</a>
        <button class="btnx" type="submit">Save</button>
      </div>
    </form>
  </div>
</div>
@endsection

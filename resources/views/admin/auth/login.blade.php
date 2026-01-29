@extends('layouts.auth')

@section('title', 'Admin Login')

@section('content')
<div class="logo-row">
  <div class="right-brand">KANDURA</div>
  <div class="lang">EN</div>
</div>

<div class="headline">Welcome</div>
<div class="subline">Welcome to Kandura System</div>
<div class="admin-tag">🔐 Admin Login</div>

<div class="form-area">
  @if ($errors->any())
    <div class="alert alert-danger py-2 mt-3">
      <div class="fw-bold mb-1">Login failed</div>
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
          <li class="small">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.login') }}" class="mt-3">
    @csrf

    <div class="mb-3">
      <input
        type="email"
        name="email"
        class="form-control"
        value="{{ old('email') }}"
        placeholder="Email"
        required
        autofocus
      >
    </div>

    <div class="mb-2">
      <div class="input-group">
        <input
          id="password"
          type="password"
          name="password"
          class="form-control"
          placeholder="Password"
          required
        >
        <button class="btn btn-outline-secondary" type="button" id="togglePassword" style="border-radius:14px;">
          👁
        </button>
      </div>

      <div class="d-flex justify-content-end mt-2">
        {{-- if you have route for forgot password, put it here --}}
        {{-- <a class="small-link" href="{{ route('password.request') }}">Forgot password?</a> --}}
      </div>
    </div>

    <button type="submit" class="btn btn-cyan w-100 text-white mt-3">
      Login
    </button>

    <div class="text-center mt-3" style="font-size:12px;color:var(--muted);">
      This page is for administrators only.
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
  (function () {
    const btn = document.getElementById('togglePassword');
    const input = document.getElementById('password');

    btn.addEventListener('click', function () {
      const hidden = input.type === 'password';
      input.type = hidden ? 'text' : 'password';
      btn.textContent = hidden ? '🙈' : '👁';
    });
  })();
</script>
@endsection

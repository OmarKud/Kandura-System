@extends('layouts.auth')

@section('title', 'Super Admin Login')

@section('content')
    <div class="auth-header">
        <div class="logo-circle">S</div>
        <div class="auth-title">Super Admin Login</div>
        <div class="auth-subtitle">
            سجّل دخولك كـ <strong>Super Admin</strong> 
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('super.login.post') }}" novalidate>
        @csrf

        <div class="form-group">
            <label for="email">البريد الإلكتروني</label>
            <input
                id="email"
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                required
                autofocus
            >
        </div>

        <div class="form-group password-wrapper">
            <label for="password">كلمة المرور</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control"
                required
            >
            <button type="button" class="toggle-password">Show</button>
        </div>

        <button type="submit" class="btn-primary">
            تسجيل الدخول كـ Super Admin
        </button>

        <div class="auth-footer">
            
        </div>
    </form>
@endsection

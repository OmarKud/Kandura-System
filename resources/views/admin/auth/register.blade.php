@extends('layouts.auth')

@section('title', 'Admin Register')

@section('content')
    <div class="auth-header">
        <div class="logo-circle">A</div>
        <div class="auth-title">Create Admin Account</div>
        <div class="auth-subtitle">
            سيتم إنشاء الحساب بدور <strong>Admin</strong> 
        </div>
    </div>

    @if ($errors->any())
        <div class="alert-error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('admin.register.post') }}" novalidate>
        @csrf

        <div class="form-group">
            <label>الاسم</label>
            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('name') }}"
                required
            >
        </div>

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <input
                type="email"
                name="email"
                class="form-control"
                value="{{ old('email') }}"
                required
            >
        </div>

        <div class="form-group">
            <label>رقم الهاتف</label>
            <input
                type="text"
                name="phone"
                class="form-control"
                value="{{ old('phone') }}"
                required
            >
        </div>

        {{-- ما منعرض role_id، لأنه منثبّتو 3 بالـ controller مع AuthService --}}

        <div class="form-group password-wrapper">
            <label>كلمة المرور</label>
            <input
                type="password"
                name="password"
                class="form-control"
                required
            >
            <button type="button" class="toggle-password">Show</button>
        </div>

        <div class="form-group">
            <label>تأكيد كلمة المرور</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control"
                required
            >
        </div>

        <button type="submit" class="btn-primary">
            إنشاء حساب Admin
        </button>

        <div class="auth-footer">
            بعد إنشاء الحساب سيتم تسجيل الدخول تلقائياً وتحويلك إلى صفحة الـ Dashboard.
        </div>
    </form>
@endsection

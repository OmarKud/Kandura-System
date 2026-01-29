@extends('layouts.dashboard')

@section('title', 'Design Options')

@section('content')

@php
    // مرونة حسب اسم المتغير عندك بدون ما نغيّر الكنترولر
    $list = $options ?? $designOptions ?? $design_options ?? null;

    // لو عندك types جاهزة من controller خليه، إذا لا، ما رح ينهار
    $typesList = $types ?? [];

    $inputStyle = "padding:12px 12px;border-radius:14px;border:1px solid rgba(15,23,42,.12);
                   background:#ffffff;color:#0f172a;font-size:14px;outline:none;min-width:240px;";

    $selectStyle = "padding:12px 12px;border-radius:14px;border:1px solid rgba(15,23,42,.12);
                    background:#ffffff;color:#0f172a;font-size:14px;outline:none;";

    $btnStyle = "padding:12px 16px;border-radius:999px;border:1px solid rgba(37,99,235,.22);
                 background:linear-gradient(135deg,#2563eb,#1d4ed8);
                 color:#fff;font-size:14px;font-weight:900;cursor:pointer;";
@endphp

{{-- PAGE HEADER --}}
<div style="
    margin-bottom:12px;
    padding:18px 18px;
    border-radius:18px;
    border:1px solid rgba(37,99,235,.18);
    background:
      radial-gradient(850px 260px at 15% 0%, rgba(79,209,255,.18), transparent 60%),
      linear-gradient(180deg, #ffffff, rgba(37,99,235,.06));
    box-shadow: 0 16px 50px rgba(15,23,42,.08);
">
    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;">
        <div>
            <h2 style="margin:0;font-size:22px;font-weight:900;color:#0f172a;">Design Options</h2>
            <div style="margin-top:6px;color:#64748b;font-size:14px;font-weight:600;">
            </div>
        </div>

        @if(Route::has('dashboard.design-options.create'))
            <a href="{{ route('dashboard.design-options.create') }}"
               style="
                    text-decoration:none;
                    padding:12px 16px;
                    border-radius:999px;
                    background:linear-gradient(135deg,#2563eb,#1d4ed8);
                    color:#fff;
                    font-size:14px;
                    font-weight:900;
                    box-shadow: 0 12px 24px rgba(37,99,235,.18);
               ">
                + New Option
            </a>
        @endif
    </div>

    {{-- badge total --}}
    <div style="margin-top:10px;">
        <span style="
            display:inline-flex;align-items:center;gap:8px;
            padding:8px 14px;border-radius:999px;
            background:rgba(79,209,255,.18);
            border:1px solid rgba(79,209,255,.35);
            color:#0369a1;font-weight:900;font-size:13px;
        ">
            Total:
            <span style="color:#0f172a;">
                {{ method_exists($list,'total') ? $list->total() : (is_iterable($list) ? count($list) : 0) }}
            </span>
        </span>
    </div>
</div>

{{-- ALERTS --}}
@if(session('success'))
    <div style="margin-bottom:12px;padding:12px 14px;border-radius:16px;border:1px solid rgba(34,197,94,.25);background:rgba(34,197,94,.08);color:#166534;font-size:14px;font-weight:700;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="margin-bottom:12px;padding:12px 14px;border-radius:16px;border:1px solid rgba(239,68,68,.25);background:rgba(239,68,68,.08);color:#991b1b;font-size:14px;font-weight:700;">
        {{ session('error') }}
    </div>
@endif

{{-- FILTERS (نفس الفلاتر الموجودة عندك غالباً: search/type/sort_by/sort_dir/per_page) --}}
<form method="GET" action="{{ url()->current() }}"
      style="
        margin-bottom:12px;
        display:flex;gap:10px;flex-wrap:wrap;
        padding:14px;border-radius:18px;
        border:1px solid rgba(15,23,42,.10);
        background:rgba(255,255,255,.78);
        box-shadow: 0 12px 26px rgba(15,23,42,.06);
      ">

    {{-- Search --}}
    <input type="text" name="search" placeholder="Search name/type..."
           value="{{ request('search') }}" style="{{ $inputStyle }}">

    {{-- Type (إذا ما عندك types جاهزة، رح يضل dropdown بس مع All Types) --}}
    <select name="type" style="{{ $selectStyle }}">
        <option value="">All Types</option>
        @foreach($typesList as $t)
            <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>
                {{ $t }}
            </option>
        @endforeach
    </select>

    {{-- Sort By --}}
    <select name="sort_by" style="{{ $selectStyle }}">
        <option value="id" {{ request('sort_by','id') == 'id' ? 'selected' : '' }}>Sort by ID</option>
        <option value="type" {{ request('sort_by') == 'type' ? 'selected' : '' }}>Sort by Type</option>
        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Sort by Created</option>
    </select>

    {{-- Sort Dir --}}
    <select name="sort_dir" style="{{ $selectStyle }}">
        <option value="asc"  {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>ASC</option>
        <option value="desc" {{ request('sort_dir','desc') == 'desc' ? 'selected' : '' }}>DESC</option>
    </select>

    {{-- Per Page --}}
    <select name="per_page" style="{{ $selectStyle }}">
        @foreach([10,20,50] as $size)
            <option value="{{ $size }}" {{ request('per_page',10) == $size ? 'selected' : '' }}>
                {{ $size }} / page
            </option>
        @endforeach
    </select>

    <button type="submit" style="{{ $btnStyle }}">Apply</button>

    @if(request()->query())
        <a href="{{ url()->current() }}"
           style="
                margin-left:auto;
                text-decoration:none;
                padding:12px 16px;
                border-radius:999px;
                border:1px solid rgba(15,23,42,.12);
                background:#fff;
                color:#334155;
                font-weight:900;
                font-size:14px;
           ">
            Reset
        </a>
    @endif
</form>

{{-- TABLE --}}
<div style="
    overflow:auto;
    border-radius:18px;
    border:1px solid rgba(15,23,42,.10);
    background:#fff;
    box-shadow: 0 16px 44px rgba(15,23,42,.07);
">
    <table style="width:100%;border-collapse:collapse;font-size:14px;min-width:900px;">
        <thead>
        <tr style="background:rgba(79,209,255,.16);border-bottom:1px solid rgba(15,23,42,.10);">
            <th style="text-align:left;padding:14px;font-weight:900;color:#0f172a;">ID</th>
            <th style="text-align:left;padding:14px;font-weight:900;color:#0f172a;">Name (EN)</th>
            <th style="text-align:left;padding:14px;font-weight:900;color:#0f172a;">Name (AR)</th>
            <th style="text-align:left;padding:14px;font-weight:900;color:#0f172a;">Type</th>
            <th style="text-align:left;padding:14px;font-weight:900;color:#0f172a;">Actions</th>
        </tr>
        </thead>

        <tbody>
        @if(!$list)
            <tr>
                <td colspan="5" style="padding:14px;color:#991b1b;font-weight:800;">
                    Blade: متغير القائمة غير موجود. لازم يكون اسمها $options أو $designOptions.
                </td>
            </tr>
        @else
            @forelse($list as $opt)
                <tr style="border-bottom:1px solid rgba(15,23,42,.08);" onmouseover="this.style.background='rgba(37,99,235,.04)'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px;font-weight:800;color:#0f172a;">{{ $opt->id }}</td>

                    <td style="padding:14px;color:#0f172a;">
                        @if(method_exists($opt,'getTranslation'))
                            {{ $opt->getTranslation('name','en') }}
                        @else
                            {{ data_get($opt,'name.en') ?? '-' }}
                        @endif
                    </td>

                    <td style="padding:14px;color:#0f172a;">
                        @if(method_exists($opt,'getTranslation'))
                            {{ $opt->getTranslation('name','ar') }}
                        @else
                            {{ data_get($opt,'name.ar') ?? '-' }}
                        @endif
                    </td>

                    <td style="padding:14px;">
                        <span style="
                            display:inline-flex;
                            padding:7px 12px;
                            border-radius:999px;
                            border:1px solid rgba(79,209,255,.35);
                            background:rgba(79,209,255,.18);
                            color:#0369a1;
                            font-size:13px;
                            font-weight:900;
                        ">
                            {{ $opt->type ?? '-' }}
                        </span>
                    </td>

                    <td style="padding:14px;display:flex;gap:10px;flex-wrap:wrap;">
                        @if(Route::has('dashboard.design-options.edit'))
                            <a href="{{ route('dashboard.design-options.edit', $opt->id) }}"
                               style="text-decoration:none;padding:10px 12px;border-radius:14px;border:1px solid rgba(15,23,42,.12);background:#fff;color:#0f172a;font-size:13px;font-weight:800;">
                                Edit
                            </a>
                        @endif

                        @if(Route::has('dashboard.design-options.destroy'))
                            <form method="POST" action="{{ route('dashboard.design-options.destroy', $opt->id) }}"
                                  onsubmit="return confirm('Delete this option?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        style="padding:10px 12px;border-radius:14px;border:1px solid rgba(239,68,68,.25);background:rgba(239,68,68,.08);color:#991b1b;font-size:13px;font-weight:900;cursor:pointer;">
                                    Delete
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:14px;color:#64748b;font-size:14px;">No options found.</td>
                </tr>
            @endforelse
        @endif
        </tbody>
    </table>
</div>

{{-- PAGINATION --}}
@if($list && method_exists($list,'links'))
    <div style="margin-top:14px;">
        {{ $list->links() }}
    </div>
@endif

@endsection

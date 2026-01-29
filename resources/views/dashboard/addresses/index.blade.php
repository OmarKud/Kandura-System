@extends('layouts.dashboard')

@section('title', 'Addresses')

@section('content')

    <style>
        :root{
            --blue:#2563eb;
            --cyan:#4fd1ff;
            --text:#0f172a;
            --muted:#64748b;
            --border: rgba(15,23,42,.10);
            --shadow: 0 18px 55px rgba(15,23,42,.08);
            --radius: 18px;
        }

        .addr-hero{
            margin-bottom:14px;
            padding:20px 18px;
            border-radius: var(--radius);
            border:1px solid rgba(37,99,235,.18);
            background:
                radial-gradient(850px 260px at 15% 0%, rgba(79,209,255,.18), transparent 60%),
                linear-gradient(180deg, #ffffff, rgba(37,99,235,.06));
            box-shadow: var(--shadow);
        }

        .addr-title{
            margin:0;
            font-size:22px;
            font-weight:900;
            color: var(--text);
            letter-spacing: -.2px;
        }

        .addr-sub{
            margin-top:6px;
            color: var(--muted);
            font-size:14px;
            font-weight:600;
        }

        .addr-filters{
            margin-bottom:14px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            padding:14px;
            border-radius: var(--radius);
            border:1px solid var(--border);
            background:rgba(255,255,255,.80);
            box-shadow: 0 14px 35px rgba(15,23,42,.06);
            align-items:center;
        }

        .addr-input, .addr-select{
            padding:12px 12px;
            border-radius:14px;
            border:1px solid var(--border);
            background:#ffffff;
            color: var(--text);
            font-size:14px;
            outline:none;
        }

        .addr-input{
            min-width:260px;
        }

        .addr-input:focus, .addr-select:focus{
            border-color: rgba(37,99,235,.45);
            box-shadow: 0 0 0 .22rem rgba(37,99,235,.12);
        }

        .btn-apply{
            padding:12px 16px;
            border-radius:999px;
            border:1px solid rgba(37,99,235,.22);
            background: linear-gradient(135deg, var(--blue), #1d4ed8);
            color:#fff;
            font-size:14px;
            font-weight:900;
            cursor:pointer;
            box-shadow: 0 16px 35px rgba(37,99,235,.18);
            transition:.18s;
        }
        .btn-apply:hover{ transform: translateY(-1px); filter: brightness(1.02); }
        .btn-apply:active{ transform: translateY(0); }

        .addr-table-wrap{
            overflow:auto;
            border-radius: var(--radius);
            border:1px solid var(--border);
            background:#fff;
            box-shadow: 0 18px 55px rgba(15,23,42,.07);
        }

        table.addr-table{
            width:100%;
            border-collapse: collapse;
            font-size:14px;
            min-width: 900px;
        }

        .addr-table thead tr{
            background: rgba(79,209,255,.16);
            border-bottom: 1px solid var(--border);
        }

        .addr-table th{
            text-align:left;
            padding:14px 14px;
            font-weight:900;
            color: var(--text);
            font-size:14px;
        }

        .addr-table td{
            padding:14px 14px;
            color: var(--text);
            border-bottom: 1px solid rgba(15,23,42,.08);
            vertical-align: middle;
        }

        .addr-table tbody tr:hover{
            background: rgba(37,99,235,.04);
        }

        .badge-soft{
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding:7px 10px;
            border-radius:999px;
            font-size:12.5px;
            font-weight:800;
            border:1px solid rgba(15,23,42,.10);
            background: rgba(15,23,42,.04);
            color:#334155;
            white-space:nowrap;
        }

        .badge-cyan{
            border:1px solid rgba(79,209,255,.35);
            background: rgba(79,209,255,.18);
            color:#0369a1;
        }

        .muted{
            color: var(--muted);
            font-weight:700;
        }

        /* Pagination bigger (works with Laravel Tailwind pagination) */
        .pagination{
            margin-top: 16px !important;
            gap: 8px;
        }
        .pagination a, .pagination span{
            font-size: 14px !important;
            padding: 10px 12px !important;
            border-radius: 12px !important;
        }
    </style>

    <div class="addr-hero">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;">
            <div>
                <h2 class="addr-title">📍 Location List (Addresses)</h2>
                <div class="addr-sub"></div>
            </div>

            <div class="badge-soft badge-cyan">
                Total: <span style="font-weight:900;color:#0f172a;">{{ $locations->total() }}</span>
            </div>
        </div>
    </div>

    {{-- Filters (نفس اللي عندك، بس أكبر وأرتب) --}}
    <form method="GET" action="{{ route('dashboard.addresses.index') }}" class="addr-filters">

        <input type="text" name="search" placeholder="Search city, build, user name..."
               value="{{ request('search') }}" class="addr-input">

        <input type="text" name="city" placeholder="Filter by city"
               value="{{ request('city') }}" class="addr-input">

        <input type="text" name="user_name" placeholder="Filter by user name"
               value="{{ request('user_name') }}" class="addr-input">

        <select name="sort_by" class="addr-select">
            <option value="city"      {{ request('sort_by') == 'city' ? 'selected' : '' }}>Sort by City</option>
            <option value="build"     {{ request('sort_by') == 'build' ? 'selected' : '' }}>Sort by Build</option>
            <option value="latitude"  {{ request('sort_by') == 'latitude' ? 'selected' : '' }}>Sort by Lat</option>
            <option value="longitude" {{ request('sort_by') == 'longitude' ? 'selected' : '' }}>Sort by Lng</option>
        </select>

        <select name="sort_dir" class="addr-select">
            <option value="asc"  {{ request('sort_dir') == 'asc' ? 'selected' : '' }}>ASC</option>
            <option value="desc" {{ request('sort_dir') == 'desc' ? 'selected' : '' }}>DESC</option>
        </select>

        <select name="per_page" class="addr-select">
            @foreach([10, 20, 50] as $size)
                <option value="{{ $size }}" {{ request('per_page', 10) == $size ? 'selected' : '' }}>
                    {{ $size }} / page
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-apply">Apply</button>

        @if(request()->hasAny(['search','city','user_name','sort_by','sort_dir','per_page']))
            <a href="{{ route('dashboard.addresses.index') }}"
               style="
                    margin-left:auto;
                    text-decoration:none;
                    padding:12px 14px;
                    border-radius:999px;
                    border:1px solid rgba(15,23,42,.10);
                    background:#fff;
                    color:#334155;
                    font-weight:900;
                    font-size:14px;
               ">
                Reset
            </a>
        @endif

    </form>

    {{-- Table (نفس الشكل، بس أكبر وأوضح + ألوان خفيفة) --}}
    <div class="addr-table-wrap">
        <table class="addr-table">
            <thead>
            <tr>
                <th>City</th>
                <th>Build</th>
                <th>Lat</th>
                <th>Lng</th>
                <th>User Name</th>
            </tr>
            </thead>

            <tbody>
            @forelse($locations as $loc)
                <tr>
                    <td>
                        <span class="badge-soft badge-cyan">🏙 {{ $loc->city }}</span>
                    </td>

                    <td style="font-weight:800;">
                        {{ $loc->build }}
                    </td>

                    <td class="muted">
                        {{ $loc->latitude }}
                    </td>

                    <td class="muted">
                        {{ $loc->longitude }}
                    </td>

                    <td style="font-weight:900;">
                        {{ optional($loc->user)->name ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding:16px;color:#64748b;">
                        No locations found.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:14px;">
        {{ $locations->links() }}
    </div>

@endsection

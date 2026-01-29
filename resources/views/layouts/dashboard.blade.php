<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}"
      dir="{{ in_array(app()->getLocale(), ['ar','fa','ur']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>Kandura Dashboard - @yield('title','Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root{
            --bg:#f2f7ff;
            --card:#ffffff;
            --text:#0f172a;
            --muted:#64748b;
            --border: rgba(15,23,42,.10);

            --primary:#2563eb;
            --primary-600:#1d4ed8;
            --primary-soft: rgba(37,99,235,.10);
            --primary-border: rgba(37,99,235,.22);

            --accent:#d4a017;
            --accent-soft: rgba(212,160,23,.14);

            --shadow: 0 14px 40px rgba(15,23,42,.08);
            --shadow2: 0 10px 24px rgba(15,23,42,.06);
            --radius: 18px;
        }

        *{box-sizing:border-box}
        body{
            margin:0;
            font-family: system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
            background:
                radial-gradient(1000px 600px at 10% 0%, rgba(37,99,235,.12), transparent 55%),
                radial-gradient(1000px 600px at 90% 100%, rgba(37,99,235,.08), transparent 60%),
                linear-gradient(180deg, #ffffff, var(--bg));
            color: var(--text);
            min-height: 100vh;
            display:flex;
        }

        /* ✅ Sidebar أكبر */
        .sidebar{
            width: 310px;
            background: var(--card);
            border-inline-end: 1px solid var(--border);
            padding: 18px 14px;
            position: sticky;
            top:0;
            height: 100vh;
        }

        .brand{
            display:flex; align-items:center; gap:12px;
            padding: 10px 10px 16px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 12px;
        }

        .brand-badge{
            width: 46px; height:46px;
            border-radius: 16px;
            display:grid; place-items:center;
            font-weight: 900;
            background:
                radial-gradient(12px 12px at 25% 25%, var(--accent-soft), transparent 60%),
                linear-gradient(135deg, rgba(37,99,235,.12), rgba(37,99,235,.22));
            border: 1px solid var(--primary-border);
            color: var(--primary);
            font-size: 16px;
        }

        .brand-title{font-weight:900; line-height:1.1; font-size:15px;}
        .brand-sub{font-size:12.5px; color: var(--muted); font-weight:700;}

        .nav{ margin-top: 12px; }

        /* ✅ عناصر القائمة أكبر */
        .nav a{
            display:flex; align-items:center; justify-content:space-between;
            padding: 12px 14px;
            font-size: 14.5px;
            font-weight: 900;
            color: var(--text);
            text-decoration:none;
            border-radius: 16px;
            border: 1px solid transparent;
            margin-bottom: 8px;
            transition: background .12s ease, border-color .12s ease, transform .08s ease;
        }
        .nav a:hover{
            background: rgba(15,23,42,.03);
            transform: translateX(2px);
        }
        .nav a.active{
            background: var(--primary-soft);
            border-color: var(--primary-border);
        }
        .nav a.active span{ color: var(--primary); font-weight:900; }
        .nav small{font-size: 12px; color: var(--muted); font-weight:800;}

        .main{flex:1; padding: 18px 20px; min-width:0;}

        .topbar{
            display:flex; align-items:center; justify-content:space-between; gap: 12px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 12px 14px;
            box-shadow: var(--shadow2);
            margin-bottom: 16px;
        }

        .left{
            display:flex; align-items:center; gap: 10px;
            min-width: 0;
        }
        .user-chip{display:flex; flex-direction:column; line-height:1.1; min-width:0;}
        .user-chip b{font-size: 13px}
        .user-chip span{font-size: 12px; color: var(--muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap;}

        .btn{
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--text);
            font-size: 12px;
            padding: 9px 12px;
            cursor:pointer;
            transition: background .12s ease, border-color .12s ease;
        }
        .btn:hover{background: rgba(15,23,42,.03)}

        .content-card{
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px;
            box-shadow: var(--shadow);
        }

        .mobile-toggle{display:none}
        .overlay{display:none}

        @media (max-width: 920px){
            .sidebar{
                position: fixed;
                top:0; bottom:0;
                inset-inline-start: 0;
                transform: translateX(-110%);
                transition: transform .18s ease;
                z-index: 40;
                box-shadow: 0 22px 60px rgba(15,23,42,.18);
            }
            .sidebar.open{transform: translateX(0)}
            .overlay{
                display:none;
                position: fixed;
                inset:0;
                background: rgba(15,23,42,.35);
                z-index: 30;
            }
            .overlay.show{display:block}
            .mobile-toggle{
                display:inline-flex;
                border:1px solid var(--border);
                background:#fff;
                border-radius: 12px;
                padding: 8px 10px;
                cursor:pointer;
            }
            .main{padding: 14px}
        }
    </style>

    @yield('head')
</head>
<body>

<div class="overlay" id="overlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="brand">
        <div class="brand-badge">K</div>
        <div>
            <div class="brand-title">Kandura System</div>
            <div class="brand-sub">Admin Dashboard</div>
        </div>
    </div>

  <nav class="nav">

    {{-- ✅ Welcome: بلا صلاحية --}}
    <a href="{{ route('dashboard.welcome') }}"
       class="{{ request()->routeIs('dashboard.welcome') ? 'active' : '' }}">
        <span>Welcome</span>
        <small>Home</small>
    </a>

    @can('admin.user.manage')
    <a href="{{ route('dashboard.users.index') }}"
       class="{{ request()->routeIs('dashboard.users.*') ? 'active' : '' }}">
        <span>Users</span>
        <small>Manage</small>
    </a>
    @endcan

    @can('admin.address.manage')
    <a href="{{ route('dashboard.addresses.index') }}"
       class="{{ request()->routeIs('dashboard.addresses.*') ? 'active' : '' }}">
        <span>Addresses</span>
        <small>Manage</small>
    </a>
    @endcan

    @can('admin.design.manage')
    <a href="{{ route('dashboard.designs.index') }}"
       class="{{ request()->routeIs('dashboard.designs.*') ? 'active' : '' }}">
        <span>Designs</span>
        <small>Browse</small>
    </a>
    @endcan

    @can('admin.design_option.manage')
    <a href="{{ route('dashboard.design-options.index') }}"
       class="{{ request()->routeIs('dashboard.design-options.*') ? 'active' : '' }}">
        <span>Design Options</span>
        <small>CRUD</small>
    </a>
    @endcan

    @can('admin.wallet.manage')
    <a href="{{ route('dashboard.wallets.index') }}"
       class="{{ request()->routeIs('dashboard.wallets.*') ? 'active' : '' }}">
        <span>Wallets</span>
        <small>Charge</small>
    </a>
    @endcan

    @can('admin.order.manage')
    <a href="{{ route('dashboard.orders.index') }}"
       class="{{ request()->routeIs('dashboard.orders.*') ? 'active' : '' }}">
        <span>Orders</span>
        <small>Manage</small>
    </a>
    @endcan

    @can('admin.coupon.manage')
    <a href="{{ route('dashboard.coupons.index') }}"
       class="{{ request()->routeIs('dashboard.coupons.*') ? 'active' : '' }}">
        <span>Coupons</span>
        <small>Discounts</small>
    </a>
    @endcan

    @can('admin.invoice.manage')
    <a href="{{ route('dashboard.invoices.index') }}"
       class="{{ request()->routeIs('dashboard.invoices.*') ? 'active' : '' }}">
        <span>Invoices</span>
        <small>Show</small>
    </a>
    @endcan

    @can('admin.review.manage')
    <a href="{{ route('dashboard.reviews.index') }}"
       class="{{ request()->routeIs('dashboard.reviews.*') ? 'active' : '' }}">
        <span>Reviews</span>
        <small>Show</small>
    </a>
    @endcan


    @php
        $showSuper = auth()->check() && auth()->user()->can('admin.superadmin.permission.manage');
    @endphp

    @if($showSuper)
        <div style="margin:12px 10px 6px; opacity:.75; font-weight:900; font-size:12px;">
            SUPERADMIN
        </div>

        <a href="{{ route('dashboard.superadmin.permissions.index') }}"
           class="{{ request()->routeIs('dashboard.superadmin.permissions.*') ? 'active' : '' }}">
            <span>Permissions</span>
            <small>Manage</small>
        </a>

        @can('admin.superadmin.role.manage')
        <a href="{{ route('dashboard.superadmin.roles.index') }}"
           class="{{ request()->routeIs('dashboard.superadmin.roles.*') ? 'active' : '' }}">
            <span>Roles</span>
            <small>Assign</small>
        </a>
        @endcan

        @can('admin.superadmin.admin.manage')
        <a href="{{ route('dashboard.superadmin.admins.index') }}"
           class="{{ request()->routeIs('dashboard.superadmin.admins.*') ? 'active' : '' }}">
            <span>Admins</span>
            <small>Create</small>
        </a>
        @endcan
    @endif

</nav>


</aside>

<main class="main">
    <div class="topbar">
        <div class="left">
            <button class="mobile-toggle" type="button" id="toggleSidebar">☰</button>
            <div class="user-chip">
                <b>{{ auth()->user()->name ?? 'Guest' }}</b>
                <span>{{ auth()->user()->email ?? '' }}</span>
            </div>
        </div>

        @auth
            <form method="POST" action="{{ route('dashboard.logout') }}">
                @csrf
                <button type="submit" class="btn">Logout</button>
            </form>
        @endauth
    </div>

    <div class="content-card">
        @yield('content')
    </div>
</main>

<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    const toggle  = document.getElementById('toggleSidebar');

    function closeSidebar(){
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    }
    function openSidebar(){
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }

    if (toggle) toggle.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });
    if (overlay) overlay.addEventListener('click', closeSidebar);
</script>

@yield('scripts')
</body>
</html>

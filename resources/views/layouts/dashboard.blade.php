<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}"
      dir="{{ in_array(app()->getLocale(), ['ar','fa','ur']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>Kandura Dashboard - @yield('title','Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        /* Notifications Bell (Premium) */
        .btn-accent{
  background: rgba(212,160,23,.16);
  border: 1px solid rgba(212,160,23,.55);
  font-weight: 900;
}
.btn-accent:hover{
  background: rgba(212,160,23,.22);
}
.topbar-right{
  display:flex;
  align-items:center;
  gap:10px;
}

.bell-btn{
    position:relative;
    width:46px;
    height:46px;
    border-radius:18px;
    display:grid;
    place-items:center;
    text-decoration:none;

    /* Yellow premium look */
    background: radial-gradient(18px 18px at 30% 25%, rgba(212,160,23,.35), transparent 60%),
                linear-gradient(180deg, rgba(212,160,23,.22), rgba(212,160,23,.10));
    border: 1px solid rgba(212,160,23,.55);
    box-shadow: 0 14px 30px rgba(15,23,42,.10);

    /* move: right + up */
    margin-inline-start: 50px;   /* زيحة يمين */
    transform: translateY(-8px); /* رفع لفوق */

    transition: transform .12s ease, filter .12s ease, background .12s ease;
}

.bell-btn:hover{
    filter: brightness(1.03);
    transform: translateY(-5px); /* hover يرفع شوي */
}

.bell-icon{
    width:24px;
    height:24px;
    color: #854d0e; /* بني/ذهبي غامق */
    filter: drop-shadow(0 2px 6px rgba(133,77,14,.18));
}

.bell-badge{
    position:absolute;
    top:-7px;
    right:-7px;
    min-width:22px;
    height:22px;
    padding:0 6px;
    border-radius:999px;
    background:#dc2626;
    color:#fff;
    font-size:11px;
    font-weight:900;
    line-height:22px;
    text-align:center;
    border:2px solid #fff;
    box-shadow: 0 10px 18px rgba(15,23,42,.14);
}

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
    <meta name="csrf-token" content="{{ csrf_token() }}">

@php
  $firebaseWeb = config('services.fcm_web', []);
  $firebasePayload = [
    'apiKey' => $firebaseWeb['api_key'] ?? null,
    'authDomain' => $firebaseWeb['auth_domain'] ?? null,
    'projectId' => $firebaseWeb['project_id'] ?? null,
    'storageBucket' => $firebaseWeb['storage_bucket'] ?? null,
    'messagingSenderId' => $firebaseWeb['messaging_sender_id'] ?? null,
    'appId' => $firebaseWeb['app_id'] ?? null,
    'vapidKey' => $firebaseWeb['vapid_public_key'] ?? null,
  ];
@endphp

<script>
  window.__FIREBASE__ = {!! json_encode($firebasePayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!};
</script>




    @yield('head')
</head>

<body>

<div class="overlay" id="overlay"></div>



<aside class="sidebar" id="sidebar">
    <div class="brand">
    <div class="brand-badge">K</div>

    <div>
        <div class="brand-title">Kandura System</div>

<div style="display:flex; align-items:center; gap:10px; margin-top:2px;">
            <div class="brand-sub">Admin Dashboard</div>

            <a class="bell-btn" href="{{ route('dashboard.notifications.index') }}" title="Notifications">
                {{-- Bell SVG --}}
             <svg class="bell-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <path d="M12 22a2.2 2.2 0 0 0 2.2-2.2H9.8A2.2 2.2 0 0 0 12 22Z" fill="currentColor"/>
    <path d="M18.2 16.8V11a6.2 6.2 0 1 0-12.4 0v5.8l-1.6 1.6v.8h15.6v-.8l-1.6-1.6Z"
          fill="currentColor" opacity=".92"/>
    <path d="M7.4 10.9c0-2.55 2.05-4.6 4.6-4.6"
          stroke="currentColor" stroke-width="1.4" stroke-linecap="round" opacity=".45"/>
</svg>


                @if(($notifSummary['unread'] ?? 0) > 0)
                    <span class="bell-badge">{{ $notifSummary['unread'] }}</span>
                @endif
            </a>
        </div>
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

@can('admin.superadmin.permission.manage')
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
@endcan

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

<div style="display:flex; align-items:center; gap:10px;">
  <span id="pushStatus" style="font-size:12px; font-weight:800; color:#64748b;">
    Notifications: —
  </span>
  <button type="button" class="btn" id="pushBtn">Enable Notifications 🔔</button>
</div>



        @auth
            <form method="POST" action="{{ route('dashboard.logout') }}">
                @csrf
                <button type="submit" class="btn">Logout</button>
            </form>
        @endauth
    </div>
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
<script type="module">
    
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.4/firebase-app.js";
  import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.12.4/firebase-messaging.js";

  const cfg = window.__FIREBASE__ || {};

  const app = initializeApp({
    apiKey: cfg.apiKey,
    authDomain: cfg.authDomain,
    projectId: cfg.projectId,
    storageBucket: cfg.storageBucket,
    messagingSenderId: cfg.messagingSenderId,
    appId: cfg.appId,
  });

  const messaging = getMessaging(app);

 onMessage(messaging, (payload) => {
  console.log("✅ Foreground push received:", payload);

  if (Notification.permission === "granted") {
    new Notification(payload?.notification?.title || "New", {
      body: payload?.notification?.body || "",
      icon: "/favicon.ico",
      data: payload?.data || {},
    });
  }
});


 window.enablePush = async function () {
  try {
    console.log("cfg:", window.__FIREBASE__);

    const cfg = window.__FIREBASE__ || {};
    if (!cfg.vapidKey) throw new Error("Missing VAPID public key");
    if (!("serviceWorker" in navigator)) throw new Error("Service Worker not supported");

    console.log("Notification.permission before:", Notification.permission);

    const perm = await Notification.requestPermission();
    console.log("permission result:", perm);
    if (perm !== "granted") throw new Error("Permission not granted");

    console.log("registering SW...");
    const reg = await navigator.serviceWorker.register("/firebase-messaging-sw.js");
    console.log("SW registered:", reg.scope);

    console.log("getting token...");
    const token = await getToken(messaging, { vapidKey: cfg.vapidKey, serviceWorkerRegistration: reg });
    console.log("FCM token:", token);

    if (!token) throw new Error("No token returned");

    console.log("saving token...");
    const res = await fetch("/dashboard/fcm-tokens", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
      },
      body: JSON.stringify({ token }),
    });

    console.log("save response:", res.status);
    if (!res.ok) throw new Error("Failed to save token");

    console.log("✅ Push enabled");
    return token;
  } catch (e) {
    console.error("❌ enablePush failed:", e);
    throw e;
  }
};
const statusEl = document.getElementById("pushStatus");
const btn = document.getElementById("pushBtn");

function setStatus(text, kind = "muted") {
  if (!statusEl) return;
  statusEl.textContent = `Notifications: ${text}`;

  // لون بسيط حسب الحالة (بدون ما نخرب ستايلك)
  statusEl.style.color =
    kind === "ok" ? "#16a34a" :
    kind === "bad" ? "#dc2626" :
    kind === "warn" ? "#d97706" :
    "#64748b";
}

function setBtn(mode) {
  // mode: "enable" | "disable" | "blocked" | "loading"
  if (!btn) return;

  btn.disabled = false;

  if (mode === "loading") {
    btn.disabled = true;
    btn.textContent = "Working… ⏳";
    return;
  }

  if (mode === "blocked") {
    btn.disabled = true;
    btn.textContent = "Blocked 🧱";
    return;
  }

  if (mode === "disable") {
    btn.textContent = "Disable Notifications 🔕";
    btn.onclick = disablePush;
    return;
  }

  // enable
  btn.textContent = "Enable Notifications 🔔";
  btn.onclick = enablePush;
}

function refreshUI() {
  if (!("Notification" in window)) {
    setStatus("Not supported", "bad");
    setBtn("blocked");
    return;
  }

  if (Notification.permission === "denied") {
    setStatus("Blocked by browser", "bad");
    setBtn("blocked");
    return;
  }

  if (Notification.permission === "granted") {
    setStatus("Enabled", "ok");
    setBtn("disable");
    return;
  }

  setStatus("Disabled", "muted");
  setBtn("enable");
}

async function enablePush() {
  try {
    setBtn("loading");
    setStatus("Requesting permission…", "warn");

    const perm = await Notification.requestPermission();
    if (perm !== "granted") {
      refreshUI();
      return;
    }

   setStatus("Registering…", "warn");
const reg = await navigator.serviceWorker.register("/firebase-messaging-sw.js");

setStatus("Waiting service worker…", "warn");
await navigator.serviceWorker.ready;

setStatus("Getting token…", "warn");
const token = await getToken(messaging, {
  vapidKey: cfg.vapidKey,
  serviceWorkerRegistration: reg,
});


    if (!token) throw new Error("No token");

    setStatus("Saving token…", "warn");
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || "";
    const res = await fetch("/dashboard/fcm-tokens", {
      method: "POST",
      headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrf },
      body: JSON.stringify({ token }),
    });

    if (!res.ok) throw new Error("Save token failed");

    refreshUI();
  } catch (e) {
    console.error(e);
    setStatus("Failed (check console)", "bad");
    setBtn("enable");
  }
}

async function disablePush() {
  try {
    setBtn("loading");
    setStatus("Disabling…", "warn");

    // 1) delete token from browser
    const { deleteToken } = await import("https://www.gstatic.com/firebasejs/10.12.4/firebase-messaging.js");
    await deleteToken(messaging);

    // 2) call server to delete token (THIS currently gives you 405 until you add route)
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || "";
    const res = await fetch("/dashboard/fcm-tokens", {
      method: "DELETE",
      headers: { "X-CSRF-TOKEN": csrf },
    });

    // إذا ما عندك DELETE endpoint حالياً، لا تخرب الدنيا:
    if (!res.ok) {
      console.warn("Server DELETE not available yet:", res.status);
    }

    // 3) optional unregister SW
    const regs = await navigator.serviceWorker.getRegistrations();
    await Promise.all(regs.map(r => r.unregister()));

    // ملاحظة: Permission ما بينشال، بس نحنا بنعتبره Disabled طالما حذفنا token+SW
    setStatus("Disabled", "muted");
    setBtn("enable");
  } catch (e) {
    console.error(e);
    setStatus("Failed to disable", "bad");
    setBtn("disable");
  }
}

document.addEventListener("DOMContentLoaded", refreshUI);

</script>

</body>
</html>

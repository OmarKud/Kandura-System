<!doctype html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Admin Login') - {{ config('app.name', 'Kandura') }}</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    :root{
      --cyan: #4fd1ff;          /* light cyan */
      --cyan-dark:#11b7ef;
      --text:#0f172a;
      --muted:#64748b;
      --border: rgba(15,23,42,.10);
      --bg:#f7fbff;
      --card:#ffffff;
      --shadow: 0 20px 55px rgba(2,8,23,.12);
      --radius: 26px;
    }

    body{
      min-height:100vh;
      background:
        radial-gradient(900px 520px at 15% 20%, rgba(79, 209, 255, .18), transparent 60%),
        radial-gradient(900px 520px at 80% 70%, rgba(17, 183, 239, .10), transparent 60%),
        var(--bg);
      color: var(--text);
      overflow-x:hidden;
    }

    /* Top-left brand like the template */
    .topbar-brand{
      position: fixed;
      top: 18px;
      left: 22px;
      display:flex;
      align-items:center;
      gap:12px;
      z-index: 50;
      user-select:none;
    }
    .brand-mark{
      width: 40px; height:40px;
      border-radius: 14px;
      background: rgba(79, 209, 255, .18);
      border: 1px solid rgba(79, 209, 255, .30);
      display:grid;
      place-items:center;
      font-weight: 900;
      color: #0284c7;
      letter-spacing:.5px;
    }
    .brand-lines{ line-height: 1.1; }
    .brand-lines .hello{
      font-weight: 900;
      font-size: 14px;
    }
    .brand-lines .sub{
      font-size: 12px;
      color: var(--muted);
      font-weight: 600;
    }

    .wrap{
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding: 28px 16px;
    }

    .frame{
      width: min(1050px, 100%);
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow:hidden;
      display:grid;
      grid-template-columns: 1.05fr .95fr;
    }

    @media (max-width: 992px){
      .frame{ grid-template-columns: 1fr; }
      .left-pane{ display:none; }
    }

    /* LEFT PANE */
    .left-pane{
      position: relative;
      padding: 22px;
      background:
        radial-gradient(600px 400px at 40% 30%, rgba(79, 209, 255, .18), transparent 65%),
        #0b1220;
    }
    .left-shell{
      height: 100%;
      min-height: 560px;
      border-radius: 22px;
      border: 1px solid rgba(255,255,255,.10);
      overflow:hidden;
      position: relative;
      background: rgba(255,255,255,.04);
    }

    /* The 3 triangles */
    .tri{
      position:absolute;
      inset: 0;
      background-size: cover;
      background-position: center;
      filter: saturate(1.08) contrast(1.02);
    }

    /* Triangle 1 (top-left) */
    .tri.t1{
      clip-path: polygon(0 0, 70% 0, 0 85%);
      background-image: url('{{ asset('assets/images/auth/tri-1.jpg') }}');
      opacity: .95;
    }

    /* Triangle 2 (top-right) */
    .tri.t2{
      clip-path: polygon(70% 0, 100% 0, 100% 78%, 30% 55%);
      background-image: url('{{ asset('assets/images/auth/tri-2.jpg') }}');
      opacity: .92;
    }

    /* Triangle 3 (bottom) */
    .tri.t3{
      clip-path: polygon(0 85%, 30% 55%, 100% 78%, 100% 100%, 0 100%);
      background-image: url('{{ asset('assets/images/auth/tri-3.jpg') }}');
      opacity: .92;
    }

    /* Left overlay texts */
    .left-top{
      position:absolute;
      top: 16px; left: 16px; right: 16px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      z-index: 5;
      color: rgba(255,255,255,.86);
      font-size: 12px;
      font-weight: 700;
    }
    .pill{
      border: 1px solid rgba(255,255,255,.18);
      background: rgba(255,255,255,.08);
      padding: 7px 12px;
      border-radius: 999px;
      display:flex;
      gap:8px;
      align-items:center;
    }

    .left-bottom{
      position:absolute;
      bottom: 16px; left: 16px; right: 16px;
      z-index: 5;
      color: rgba(255,255,255,.88);
      display:flex;
      justify-content:space-between;
      align-items:flex-end;
      gap:12px;
    }
    .profile{
      display:flex; align-items:center; gap:10px;
    }
    .avatar{
      width: 38px; height: 38px;
      border-radius: 999px;
      background: rgba(79, 209, 255, .22);
      border: 1px solid rgba(79, 209, 255, .30);
      display:grid; place-items:center;
      font-weight: 900;
      color:#e6fbff;
    }
    .profile .name{ font-weight: 900; font-size: 13px; line-height:1.1; }
    .profile .role{ font-size: 12px; opacity:.85; }

    /* RIGHT PANE */
    .right-pane{
      padding: 36px 36px 28px;
    }

    .logo-row{
      display:flex;
      align-items:center;
      justify-content:space-between;
      margin-bottom: 28px;
    }
    .right-brand{
      font-weight: 900;
      letter-spacing: .6px;
      font-size: 16px;
    }
    .lang{
      font-size:12px;
      color: var(--muted);
      padding: 7px 12px;
      border-radius: 999px;
      border: 1px solid var(--border);
      background: #fff;
    }

    .headline{
      margin-top: 6px;
      font-size: 42px;
      font-weight: 900;
      letter-spacing: -.7px;
    }
    .subline{
      margin-top: 6px;
      color: var(--muted);
      font-weight: 600;
      font-size: 13px;
    }

    .admin-tag{
      display:inline-flex;
      align-items:center;
      gap:8px;
      margin-top: 14px;
      padding: 7px 12px;
      border-radius: 999px;
      background: rgba(79,209,255,.16);
      border: 1px solid rgba(79,209,255,.28);
      color: #0369a1;
      font-size: 12px;
      font-weight: 800;
    }

    .form-area{
      margin-top: 22px;
      max-width: 420px;
    }

    .form-control{
      border-radius: 14px;
      border: 1px solid var(--border);
      padding: 12px 14px;
      font-size: 14px;
    }
    .form-control:focus{
      border-color: rgba(79, 209, 255, .60);
      box-shadow: 0 0 0 .25rem rgba(79, 209, 255, .16);
    }

    .btn-cyan{
      border: none;
      border-radius: 999px;
      padding: 12px 16px;
      font-weight: 900;
      background: linear-gradient(135deg, var(--cyan), var(--cyan-dark));
      box-shadow: 0 14px 30px rgba(17, 183, 239, .22);
    }
    .btn-cyan:hover{ filter: brightness(1.02); transform: translateY(-1px); }
    .btn-cyan:active{ transform: translateY(0); }

    .small-link{
      font-size: 12px;
      font-weight: 800;
      color: #0284c7;
      text-decoration:none;
    }

    .divider{
      display:flex;
      align-items:center;
      gap:12px;
      color: var(--muted);
      font-size: 12px;
      margin: 16px 0;
    }
    .divider::before,.divider::after{
      content:"";
      height:1px;
      flex:1;
      background: rgba(15,23,42,.10);
    }
  </style>

  @yield('head')
</head>
<body>

  <div class="topbar-brand">
    <div class="brand-mark">K</div>
    <div class="brand-lines">
      <div class="hello">Hello to Kandura System</div>
      <div class="sub">Admin panel</div>
    </div>
  </div>

  <div class="wrap">
    <div class="frame">
      <div class="left-pane">
        <div class="left-shell">
          <div class="tri t1"></div>
          <div class="tri t2"></div>
          <div class="tri t3"></div>

          <div class="left-top">
            <div class="pill">Selected Works</div>
            <div class="pill">Kandura</div>
          </div>

          <div class="left-bottom">
            <div class="profile">
              <div class="avatar">A</div>
              <div>
                <div class="name">Admin</div>
                <div class="role">Dashboard Access</div>
              </div>
            </div>
            <div class="pill">v1.0</div>
          </div>
        </div>
      </div>

      <div class="right-pane">
        @yield('content')
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @yield('scripts')
</body>
</html>

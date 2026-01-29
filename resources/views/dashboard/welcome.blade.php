@extends('layouts.dashboard')

@section('title', 'Welcome')

@section('content')

  {{-- HERO (BIGGER) --}}
  <div style="
      margin-bottom:18px;
      padding:24px 22px;
      border-radius:18px;
      border:1px solid rgba(37,99,235,.20);
      background:
        radial-gradient(900px 320px at 20% 0%, rgba(37,99,235,.20), transparent 60%),
        linear-gradient(180deg, #ffffff, rgba(37,99,235,.06));
      box-shadow: 0 18px 55px rgba(15,23,42,.10);
      position: relative;
      overflow: hidden;
  ">
      <div style="
          position:absolute;
          top:-70px; right:-70px;
          width:220px; height:220px;
          background: rgba(37,99,235,.10);
          border-radius: 999px;
          pointer-events:none;
      "></div>

      <div style="display:flex;gap:14px;align-items:flex-start;flex-wrap:wrap;position:relative;z-index:1;">
          <div style="
              width:54px;height:54px;border-radius:16px;
              display:grid;place-items:center;
              font-weight:900;
              font-size:18px;
              color:#2563eb;
              border:1px solid rgba(37,99,235,.24);
              background: rgba(37,99,235,.10);
              flex: 0 0 auto;
          ">K</div>

          <div style="min-width:260px;flex:1;">
              <h1 style="margin:0 0 8px 0;font-size:28px;font-weight:900;color:#0f172a;">
                  Welcome in <span style="color:#2563eb;">Kandura System</span> 👋
              </h1>

              <p style="margin:0;color:#64748b;font-size:15px;line-height:1.7;">
                  Admin dashboard to track users, activity, and daily tasks in one place.
              </p>

              <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
                  <span style="
                      display:inline-flex;align-items:center;gap:8px;
                      padding:9px 12px;
                      border-radius:999px;
                      border:1px solid rgba(15,23,42,.10);
                      background:#fff;
                      font-size:12.5px;
                      color:#0f172a;
                      box-shadow: 0 8px 16px rgba(15,23,42,.06);
                  ">
                      📅 Today:
                      <b style="color:#2563eb;">{{ now()->format('Y-m-d') }}</b>
                  </span>

                  <span style="
                      display:inline-flex;align-items:center;gap:8px;
                      padding:9px 12px;
                      border-radius:999px;
                      border:1px solid rgba(15,23,42,.10);
                      background:#fff;
                      font-size:12.5px;
                      color:#0f172a;
                      box-shadow: 0 8px 16px rgba(15,23,42,.06);
                  ">
                      ✅ Active:
                      <b style="color:#16a34a;">{{ $totalActiveUsers }}</b>
                      <span style="color:#cbd5e1;">|</span>
                      ❌ Inactive:
                      <b style="color:#dc2626;">{{ $totalInactiveUsers }}</b>
                  </span>

                  <span style="
                      display:inline-flex;align-items:center;gap:8px;
                      padding:9px 12px;
                      border-radius:999px;
                      border:1px solid rgba(37,99,235,.18);
                      background: rgba(37,99,235,.08);
                      font-size:12.5px;
                      color:#2563eb;
                      box-shadow: 0 8px 16px rgba(15,23,42,.06);
                  ">
                      🚀 New this month:
                      <b>{{ $newUsersThisMonth }}</b>
                  </span>
              </div>
          </div>
      </div>
  </div>


  {{-- STATS + SCHEDULE --}}
  <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:18px;align-items:stretch;">

      {{-- USER CARDS (BIGGER + COLORS) --}}
      <div style="display:flex;flex-wrap:wrap;gap:14px;flex:1;min-width:320px;align-items:stretch;">

          {{-- Total --}}
          <div style="
              width: 260px; min-width: 230px;
              background:#ffffff;
              border-radius:18px;
              padding:18px;
              border:1px solid rgba(15,23,42,.10);
              box-shadow: 0 16px 50px rgba(15,23,42,.08);
          ">
              <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                  <div style="font-size:13px;color:#64748b;font-weight:800;">Total Users</div>
                  <span style="
                      font-size:11.5px;
                      padding:6px 10px;
                      border-radius:999px;
                      background: rgba(37,99,235,.10);
                      color:#2563eb;
                      border:1px solid rgba(37,99,235,.18);
                      font-weight:800;
                  ">Overview</span>
              </div>

              <div style="font-size:38px;font-weight:900;color:#0f172a;line-height:1;">
                  {{ $totalUsers }}
              </div>

              <div style="font-size:13px;color:#64748b;margin-top:10px;line-height:1.6;">
                  Active: <b style="color:#0f172a;">{{ $totalActiveUsers }}</b>
                  <span style="color:#cbd5e1;">|</span>
                  Inactive: <b style="color:#0f172a;">{{ $totalInactiveUsers }}</b>
              </div>
          </div>

          {{-- Normal Users (Cyan) --}}
          <div style="
              width: 220px; min-width: 210px;
              background: linear-gradient(180deg, rgba(79,209,255,.22), #fff);
              border-radius:18px;
              padding:18px;
              border:1px solid rgba(79,209,255,.40);
              box-shadow: 0 16px 50px rgba(15,23,42,.08);
          ">
              <div style="font-size:13px;color:#0369a1;font-weight:900;margin-bottom:10px;">
                  Users (Normal)
              </div>
              <div style="font-size:34px;font-weight:900;color:#0f172a;line-height:1;">
                  {{ $totalNormalUsers }}
              </div>
              <div style="font-size:12.5px;color:#0f172a;margin-top:10px;opacity:.75;">
                  <b>user</b>
              </div>
          </div>

          {{-- Admins (Gray) --}}
          <div style="
              width: 220px; min-width: 210px;
              background: linear-gradient(180deg, rgba(148,163,184,.25), #fff);
              border-radius:18px;
              padding:18px;
              border:1px solid rgba(148,163,184,.55);
              box-shadow: 0 16px 50px rgba(15,23,42,.08);
          ">
              <div style="font-size:13px;color:#334155;font-weight:900;margin-bottom:10px;">
                  Admins
              </div>
              <div style="font-size:34px;font-weight:900;color:#0f172a;line-height:1;">
                  {{ $totalAdmins }}
              </div>
              <div style="font-size:12.5px;color:#0f172a;margin-top:10px;opacity:.75;">
                   <b>admin</b>
              </div>
          </div>

          {{-- Super Admins (Yellow) --}}
          <div style="
              width: 220px; min-width: 210px;
              background: linear-gradient(180deg, rgba(250,204,21,.24), #fff);
              border-radius:18px;
              padding:18px;
              border:1px solid rgba(250,204,21,.55);
              box-shadow: 0 16px 50px rgba(15,23,42,.08);
          ">
              <div style="font-size:13px;color:#854d0e;font-weight:900;margin-bottom:10px;">
                  welcome 
              </div>
             
              <div style="font-size:12.5px;color:#0f172a;margin-top:10px;opacity:.75;">
                  <b>admin dashboard</b>
              </div>
          </div>

      </div>

      {{-- Today Schedule (BIGGER) --}}
      <div style="
          flex: 1;
          min-width: 320px;
          background:#ffffff;
          border-radius:18px;
          padding:18px;
          border:1px solid rgba(15,23,42,.10);
          box-shadow: 0 16px 50px rgba(15,23,42,.08);
      ">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
              <h3 style="margin:0;font-size:16px;color:#0f172a;font-weight:900;">Today Schedule</h3>
              <span style="font-size:12.5px;color:#64748b;font-weight:700;">Tasks</span>
          </div>

          <ul style="list-style:none;padding:0;margin:0;">
              @forelse($schedule as $item)
                  <li style="
                      padding:12px 12px;
                      border:1px solid rgba(15,23,42,.08);
                      background: rgba(37,99,235,.04);
                      border-radius:16px;
                      margin-bottom:10px;
                  ">
                      <div style="font-size:12px;color:#2563eb;font-weight:900;margin-bottom:5px;">
                          {{ $item['time'] }}
                      </div>
                      <div style="font-size:14px;color:#0f172a;font-weight:700;">
                          {{ $item['title'] }}
                      </div>
                  </li>
              @empty
                  <li style="font-size:14px;color:#64748b;">No schedule items today.</li>
              @endforelse
          </ul>
      </div>
  </div>


  {{-- CHART + RECENT USERS --}}
  <div style="display:flex;flex-wrap:wrap;gap:14px;align-items:flex-start;">

      {{-- Chart (BIGGER) --}}
      <div style="
          flex:1;
          min-width:340px;
          background:#ffffff;
          border-radius:18px;
          padding:18px;
          border:1px solid rgba(15,23,42,.10);
          box-shadow: 0 16px 50px rgba(15,23,42,.08);
      ">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
              <h3 style="margin:0;font-size:16px;color:#0f172a;font-weight:900;">Users per Month</h3>
              <span style="font-size:12.5px;color:#64748b;font-weight:700;">Last 6 months</span>
          </div>

          <div style="
              border:1px solid rgba(15,23,42,.08);
              background: linear-gradient(180deg, rgba(37,99,235,.06), #fff);
              border-radius:16px;
              padding:12px;
          ">
              <canvas id="usersChart" style="max-height:270px;"></canvas>
          </div>

          @if(empty($chartLabels))
              <div style="margin-top:10px;font-size:13px;color:#64748b;">
                  No data yet for chart.
              </div>
          @endif
      </div>

      {{-- Recent Users (BIGGER) --}}
      <div style="
          width:360px;
          max-width:100%;
          background:#ffffff;
          border-radius:18px;
          padding:18px;
          border:1px solid rgba(15,23,42,.10);
          box-shadow: 0 16px 50px rgba(15,23,42,.08);
      ">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
              <h3 style="margin:0;font-size:16px;color:#0f172a;font-weight:900;">Recent Users</h3>
              <span style="
                  font-size:11.5px;
                  padding:6px 10px;
                  border-radius:999px;
                  background: rgba(37,99,235,.10);
                  color:#2563eb;
                  border:1px solid rgba(37,99,235,.18);
                  font-weight:900;
              ">Latest</span>
          </div>

          <ul style="list-style:none;padding:0;margin:0;">
              @forelse($recentUsers as $u)
                  <li style="
                      padding:12px 12px;
                      border:1px solid rgba(15,23,42,.08);
                      border-radius:16px;
                      margin-bottom:10px;
                      background:#fff;
                  ">
                      <div style="font-size:14px;color:#0f172a;font-weight:900;">
                          {{ $u->name }}
                      </div>
                      <div style="color:#64748b;font-size:12.5px;margin-top:3px;">
                          {{ $u->email }} • {{ $u->created_at?->format('Y-m-d') }}
                      </div>
                  </li>
              @empty
                  <li style="font-size:14px;color:#64748b;">No users yet.</li>
              @endforelse
          </ul>
      </div>
  </div>


  {{-- Chart.js --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    (function () {
      const labels = @json($chartLabels);
      const data = @json($chartData);

      let finalLabels = labels;
      let finalData = data;

      if (!finalLabels || finalLabels.length === 0) {
        finalLabels = ['No data'];
        finalData = [0];
      }

      const canvas = document.getElementById('usersChart');
      if (!canvas) return;

      const ctx = canvas.getContext('2d');

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: finalLabels,
          datasets: [{
            label: 'Users',
            data: finalData,
            tension: 0.35,
            borderColor: '#2563eb',
            borderWidth: 2,
            pointRadius: 3,
            pointBackgroundColor: '#2563eb',
          }]
        },
        options: {
          plugins: {
            legend: {
              labels: { color: '#0f172a', font: { size: 12 } }
            }
          },
          scales: {
            x: {
              ticks: { color: '#64748b', font: { size: 12 } },
              grid: { display: false }
            },
            y: {
              ticks: { color: '#64748b', font: { size: 12 }, stepSize: 1 },
              grid: { color: 'rgba(15,23,42,.08)' }
            }
          }
        }
      });
    })();
  </script>

@endsection

@extends('layouts.dashboard')
@section('title','Reviews')

@section('head')
<style>
  .wrap{max-width:1100px;margin:0 auto}
  .card{background:#fff;border:1px solid rgba(15,23,42,.12);border-radius:16px;box-shadow:0 10px 30px rgba(2,6,23,.06);overflow:hidden}
  .head{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:14px;border-bottom:1px solid rgba(15,23,42,.08)}
  .head h2{margin:0;font-size:18px;font-weight:900}
  .muted{color:#64748b;font-weight:800;font-size:12px}
  .filters{display:flex;gap:8px;flex-wrap:wrap}
  .in{height:38px;border-radius:10px;border:1px solid rgba(15,23,42,.14);padding:0 10px;font-weight:800}
  .btn{height:38px;padding:0 14px;border-radius:999px;border:1px solid rgba(15,23,42,.14);background:rgba(37,99,235,.10);font-weight:900;text-decoration:none;color:#0f172a;display:inline-flex;align-items:center;justify-content:center}
  .btn.ghost{background:#fff}
  table{width:100%;border-collapse:collapse}
  th,td{padding:12px 14px;border-bottom:1px solid rgba(15,23,42,.06);text-align:left;vertical-align:top}
  th{background:rgba(37,99,235,.06);font-size:12px;color:#475569}
  td{font-size:13px}

  .userCell{display:flex;align-items:center;gap:10px}
  .avatar{
    width:34px;height:34px;border-radius:999px;
    display:flex;align-items:center;justify-content:center;
    background:rgba(37,99,235,.10);
    border:1px solid rgba(37,99,235,.18);
    font-weight:900;
  }
  .userMeta{display:flex;flex-direction:column;line-height:1.2}
  .userMeta b{font-weight:900}
  .userMeta span{font-size:12px;color:#64748b;font-weight:800}

  .stars{display:flex;gap:3px;align-items:center}
  .star{font-size:16px;line-height:1}
  .star.on{color:#f59e0b}
  .star.off{color:rgba(15,23,42,.18)}
  .pill{display:inline-block;padding:4px 10px;border-radius:999px;background:#fff;border:1px solid rgba(15,23,42,.12);font-weight:900;font-size:12px}

  .comment{max-width:420px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-weight:800}
</style>
@endsection

@section('content')
<div class="wrap">
  <div class="card">
    <div class="head">
      <div>
        <h2>Reviews</h2>
        <div class="muted">List reviews</div>
      </div>

      <form class="filters" method="GET" action="{{ route('dashboard.reviews.index') }}">
        <input class="in" type="text" name="q" value="{{ $q }}" placeholder="Search by user or comment">

        <select class="in" name="rating">
          <option value="">All ratings</option>
          @for($i=5;$i>=1;$i--)
            <option value="{{ $i }}" @selected((string)$rating===(string)$i)>{{ $i }} stars</option>
          @endfor
        </select>

        <select class="in" name="per_page">
          @foreach([15,25,50,100] as $n)
            <option value="{{ $n }}" @selected((int)$perPage===$n)>{{ $n }}/page</option>
          @endforeach
        </select>

        <button class="btn" type="submit">Apply</button>
        <a class="btn ghost" href="{{ route('dashboard.reviews.index') }}">Reset</a>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th>User</th>
          <th>Order Number</th>
          <th>Rating</th>
          <th>Comment</th>
          <th>Created</th>
        </tr>
      </thead>

      <tbody>
        @forelse($reviews as $rev)
          @php
            $u = $rev->user;
            $name = $u?->name ?: 'Unknown';
            $initial = mb_strtoupper(mb_substr($name, 0, 1));
            $ratingVal = (int)($rev->rating ?? 0);
          @endphp

          <tr>
            <td>
              <div class="userCell">
                <div class="avatar">{{ $initial }}</div>
                <div class="userMeta">
                  <b>{{ $name }}</b>
                  <span>
                    @if(!empty($u?->email)) {{ $u->email }} @endif
                    @if(!empty($u?->phone)) • {{ $u->phone }} @endif
                  </span>
                </div>
              </div>
            </td>

            {{-- طلبك: عرض order_id لكن تسميته Order Number --}}
            <td><span class="pill">#{{ $rev->order_id }}</span></td>

            <td>
              <div style="display:flex;gap:10px;align-items:center">
                <div class="stars" aria-label="rating">
                  @for($i=1;$i<=5;$i++)
                    <span class="star {{ $i <= $ratingVal ? 'on' : 'off' }}">★</span>
                  @endfor
                </div>
                <span class="pill">{{ $ratingVal }}/5</span>
              </div>
            </td>

            <td class="comment">{{ $rev->comment ?: '— No comment —' }}</td>

            <td>{{ optional($rev->created_at)->format('Y-m-d H:i') }}</td>
          </tr>

        @empty
          <tr>
            <td colspan="5" class="muted" style="text-align:center;padding:18px">
              No reviews found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div style="padding:12px 14px">
      {{ $reviews->links() }}
    </div>
  </div>
</div>
@endsection

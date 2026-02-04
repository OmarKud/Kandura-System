@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')

<div style="
    margin-bottom:18px;
    background:#ffffff;
    border-radius:18px;
    padding:18px;
    border:1px solid rgba(15,23,42,.10);
    box-shadow: 0 16px 50px rgba(15,23,42,.08);
">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="
                width:44px;height:44px;border-radius:14px;
                display:grid;place-items:center;
                background:rgba(37,99,235,.10);
                border:1px solid rgba(37,99,235,.18);
                color:#2563eb;
                font-weight:900;
            ">🔔</div>

            <div>
                <div style="font-size:18px;font-weight:900;color:#0f172a;line-height:1.1;">Notifications</div>
                <div style="font-size:12.5px;color:#64748b;">
                    Unread: <b style="color:#0f172a;">{{ $summary['unread'] ?? 0 }}</b>
                    <span style="color:#cbd5e1;">|</span>
                    Total: <b style="color:#0f172a;">{{ $summary['total'] ?? 0 }}</b>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            @if(session('status'))
                <span style="font-size:12.5px;color:#16a34a;font-weight:900;">{{ session('status') }}</span>
            @endif

            <a href="{{ route('dashboard.notifications.index', ['filter' => 'all']) }}"
               style="padding:8px 12px;border-radius:999px;border:1px solid rgba(15,23,42,.10);background:#fff;font-size:12.5px;font-weight:900;color:#0f172a;text-decoration:none;">
                All
            </a>
            <a href="{{ route('dashboard.notifications.index', ['filter' => 'unread']) }}"
               style="padding:8px 12px;border-radius:999px;border:1px solid rgba(37,99,235,.18);background:rgba(37,99,235,.08);font-size:12.5px;font-weight:900;color:#2563eb;text-decoration:none;">
                Unread ({{ $summary['unread'] ?? 0 }})
            </a>
            <a href="{{ route('dashboard.notifications.index', ['filter' => 'read']) }}"
               style="padding:8px 12px;border-radius:999px;border:1px solid rgba(15,23,42,.10);background:#fff;font-size:12.5px;font-weight:900;color:#0f172a;text-decoration:none;">
                Read
            </a>

            <form method="POST" action="{{ route('dashboard.notifications.markAllRead') }}">
                @csrf
                <button type="submit" style="
                    padding:9px 12px;border-radius:999px;border:1px solid rgba(15,23,42,.10);
                    background:#0f172a;color:#fff;font-size:12.5px;font-weight:900;cursor:pointer;
                ">
                    Mark all read
                </button>
            </form>
        </div>
    </div>
</div>

<div style="
    background:#ffffff;
    border-radius:18px;
    border:1px solid rgba(15,23,42,.10);
    box-shadow: 0 16px 50px rgba(15,23,42,.08);
    overflow:hidden;
">
    @forelse($notifications as $n)
        @php
            $isRead = !is_null($n->read_at);
            $data = $n->data ?? [];
        @endphp

        <div style="
            padding:16px 18px;
            border-bottom:1px solid rgba(15,23,42,.08);
            background: {{ $isRead ? '#fff' : 'rgba(37,99,235,.05)' }};
            display:flex;justify-content:space-between;gap:12px;align-items:flex-start;
        ">
            <a href="{{ route('dashboard.notifications.open', $n->id) }}"
               style="text-decoration:none;min-width:0;flex:1;color:inherit;">
                <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <div style="font-size:14.5px;font-weight:900;color:#0f172a;">
                        {{ $data['title'] ?? 'Notification' }}
                    </div>
                    <span style="
                        font-size:11.5px;padding:5px 10px;border-radius:999px;font-weight:900;
                        border:1px solid rgba(15,23,42,.10);
                        background: {{ $isRead ? 'rgba(148,163,184,.20)' : 'rgba(37,99,235,.12)' }};
                        color: {{ $isRead ? '#334155' : '#2563eb' }};
                    ">
                        {{ $isRead ? 'Read' : 'Unread' }}
                    </span>
                </div>

                <div style="margin-top:6px;font-size:13px;color:#334155;line-height:1.6;">
                    {{ $data['body'] ?? '' }}
                </div>

                <div style="margin-top:10px;font-size:12px;color:#64748b;">
                    {{ $n->created_at?->toDateString() }} • {{ $n->created_at?->format('H:i') }}
                    @if(!empty($data['type']))
                        <span style="color:#cbd5e1;">|</span> {{ $data['type'] }}
                    @endif
                </div>
            </a>

            <div style="display:flex;gap:8px;align-items:center;flex:0 0 auto;">
                @if(!$isRead)
                    <form method="POST" action="{{ route('dashboard.notifications.markRead', $n->id) }}">
                        @csrf
                        <button type="submit" style="
                            padding:8px 12px;border-radius:12px;border:1px solid rgba(15,23,42,.10);
                            background:#fff;font-size:12.5px;font-weight:900;cursor:pointer;
                        ">
                            Mark read
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div style="padding:20px;color:#64748b;">
            No notifications yet.
        </div>
    @endforelse
</div>

@if(method_exists($notifications, 'links'))
    <div style="margin-top:12px;">
        {{ $notifications->links() }}
    </div>
@endif

@endsection

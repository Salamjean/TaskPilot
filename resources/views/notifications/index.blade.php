@extends(auth()->user()->role === 'admin' ? 'admin.layouts.app' : 'personnel.layouts.app')

@section('page-title', 'Toutes les notifications')

@section('content')
    <style>
        .notif-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, .05);
            margin-bottom: 24px;
            overflow: hidden;
        }

        .notif-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #F9FAFB;
        }

        .notif-header h2 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: var(--text);
        }

        .notif-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid #F3F4F6;
            text-decoration: none;
            transition: background .15s;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item:hover {
            background: #F9FAFB;
        }

        .notif-item.unread {
            background: #EFF6FF;
        }

        .notif-item.unread:hover {
            background: #E0F2FE;
        }

        .notif-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .notif-icon svg {
            width: 20px;
            height: 20px;
            color: var(--secondary);
        }

        .notif-body {
            flex: 1;
        }

        .notif-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }

        .notif-message {
            font-size: .88rem;
            color: #4B5563;
            line-height: 1.5;
        }

        .notif-time {
            font-size: .75rem;
            color: #9CA3AF;
            margin-top: 8px;
            font-weight: 500;
        }

        .btn-mark {
            background: none;
            border: none;
            font-size: .85rem;
            font-weight: 600;
            color: var(--secondary);
            cursor: pointer;
            padding: 0;
        }

        .btn-mark:hover {
            text-decoration: underline;
        }

        .empty-state {
            padding: 60px 24px;
            text-align: center;
        }

        .empty-state svg {
            width: 48px;
            height: 48px;
            color: #D1D5DB;
            margin-bottom: 16px;
        }

        .empty-state p {
            color: #6B7280;
            font-size: .95rem;
        }

        /* Pagination Styling */
        .pagination-wrapper {
            padding: 20px 24px;
            background: #F9FAFB;
            border-top: 1px solid var(--border);
        }
    </style>

    <div class="container-fluid">
        <div class="notif-card">
            <div class="notif-header">
                <h2>Mes Notifications</h2>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <form method="POST" action="{{ route('notifications.markAllRead') }}">
                        @csrf
                        <button type="submit" class="btn-mark">Tout marquer comme lu</button>
                    </form>
                @endif
            </div>

            <div class="notif-list">
                @forelse($notifications as $notification)
                    <a href="{{ $notification->data['link'] ?? '#' }}"
                        class="notif-item {{ $notification->unread() ? 'unread' : '' }}">
                        <div class="notif-icon">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="notif-body">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div class="notif-title">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                @if($notification->unread())
                                    <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}"
                                        style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn-mark" title="Marquer comme lu"
                                            onclick="event.stopPropagation();">
                                            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="notif-message">{{ $notification->data['message'] ?? '' }}</div>
                            <div class="notif-time">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <p>Vous n'avez pas encore de notifications.</p>
                    </div>
                @endforelse
            </div>

            @if($notifications->hasPages())
                <div class="pagination-wrapper">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
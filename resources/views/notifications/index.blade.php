<x-layouts.app title="Сповіщення">
    @push('styles')
    <style>
        /* ── Layout ── */
        .layout {
            display: grid;
            grid-template-columns: 1fr 360px;
            gap: 1.5rem;
            align-items: start;
        }

        @media (max-width: 768px) {
            .layout { grid-template-columns: 1fr; }
        }

        /* ── Section titles ── */
        .section-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        /* ── Users list ── */
        .users-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .user-row {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0.9rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: border-color 0.2s;
        }

        .user-row:hover { border-color: var(--accent); }

        .avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem; color: #fff;
            flex-shrink: 0;
        }

        .user-info { flex: 1; min-width: 0; }

        .user-name {
            font-weight: 600;
            color: var(--text);
            font-size: 0.9rem;
        }

        .user-email {
            font-size: 0.75rem;
            color: var(--text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Send form ── */
        .send-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .send-input {
            flex: 1;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-size: 0.85rem;
            padding: 0.5rem 0.8rem;
            font-family: inherit;
            transition: border-color 0.2s;
            min-width: 0;
        }

        .send-input:focus { outline: none; border-color: var(--accent); }

        .send-btn {
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .send-btn:hover { background: var(--accent-hover); }
        .send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

        /* ── Sent flash ── */
        .sent-flash {
            font-size: 0.75rem;
            color: var(--green);
            margin-left: 0.3rem;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .sent-flash.show { opacity: 1; }

        /* ── Notifications panel ── */
        .notif-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            position: sticky;
            top: 72px;
        }

        .notif-header {
            padding: 0.9rem 1.2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notif-header-left {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .notif-count {
            background: var(--accent);
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.1rem 0.45rem;
            border-radius: 99px;
            min-width: 18px;
            text-align: center;
        }

        .notif-count[data-count="0"] { display: none; }

        .mark-all-btn {
            font-size: 0.75rem;
            color: var(--text-muted);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }

        .mark-all-btn:hover { color: var(--accent); }

        .notif-list {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .notif-list::-webkit-scrollbar { width: 3px; }
        .notif-list::-webkit-scrollbar-thumb { background: var(--border); }

        .notif-item {
            padding: 0.9rem 1.2rem;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            transition: background 0.15s;
            animation: slideIn 0.2s ease;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(10px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: var(--surface2); }

        .notif-item.reading-out {
            opacity: 0;
            transform: translateX(20px);
            transition: opacity 0.25s, transform 0.25s;
        }

        .notif-sender {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 0.25rem;
        }

        .notif-text {
            font-size: 0.875rem;
            color: var(--text);
            line-height: 1.4;
            word-break: break-word;
        }

        .notif-time {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-top: 0.3rem;
        }

        .notif-empty {
            padding: 2.5rem 1.2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .notif-hint {
            font-size: 0.7rem;
            color: var(--text-muted);
            opacity: 0.6;
            margin-top: 0.3rem;
        }

        /* ── WS indicator in nav ── */
        .ws-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: var(--text-muted);
            display: inline-block;
            margin-right: 0.3rem;
            transition: background 0.3s;
        }
        .ws-dot.connected { background: var(--green); }
    </style>
    @endpush

    <div class="layout">

        {{-- ── LEFT: send notifications ──────────────────────────────── --}}
        <div>
            <p class="section-title">Користувачі — надіслати сповіщення</p>

            @if($users->isEmpty())
                <div style="color:var(--text-muted);font-size:.9rem;padding:2rem 0;">
                    Поки що немає інших користувачів
                </div>
            @else
                <div class="users-list">
                    @foreach($users as $u)
                        <div class="user-row">
                            <div class="avatar">{{ strtoupper(substr($u->name, 0, 1)) }}</div>
                            <div class="user-info">
                                <div class="user-name">{{ $u->name }}</div>
                                <div class="user-email">{{ $u->email }}</div>
                            </div>
                            <div class="send-form">
                                <input
                                    type="text"
                                    class="send-input"
                                    placeholder="Текст..."
                                    data-user-id="{{ $u->id }}"
                                    data-send-url="{{ route('notifications.send', $u->id) }}"
                                    maxlength="500"
                                >
                                <button class="send-btn" data-user-id="{{ $u->id }}">Надіслати</button>
                                <span class="sent-flash" data-user-id="{{ $u->id }}">✓</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── RIGHT: unread notifications ────────────────────────────── --}}
        <div>
            <div class="notif-panel">
                <div class="notif-header">
                    <div class="notif-header-left">
                        <span class="ws-dot" id="wsDot"></span>
                        Сповіщення
                        <span class="notif-count" id="notifCount" data-count="{{ $unreadNotifications->count() }}">
                            {{ $unreadNotifications->count() }}
                        </span>
                    </div>
                    <button class="mark-all-btn" id="markAllBtn">Прочитати всі</button>
                </div>

                <div class="notif-list" id="notifList">
                    @forelse($unreadNotifications as $notif)
                        <div class="notif-item" data-id="{{ $notif->id }}" data-read-url="{{ route('notifications.read', $notif) }}">
                            <div class="notif-sender">{{ $notif->sender->name }}</div>
                            <div class="notif-text">{{ $notif->text }}</div>
                            <div class="notif-time">{{ $notif->created_at->format('d.m H:i') }}</div>
                        </div>
                    @empty
                        <div class="notif-empty" id="notifEmpty">
                            Немає нових сповіщень
                            <div class="notif-hint">Клікніть на сповіщення щоб відмітити прочитаним</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
        const CURRENT_USER_ID = {{ auth()->id() }};
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const MARK_ALL_URL = "{{ route('notifications.read-all') }}";
    </script>
    @vite(['resources/js/app.js', 'resources/js/notifications.js'])
    @endpush
</x-layouts.app>

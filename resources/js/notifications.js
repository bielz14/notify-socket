/**
 * notifications.js
 *
 * Flow:
 * 1. Subscribe to private-notifications.{userId}
 * 2. On new notification — prepend to panel, update counter
 * 3. Click notification — mark as read via POST, animate out
 * 4. Send form — POST text to server, server dispatches event → Reverb → receiver
 */

const notifList  = document.getElementById('notifList');
const notifCount = document.getElementById('notifCount');
const notifEmpty = document.getElementById('notifEmpty');
const wsDot      = document.getElementById('wsDot');
const markAllBtn = document.getElementById('markAllBtn');
const CSRF       = document.querySelector('meta[name="csrf-token"]').content;

// ── Counter helpers ──────────────────────────────────────────────────────────
function getCount() {
    return parseInt(notifCount.dataset.count || '0', 10);
}

function setCount(n) {
    notifCount.dataset.count = n;
    notifCount.textContent = n;
    if (n === 0) {
        notifCount.style.display = 'none';
        showEmpty();
    } else {
        notifCount.style.display = '';
    }
}

function showEmpty() {
    if (!notifList.querySelector('.notif-item')) {
        if (!notifEmpty) {
            const div = document.createElement('div');
            div.className = 'notif-empty';
            div.id = 'notifEmpty';
            div.innerHTML = 'Немає нових сповіщень<div class="notif-hint">Клікніть на сповіщення щоб відмітити прочитаним</div>';
            notifList.appendChild(div);
        }
    }
}

// ── Render new notification ──────────────────────────────────────────────────
function renderNotification({ id, sender_name, text, created_at, readUrl }) {
    // Remove empty state
    const empty = document.getElementById('notifEmpty');
    if (empty) empty.remove();

    const item = document.createElement('div');
    item.className = 'notif-item';
    item.dataset.id = id;
    item.dataset.readUrl = readUrl || '';
    item.innerHTML = `
        <div class="notif-sender">${esc(sender_name)}</div>
        <div class="notif-text">${esc(text)}</div>
        <div class="notif-time">${esc(created_at)}</div>
    `;

    // Prepend — newest on top
    notifList.insertBefore(item, notifList.firstChild);
    setCount(getCount() + 1);
    bindMarkRead(item);
}

// ── Mark single notification as read ────────────────────────────────────────
function bindMarkRead(item) {
    item.addEventListener('click', async () => {
        const url = item.dataset.readUrl;
        if (!url) return;

        item.classList.add('reading-out');

        await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        });

        setTimeout(() => {
            item.remove();
            setCount(Math.max(0, getCount() - 1));
        }, 260);
    });
}

// Bind existing notifications on page load
document.querySelectorAll('.notif-item').forEach(bindMarkRead);

// ── Mark all as read ─────────────────────────────────────────────────────────
markAllBtn.addEventListener('click', async () => {
    await fetch(MARK_ALL_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    });

    document.querySelectorAll('.notif-item').forEach(item => {
        item.classList.add('reading-out');
        setTimeout(() => item.remove(), 260);
    });

    setTimeout(() => {
        setCount(0);
    }, 300);
});

// ── WebSocket subscription ───────────────────────────────────────────────────
window.Echo.private(`notifications.${CURRENT_USER_ID}`)
    .listen('.notification.received', (e) => {
        renderNotification({
            id:          e.id,
            sender_name: e.sender_name,
            text:        e.text,
            created_at:  e.created_at,
            readUrl:     `/notifications/${e.id}/read`,
        });
    })
    .subscribed(() => {
        wsDot.classList.add('connected');
    })
    .error(() => {
        wsDot.classList.remove('connected');
    });

// ── Send notification forms ──────────────────────────────────────────────────
document.querySelectorAll('.send-btn').forEach(btn => {
    btn.addEventListener('click', () => sendNotification(btn.dataset.userId));
});

document.querySelectorAll('.send-input').forEach(input => {
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') sendNotification(input.dataset.userId);
    });
});

async function sendNotification(userId) {
    const input   = document.querySelector(`.send-input[data-user-id="${userId}"]`);
    const btn     = document.querySelector(`.send-btn[data-user-id="${userId}"]`);
    const flash   = document.querySelector(`.sent-flash[data-user-id="${userId}"]`);
    const url     = input.dataset.sendUrl;
    const text    = input.value.trim();

    if (!text) return;

    btn.disabled = true;

    try {
        await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ text }),
        });

        input.value = '';
        flash.classList.add('show');
        setTimeout(() => flash.classList.remove('show'), 2000);
    } catch (err) {
        console.error('Send error:', err);
    } finally {
        btn.disabled = false;
        input.focus();
    }
}

function esc(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

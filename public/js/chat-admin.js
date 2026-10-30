'use strict';
/* Chat phía admin – AJAX polling */
(function () {
    const BASE = (window.APP && window.APP.base) || '';
    const userList = document.getElementById('userList');
    const msgs = document.getElementById('adminMsgs');
    const title = document.getElementById('chatTitle');
    const input = document.getElementById('adminInput');
    const sendBtn = document.getElementById('adminSend');
    let current = null, lastId = 0, users = window.CHAT_USERS || [];
    const seen = new Set();
    const hdr = { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };

    function renderUsers() {
        userList.innerHTML = '';
        if (!users.length) { userList.innerHTML = '<div class="p-4 text-muted text-center">Chưa có khách hàng nào nhắn tin.</div>'; return; }
        users.forEach(u => {
            const el = document.createElement('div');
            el.className = 'chat-user p-3 border-bottom d-flex justify-content-between align-items-center' + (u.username === current ? ' active' : '');
            const left = document.createElement('div');
            left.style.minWidth = 0;
            const n = document.createElement('div'); n.className = 'fw-bold'; n.textContent = u.name;
            const l = document.createElement('div'); l.className = 'small text-muted text-truncate'; l.textContent = u.last || '';
            left.append(n, l);
            el.appendChild(left);
            if (u.unread > 0 && u.username !== current) {
                const b = document.createElement('span'); b.className = 'badge bg-danger rounded-pill'; b.textContent = u.unread; el.appendChild(b);
            }
            el.addEventListener('click', () => openChat(u));
            userList.appendChild(el);
        });
    }

    function addMessage(m) {
        if (seen.has(m.id)) return;
        seen.add(m.id); lastId = Math.max(lastId, m.id);
        const d = document.createElement('div');
        d.className = 'bubble ' + (m.mine ? 'me' : 'them');
        d.textContent = m.content;
        msgs.appendChild(d);
        msgs.scrollTop = msgs.scrollHeight;
    }

    function openChat(u) {
        current = u.username; lastId = 0; seen.clear(); msgs.innerHTML = '';
        title.textContent = 'Đang chat với: ' + u.name;
        input.disabled = false; sendBtn.disabled = false; input.focus();
        u.unread = 0; renderUsers(); pollMessages();
    }

    function pollMessages() {
        if (!current) return;
        const who = current;
        fetch(BASE + '/admin/chat/messages?username=' + encodeURIComponent(who) + '&after=' + lastId, { headers: hdr })
            .then(r => r.json()).then(list => { if (who === current && Array.isArray(list)) list.forEach(addMessage); }).catch(() => {});
    }

    function pollUsers() {
        fetch(BASE + '/admin/chat/users', { headers: hdr }).then(r => r.json()).then(list => {
            if (Array.isArray(list)) { users = list.map(u => u.username === current ? Object.assign(u, { unread: 0 }) : u); renderUsers(); }
        }).catch(() => {});
    }

    function send() {
        const text = input.value.trim();
        if (!text || !current) return;
        input.value = '';
        fetch(BASE + '/admin/chat/send', {
            method: 'POST', headers: Object.assign({ 'X-CSRF-Token': window.APP.csrf }, hdr),
            body: new URLSearchParams({ username: current, content: text })
        }).then(r => r.json()).then(m => { if (m && m.success) addMessage(m); }).catch(() => { input.value = text; });
    }

    sendBtn.addEventListener('click', send);
    input.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); send(); } });
    renderUsers();
    setInterval(pollMessages, 2500);
    setInterval(pollUsers, 5000);
})();

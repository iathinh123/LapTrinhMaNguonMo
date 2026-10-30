'use strict';
/* Chat khách hàng ↔ admin. Thay WebSocket/STOMP của bản Java bằng AJAX polling (3 giây/lần) để chạy được trên Apache + PHP (Laragon). */
(function () {
    const chatButton = document.getElementById('chat-button');
    const chatMenu = document.getElementById('chat-menu');
    const chatPopup = document.getElementById('chat-popup');
    const closeChat = document.getElementById('close-chat');
    const input = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-message');
    const area = document.getElementById('message-area');
    const openBtn = document.getElementById('open-live-chat');
    if (!chatButton) return;

    const BASE = (window.APP && window.APP.base) || '';
    let lastId = 0, timer = null, loaded = false;
    const seen = new Set();

    chatButton.addEventListener('click', () => {
        if (chatPopup && chatPopup.style.display === 'flex') { chatPopup.style.display = 'none'; stopPolling(); }
        else if (chatMenu) chatMenu.style.display = chatMenu.style.display === 'flex' ? 'none' : 'flex';
    });

    if (openBtn) openBtn.addEventListener('click', e => {
        e.preventDefault();
        chatMenu.style.display = 'none';
        chatPopup.style.display = 'flex';
        input.focus();
        poll();
        startPolling();
    });
    if (closeChat) closeChat.addEventListener('click', () => { chatPopup.style.display = 'none'; stopPolling(); });
    if (!input || !sendBtn) return;

    function startPolling() { stopPolling(); timer = setInterval(poll, 3000); }
    function stopPolling() { if (timer) clearInterval(timer); timer = null; }

    function addMessage(m) {
        if (seen.has(m.id)) return;
        seen.add(m.id);
        lastId = Math.max(lastId, m.id);
        const div = document.createElement('div');
        div.className = 'message ' + (m.mine ? 'message-customer' : 'message-admin');
        const p = document.createElement('p');
        p.className = 'mb-0';
        p.textContent = m.content;          // textContent: chống XSS
        div.appendChild(p);
        area.appendChild(div);
        area.scrollTop = area.scrollHeight;
    }

    function poll() {
        fetch(BASE + '/chat/messages?after=' + lastId, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.ok ? r.json() : [])
            .then(list => { if (Array.isArray(list)) list.forEach(addMessage); loaded = true; })
            .catch(() => {});
    }

    function send() {
        const text = input.value.trim();
        if (!text) return;
        input.value = '';
        const body = new URLSearchParams({ content: text });
        fetch(BASE + '/chat/send', {
            method: 'POST',
            headers: { 'X-CSRF-Token': window.APP.csrf, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: body
        }).then(r => r.json()).then(m => { if (m && m.success) addMessage(m); }).catch(() => { input.value = text; });
    }

    sendBtn.addEventListener('click', send);
    input.addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); send(); } });
})();

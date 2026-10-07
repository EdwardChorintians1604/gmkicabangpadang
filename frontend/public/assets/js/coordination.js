document.addEventListener('DOMContentLoaded', function () {
    const chat = document.querySelector('.coordination-chat');
    const messages = document.getElementById('coordinationMessages');
    const form = document.getElementById('coordinationForm');
    if (!chat || !messages || !form) return;

    const textarea = form.querySelector('textarea[name="body"]');
    const error = document.getElementById('coordinationError');
    const liveStatus = chat.querySelector('.coordination-live');
    const olderButton = document.getElementById('coordinationLoadOlder');
    const roleNames = {
        ketcab: 'Ketua Cabang',
        sekcab: 'Sekretaris Cabang',
        bencab: 'Bendahara Cabang',
        sekfung_medko: 'Sekfung Medko',
        admin: 'Sekfung Medko'
    };
    let latestId = Number(chat.dataset.latestId) || 0;
    let oldestId = Number(chat.dataset.oldestId) || 0;

    function createMessage(message) {
        const article = document.createElement('article');
        const own = Number(message.sender_id) === Number(form.dataset.userId);
        article.className = 'coordination-message ' + (own ? 'outgoing' : 'incoming');
        article.dataset.messageId = String(message.id);

        if (!own) {
            const sender = document.createElement('strong');
            sender.className = 'coordination-sender';
            sender.textContent = message.sender_name + ' · ' + (roleNames[message.sender_role] || message.sender_role);
            article.appendChild(sender);
        }

        const body = document.createElement('p');
        body.textContent = message.body;
        article.appendChild(body);

        const time = document.createElement('time');
        time.dateTime = message.created_at;
        const sentAt = new Date(message.created_at.replace(' ', 'T'));
        time.textContent = Number.isNaN(sentAt.getTime())
            ? ''
            : new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit' }).format(sentAt);
        article.appendChild(time);

        return article;
    }

    function appendMessage(message) {
        const shouldScroll = Number(message.sender_id) === Number(form.dataset.userId)
            || messages.scrollHeight - messages.scrollTop - messages.clientHeight < 100;
        const empty = document.getElementById('coordinationEmpty');
        if (empty) empty.remove();

        messages.appendChild(createMessage(message));
        latestId = Math.max(latestId, Number(message.id) || 0);
        if (shouldScroll) messages.scrollTop = messages.scrollHeight;
    }

    messages.scrollTop = messages.scrollHeight;
    olderButton.addEventListener('click', async function () {
        if (!oldestId) return;
        olderButton.disabled = true;
        try {
            const oldHeight = messages.scrollHeight;
            const query = new URLSearchParams({ before: String(oldestId) });
            const response = await fetch(chat.dataset.messagesUrl + '?' + query.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error('Pesan sebelumnya gagal dimuat.');
            const result = await response.json();
            if (result.messages.length) {
                const fragment = document.createDocumentFragment();
                result.messages.forEach(function (message) {
                    fragment.appendChild(createMessage(message));
                });
                const firstMessage = messages.querySelector('.coordination-message');
                messages.insertBefore(fragment, firstMessage || null);
                oldestId = Number(result.messages[0].id) || oldestId;
                messages.scrollTop += messages.scrollHeight - oldHeight;
            }
            olderButton.hidden = !result.has_more;
            chat.dataset.hasOlder = result.has_more ? 'true' : 'false';
        } catch (exception) {
            liveStatus.textContent = exception.message || 'Koneksi chat terputus.';
            liveStatus.classList.add('offline');
        } finally {
            olderButton.disabled = false;
        }
    });

    textarea.addEventListener('input', function () {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
    });

    textarea.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            form.requestSubmit();
        }
    });

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        error.hidden = true;
        const submitButton = form.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            const result = await response.json();
            if (!response.ok || !result.message) throw new Error(result.error || 'Pesan gagal dikirim.');
            appendMessage(result.message);
            form.reset();
            textarea.style.height = '';
            textarea.focus();
        } catch (exception) {
            error.textContent = exception.message || 'Koneksi bermasalah. Pesan belum terkirim.';
            error.hidden = false;
        } finally {
            submitButton.disabled = false;
        }
    });

    async function refreshMessages() {
        if (document.hidden) return;
        const query = new URLSearchParams({ after: String(latestId) });
        try {
            const response = await fetch(chat.dataset.messagesUrl + '?' + query.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error('Koneksi chat terputus.');
            const result = await response.json();
            result.messages.forEach(appendMessage);
            liveStatus.textContent = 'Terhubung';
            liveStatus.classList.remove('offline');
        } catch {
            liveStatus.textContent = 'Menyambungkan...';
            liveStatus.classList.add('offline');
        }
    }

    window.setInterval(refreshMessages, 10000);
});

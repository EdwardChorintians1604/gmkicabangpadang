document.addEventListener('DOMContentLoaded', function () {
    const chat = document.querySelector('.coordination-chat');
    const messages = document.getElementById('coordinationMessages');
    const form = document.getElementById('coordinationForm');
    if (!chat || !messages || !form) return;

    const textarea = form.querySelector('textarea[name="body"]');
    const error = document.getElementById('coordinationError');
    const liveStatus = document.getElementById('coordinationLiveStatus') || chat.querySelector('.coordination-live');
    const olderButton = document.getElementById('coordinationLoadOlder');
    const soundToggle = document.getElementById('coordinationSoundToggle');
    const soundIcon = document.getElementById('soundIcon');
    const notifyToggle = document.getElementById('coordinationNotifyToggle');
    const notifyIcon = document.getElementById('notifyIcon');
    const notifyText = document.getElementById('notifyText');

    const roleNames = {
        ketcab: 'Ketua Cabang',
        sekcab: 'Sekretaris Cabang',
        bencab: 'Bendahara Cabang',
        sekfung_medko: 'Sekfung Medko',
        admin: 'Administrator'
    };

    let latestId = Number(chat.dataset.latestId) || 0;
    let oldestId = Number(chat.dataset.oldestId) || 0;
    const currentUserId = Number(form.dataset.userId) || 0;

    // Scan initial messages for highest latestId and oldestId
    const existingMsgs = messages.querySelectorAll('.coordination-message');
    existingMsgs.forEach(function (el) {
        const id = Number(el.dataset.messageId);
        if (id) {
            if (id > latestId) latestId = id;
            if (!oldestId || id < oldestId) oldestId = id;
        }
    });

    // --- Audio Notification Setup (Web Audio API) ---
    let soundEnabled = localStorage.getItem('gmki_chat_sound') !== 'false';
    let audioCtx = null;

    function updateSoundUi() {
        if (soundIcon) soundIcon.textContent = soundEnabled ? '🔊' : '🔇';
        if (soundToggle) {
            soundToggle.classList.toggle('muted', !soundEnabled);
            soundToggle.title = soundEnabled ? 'Suara Aktif (Klik untuk Mematikan)' : 'Suara Senyap (Klik untuk Menyalakan)';
        }
    }
    updateSoundUi();

    if (soundToggle) {
        soundToggle.addEventListener('click', function () {
            soundEnabled = !soundEnabled;
            localStorage.setItem('gmki_chat_sound', soundEnabled ? 'true' : 'false');
            updateSoundUi();
            if (soundEnabled) playIncomingTone();
        });
    }

    function playIncomingTone() {
        if (!soundEnabled) return;
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            if (!audioCtx) audioCtx = new AudioContext();
            if (audioCtx.state === 'suspended') audioCtx.resume();

            const now = audioCtx.currentTime;
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            osc.type = 'sine';
            // Two harmonic frequencies: G5 (784Hz) then E6 (1318Hz) for a friendly WhatsApp-like tone
            osc.frequency.setValueAtTime(784, now);
            osc.frequency.exponentialRampToValueAtTime(1318, now + 0.08);

            gain.gain.setValueAtTime(0.2, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.3);

            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start(now);
            osc.stop(now + 0.3);
        } catch (e) {
            // Audio autoplay policy fallback
        }
    }

    // --- Desktop Notification Setup ---
    function updateNotifyUi() {
        if (!('Notification' in window) || !notifyToggle) return;
        if (Notification.permission === 'granted') {
            if (notifyIcon) notifyIcon.textContent = '🔔';
            if (notifyText) notifyText.textContent = 'Notif Aktif';
            notifyToggle.classList.add('active');
            notifyToggle.title = 'Notifikasi Desktop Aktif';
        } else if (Notification.permission === 'denied') {
            if (notifyIcon) notifyIcon.textContent = '🔕';
            if (notifyText) notifyText.textContent = 'Diblokir';
            notifyToggle.classList.remove('active');
            notifyToggle.title = 'Izin Notifikasi Diblokir di Browser';
        } else {
            if (notifyIcon) notifyIcon.textContent = '🔔';
            if (notifyText) notifyText.textContent = 'Notifikasi';
            notifyToggle.classList.remove('active');
            notifyToggle.title = 'Klik untuk Mengaktifkan Notifikasi Popup';
        }
    }
    updateNotifyUi();

    if (notifyToggle) {
        notifyToggle.addEventListener('click', async function () {
            if (!('Notification' in window)) {
                alert('Browser ini tidak mendukung Web Notifications.');
                return;
            }
            if (Notification.permission === 'default') {
                const perm = await Notification.requestPermission();
                updateNotifyUi();
                if (perm === 'granted') {
                    showDesktopNotification('GMKI Cabang Padang', 'Notifikasi chat koordinasi berhasil diaktifkan!');
                }
            } else if (Notification.permission === 'denied') {
                alert('Izin notifikasi diblokir pada peramban Anda. Silakan ubah izin situs di ikon gembok bilah URL.');
            }
        });
    }

    function showDesktopNotification(senderName, bodyText) {
        if (!('Notification' in window) || Notification.permission !== 'granted') return;
        try {
            const notif = new Notification(senderName + ' · BPC GMKI', {
                body: bodyText.length > 80 ? bodyText.substring(0, 80) + '...' : bodyText,
                icon: '/assets/images/GMKI-Logos.png',
                tag: 'gmki-coordination-message',
                renotify: true
            });
            notif.onclick = function () {
                window.focus();
                this.close();
            };
        } catch (e) {}
    }

    // --- Title Blinker & Unread Badge ---
    let unreadCount = 0;
    const baseTitle = document.title;
    let blinkTimer = null;

    function renderTitle() {
        if (document.hidden && unreadCount > 0) {
            if (!blinkTimer) {
                let flip = false;
                blinkTimer = window.setInterval(function () {
                    flip = !flip;
                    document.title = flip
                        ? `💬 (${unreadCount}) Pesan Baru! - Ruang Koordinasi`
                        : `🟢 Chat Koordinasi BPC - GMKI Padang`;
                }, 1200);
            }
        } else {
            if (blinkTimer) {
                clearInterval(blinkTimer);
                blinkTimer = null;
            }
            unreadCount = 0;
            document.title = baseTitle;
        }
    }

    document.addEventListener('visibilitychange', renderTitle);
    window.addEventListener('focus', renderTitle);

    // --- Messages Rendering & Handling ---
    function createMessage(message) {
        const article = document.createElement('article');
        const own = Number(message.sender_id) === currentUserId;
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

    function appendMessage(message, isPollingIncoming = false) {
        const isOwn = Number(message.sender_id) === currentUserId;
        const nearBottom = messages.scrollHeight - messages.scrollTop - messages.clientHeight < 120;
        const shouldScroll = isOwn || nearBottom;

        const empty = document.getElementById('coordinationEmpty');
        if (empty) empty.remove();

        messages.appendChild(createMessage(message));
        latestId = Math.max(latestId, Number(message.id) || 0);

        if (shouldScroll) {
            messages.scrollTop = messages.scrollHeight;
        }

        // Notification triggers for incoming message from other members
        if (isPollingIncoming && !isOwn) {
            playIncomingTone();
            if (document.hidden) {
                unreadCount++;
                renderTitle();
                const senderTitle = (message.sender_name || 'Pengurus') + ' (' + (roleNames[message.sender_role] || message.sender_role) + ')';
                showDesktopNotification(senderTitle, message.body);
            }
        }
    }

    messages.scrollTop = messages.scrollHeight;

    // Load older messages
    if (olderButton) {
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
                if (result.messages && result.messages.length) {
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
                setLiveStatus(false, exception.message || 'Koneksi terputus');
            } finally {
                olderButton.disabled = false;
            }
        });
    }

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
        const textValue = textarea.value.trim();
        if (!textValue) return;

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
            appendMessage(result.message, false);
            form.reset();
            textarea.style.height = '';
            textarea.focus();
            setLiveStatus(true);
            // Quick check after sending
            refreshMessages();
        } catch (exception) {
            error.textContent = exception.message || 'Koneksi bermasalah. Pesan belum terkirim.';
            error.hidden = false;
        } finally {
            submitButton.disabled = false;
        }
    });

    function setLiveStatus(isOnline, label = null) {
        if (!liveStatus) return;
        const labelEl = liveStatus.querySelector('.coordination-live-label') || liveStatus;
        if (isOnline) {
            labelEl.textContent = label || 'Real-time';
            liveStatus.classList.remove('offline');
        } else {
            labelEl.textContent = label || 'Menyambungkan...';
            liveStatus.classList.add('offline');
        }
    }

    let isFetching = false;
    async function refreshMessages() {
        if (isFetching) return;
        isFetching = true;
        const query = new URLSearchParams({ after: String(latestId) });
        try {
            const response = await fetch(chat.dataset.messagesUrl + '?' + query.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error('Koneksi chat terputus.');
            const result = await response.json();
            if (result.messages && result.messages.length > 0) {
                result.messages.forEach(function (msg) {
                    appendMessage(msg, true);
                });
            }
            setLiveStatus(true);
        } catch {
            setLiveStatus(false);
        } finally {
            isFetching = false;
        }
    }

    // Adaptive Real-Time Polling:
    // Tab active: every 2.5s (2500ms) for snappy WhatsApp feel
    // Tab background: every 4s (4000ms) so background sound and notifications work
    let pollInterval = null;
    function startPolling() {
        if (pollInterval) clearInterval(pollInterval);
        const delay = document.hidden ? 4000 : 2500;
        pollInterval = window.setInterval(refreshMessages, delay);
    }

    document.addEventListener('visibilitychange', function () {
        startPolling();
        if (!document.hidden) {
            refreshMessages();
        }
    });

    startPolling();
});

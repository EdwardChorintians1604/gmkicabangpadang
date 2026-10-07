/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: frontend/public/assets/js/session-guard.js
 * Deskripsi: Perlindungan Sesi Klien, Pencegahan Pembajakan Sesi Tertinggal,
 *            Pembersihan Otomatis Saat Browser/Tab Ditutup, & Idle Timeout.
 * =====================================================================
 */

(function () {
    'use strict';

    // Konfigurasi Keamanan Sesi
    const IDLE_TIMEOUT_MS = 15 * 60 * 1000; // 15 menit inaktivitas otomatis logout
    const STORAGE_MARKER_KEY = 'gmki_session_tab_active';
    const LAST_ACTIVITY_KEY = 'gmki_last_activity_ts';
    const BROADCAST_LOGOUT_KEY = 'gmki_global_logout_event';

    const authUserId = window.__AUTH_USER_ID__ || null;

    // 1. Jika pengguna berstatus GUEST (tidak login)
    if (!authUserId) {
        sessionStorage.removeItem(STORAGE_MARKER_KEY);
        return;
    }

    // 2. DETEKSI SESI TERTINGGAL SETELAH TAB / BROWSER DITUTUP:
    // Browser modern otomatis menghapus sessionStorage saat tab/jendela ditutup.
    // Jika server masih memiliki sesi login aktif tapi sessionStorage kosong dan tidak ada flag login baru,
    // berarti pengguna pernah keluar/menutup browser tanpa menekan tombol logout!
    const activeMarker = sessionStorage.getItem(STORAGE_MARKER_KEY);
    const justLoggedIn = sessionStorage.getItem('gmki_just_logged_in');

    if (!activeMarker) {
        if (justLoggedIn) {
            // Baru saja login secara sah dari form login
            sessionStorage.removeItem('gmki_just_logged_in');
            sessionStorage.setItem(STORAGE_MARKER_KEY, String(authUserId));
        } else {
            // Sesi tertinggal terdeteksi! Reset sesi di server sekarang juga agar tidak dimanfaatkan hacker.
            console.warn('[Keamanan GMKI] Browser/tab sebelumnya ditutup tanpa logout. Mereset sesi otomatis.');
            
            try {
                if (navigator.sendBeacon) {
                    navigator.sendBeacon('/logout-auto');
                }
            } catch (e) {}

            fetch('/logout-auto', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).catch(function () {
                return fetch('/logout-auto');
            }).finally(function () {
                sessionStorage.clear();
                window.location.reload();
            });
            return;
        }
    }

    // 3. PENCATATAN AKTIVITAS PENGGUNA (IDLE TIMEOUT)
    function recordActivity() {
        localStorage.setItem(LAST_ACTIVITY_KEY, Date.now().toString());
    }

    recordActivity();

    ['mousedown', 'keydown', 'scroll', 'touchstart'].forEach(function (evt) {
        window.addEventListener(evt, recordActivity, { passive: true });
    });

    // 4. SINKRONISASI LOGOUT ANTAR TAB BROWSER
    window.addEventListener('storage', function (e) {
        if (e.key === BROADCAST_LOGOUT_KEY && e.newValue) {
            sessionStorage.clear();
            window.location.href = '/login?msg=logged_out';
        }
    });

    // 5. PENGAWASAN WAKTU INAKTIVITAS (SETIAP 15 DETIK)
    setInterval(function () {
        const lastActive = parseInt(localStorage.getItem(LAST_ACTIVITY_KEY) || Date.now().toString(), 10);
        const elapsed = Date.now() - lastActive;

        if (elapsed >= IDLE_TIMEOUT_MS) {
            sessionStorage.clear();
            localStorage.setItem(BROADCAST_LOGOUT_KEY, Date.now().toString());

            fetch('/logout-auto', { method: 'POST' }).finally(function () {
                alert('Sesi Anda telah direset secara otomatis demi keamanan karena tidak ada aktivitas selama 15 menit.');
                window.location.href = '/login?timeout=1';
            });
        }
    }, 15000);
})();

/**
 * GMKI Cabang Padang - App JavaScript
 * app.js
 */

// Global Sidebar Drawer Controller (Shared by all dashboard layouts)
window.initSidebarDrawer = function () {
    if (window._sidebarDrawerInitialized) return;
    
    const toggles = document.querySelectorAll('.sidebar-mobile-toggle, .sidebar-toggle-btn');
    const sidebar = document.querySelector('.dashboard-sidebar');
    const backdrop = document.getElementById('sidebarBackdrop') || document.querySelector('.sidebar-backdrop');
    const closeButtons = document.querySelectorAll('.sidebar-close-btn, #adminSidebarCloseBtn, #sidebarCloseBtn');

    if (!sidebar || toggles.length === 0) return;
    window._sidebarDrawerInitialized = true;

    function openSidebar() {
        sidebar.classList.add('show');
        if (backdrop) backdrop.classList.add('active');
        document.body.classList.add('sidebar-open');
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        if (backdrop) backdrop.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    }

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (sidebar.classList.contains('show')) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    });

    closeButtons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
        });
    });

    if (backdrop) {
        backdrop.addEventListener('click', function (e) {
            e.preventDefault();
            closeSidebar();
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && sidebar.classList.contains('show')) {
            closeSidebar();
        }
    });

    // Close when clicking outside sidebar on mobile
    document.addEventListener('click', function (e) {
        if (window.innerWidth <= 900 && sidebar.classList.contains('show')) {
            let isClickOnToggle = false;
            toggles.forEach(function (t) {
                if (t.contains(e.target)) isClickOnToggle = true;
            });
            if (!sidebar.contains(e.target) && !isClickOnToggle) {
                closeSidebar();
            }
        }
    });
};

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Navigation Toggle (Public)
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.toggle('open');
        });
    }

    // 2. Sidebar Mobile Drawer Toggle (Dashboard & Admin)
    window.initSidebarDrawer();

    // 3. Flash Alert Dismiss Button
    const alertCloses = document.querySelectorAll('.alert-close');
    alertCloses.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const alertBox = btn.closest('.alert');
            if (alertBox) {
                alertBox.style.opacity = '0';
                setTimeout(() => alertBox.remove(), 250);
            }
        });
    });

    // 4. Confirmation Dialogs for Destructive Actions
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(function (el) {
        el.addEventListener('click', function (e) {
            const msg = el.getAttribute('data-confirm') || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            if (!confirm(msg)) {
                e.preventDefault();
            }
        });
    });

    // 5. Image Preview Helper
    const imageInputs = document.querySelectorAll('input[type="file"][data-preview]');
    imageInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            const targetId = input.getAttribute('data-preview');
            const previewEl = document.getElementById(targetId);
            if (previewEl && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewEl.src = e.target.result;
                    previewEl.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    });
});

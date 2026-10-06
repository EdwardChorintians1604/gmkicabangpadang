/**
 * GMKI Cabang Padang - App JavaScript
 * app.js
 */

// Global Sidebar Drawer Controller (Shared by all dashboard layouts)
// Global Sidebar Drawer & Collapse Controller (Shared by all dashboard layouts)
window.initSidebarDrawer = function () {
    if (window._sidebarDrawerInitialized) return;

    const toggles = document.querySelectorAll('.navbar-sidebar-toggle, .sidebar-mobile-toggle, .sidebar-toggle-btn');
    const sidebar = document.querySelector('.dashboard-sidebar');
    const backdrop = document.getElementById('sidebarBackdrop') || document.querySelector('.sidebar-backdrop');
    const closeButtons = document.querySelectorAll('.sidebar-close-btn, #adminSidebarCloseBtn, #pengawasSidebarCloseBtn, #sidebarCloseBtn');

    if (!sidebar || toggles.length === 0) return;
    window._sidebarDrawerInitialized = true;

    const isDesktop = function () {
        return window.innerWidth > 900;
    };

    // Restore desktop collapsed state on initial load
    try {
        if (isDesktop() && localStorage.getItem('gmki_sidebar_collapsed') === 'true') {
            document.body.classList.add('sidebar-collapsed');
        }
    } catch (err) {}

    function openMobileSidebar() {
        sidebar.classList.add('show');
        if (backdrop) backdrop.classList.add('active');
        document.body.classList.add('sidebar-open');
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('show');
        if (backdrop) backdrop.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    }

    function toggleDesktopSidebar() {
        const isCollapsed = document.body.classList.toggle('sidebar-collapsed');
        try {
            localStorage.setItem('gmki_sidebar_collapsed', isCollapsed ? 'true' : 'false');
        } catch (err) {}
    }

    function handleToggle(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        if (isDesktop()) {
            toggleDesktopSidebar();
        } else {
            if (sidebar.classList.contains('show')) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        }
    }

    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', handleToggle);
    });

    closeButtons.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (isDesktop()) {
                document.body.classList.add('sidebar-collapsed');
                try { localStorage.setItem('gmki_sidebar_collapsed', 'true'); } catch (err) {}
            } else {
                closeMobileSidebar();
            }
        });
    });

    if (backdrop) {
        backdrop.addEventListener('click', function (e) {
            e.preventDefault();
            closeMobileSidebar();
        });
    }

    document.addEventListener('keydown', function (e) {
        // ESC closes mobile sidebar
        if (e.key === 'Escape' && sidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
        // Ctrl+B / Cmd+B toggles sidebar
        if ((e.ctrlKey || e.metaKey) && (e.key === 'b' || e.key === 'B')) {
            e.preventDefault();
            handleToggle();
        }
    });

    // Close when clicking outside sidebar on mobile
    document.addEventListener('click', function (e) {
        if (!isDesktop() && sidebar.classList.contains('show')) {
            let isClickOnToggle = false;
            toggles.forEach(function (t) {
                if (t.contains(e.target)) isClickOnToggle = true;
            });
            if (!sidebar.contains(e.target) && !isClickOnToggle) {
                closeMobileSidebar();
            }
        }
    });

    // Handle screen resize
    window.addEventListener('resize', function () {
        if (isDesktop() && sidebar.classList.contains('show')) {
            closeMobileSidebar();
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

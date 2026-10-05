/**
 * GMKI Cabang Padang - App JavaScript
 * app.js
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Navigation Toggle (Public)
    const navToggle = document.querySelector('.nav-toggle');
    const navMenu = document.querySelector('.nav-menu');
    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.toggle('open');
        });
    }

    // 2. Sidebar Mobile Drawer Toggle (Dashboard)
    const sidebarToggle = document.querySelector('.sidebar-toggle-btn');
    const sidebar = document.querySelector('.dashboard-sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });

        // Close sidebar if clicked outside on mobile
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 900 && 
                sidebar.classList.contains('show') && 
                !sidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    }

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

/**
 * =====================================================================
 * GMKI CABANG PADANG - SISTEM INFORMASI & MANAJEMEN KEANGGOTAAN
 * File: frontend/public/assets/js/admin.js
 * Deskripsi: Skrip Antarmuka Admin & Pengawas (Sidebar, Tabs, Preview, Confirm)
 * =====================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const mobileToggle = document.querySelector('.sidebar-mobile-toggle');
    const sidebar = document.querySelector('.dashboard-sidebar');

    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
        });

        // Close sidebar when clicking outside on small screens
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 900 && sidebar.classList.contains('show')) {
                if (!sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }

    // 2. Executive Tabs Switcher (Pengawas Dashboard Tabs: Ketcab, Sekcab, Bencab)
    const tabButtons = document.querySelectorAll('.pengawas-tab-btn');
    if (tabButtons.length > 0) {
        tabButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                const targetId = this.getAttribute('data-tab');
                if (!targetId) return;

                // Deactivate all buttons
                tabButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Deactivate all tab panes
                const tabPanes = document.querySelectorAll('.tab-pane');
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // Activate selected pane
                const targetPane = document.getElementById(targetId);
                if (targetPane) {
                    targetPane.classList.add('active');
                }
            });
        });
    }

    // 3. Image File Upload Preview Helper
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                // Find nearest preview container if exists
                const previewTarget = document.querySelector(`[data-preview-for="${this.id}"]`);
                if (previewTarget) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        previewTarget.src = e.target.result;
                        previewTarget.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
        });
    });

    // 4. Auto Dismiss Alert / Flash Messages
    const alertDismissables = document.querySelectorAll('.alert-dismissible');
    alertDismissables.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // 5. Enhanced Confirm Helper for Dangerous Actions
    const dangerousForms = document.querySelectorAll('form[data-confirm]');
    dangerousForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const message = this.getAttribute('data-confirm') || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
});

/**
 * GMKI Cabang Padang - Client-side Form Validation
 * validation.js
 */

document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            let isValid = true;
            const requiredInputs = form.querySelectorAll('[required]');

            // Hapus pesan error sebelumnya
            form.querySelectorAll('.validation-error-msg').forEach(el => el.remove());
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

            requiredInputs.forEach(function (input) {
                const value = input.value.trim();

                if (!value) {
                    isValid = false;
                    showFieldError(input, 'Bagian ini tidak boleh kosong.');
                } else if (input.type === 'email' && !validateEmail(value)) {
                    isValid = false;
                    showFieldError(input, 'Format email tidak valid.');
                } else if (input.getAttribute('minlength') && value.length < parseInt(input.getAttribute('minlength'))) {
                    isValid = false;
                    showFieldError(input, `Minimal ${input.getAttribute('minlength')} karakter.`);
                }
            });

            // Password confirmation check
            const password = form.querySelector('input[name="password"], input[name="new_password"]');
            const confirmPassword = form.querySelector('input[name="password_confirmation"], input[name="new_password_confirmation"]');

            if (password && confirmPassword && password.value !== confirmPassword.value) {
                isValid = false;
                showFieldError(confirmPassword, 'Konfirmasi kata sandi tidak cocok.');
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    function showFieldError(input, message) {
        input.classList.add('is-invalid');
        input.style.borderColor = '#ef4444';
        
        const errorEl = document.createElement('div');
        errorEl.className = 'validation-error-msg';
        errorEl.style.color = '#ef4444';
        errorEl.style.fontSize = '0.75rem';
        errorEl.style.marginTop = '0.25rem';
        errorEl.textContent = message;

        input.parentNode.appendChild(errorEl);

        input.addEventListener('input', function handler() {
            input.style.borderColor = '';
            errorEl.remove();
            input.removeEventListener('input', handler);
        });
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
});

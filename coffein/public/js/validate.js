// public/js/validate.js
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', e => {
            let ok = true;

            const email = form.querySelector('input[type="email"]');
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                email.classList.add('is-invalid');
                ok = false;
            } else if (email) {
                email.classList.remove('is-invalid');
            }

            const pwd = form.querySelector('input[name="password"]');
            if (pwd && pwd.value.length < 6) {
                pwd.classList.add('is-invalid');
                ok = false;
            } else if (pwd) {
                pwd.classList.remove('is-invalid');
            }

            const nameInputs = form.querySelectorAll('input[required]');
            nameInputs.forEach(inp => {
                if (!inp.value.trim()) {
                    inp.classList.add('is-invalid');
                    ok = false;
                } else {
                    inp.classList.remove('is-invalid');
                }
            });

            if (!ok) e.preventDefault();
        });
    });
});
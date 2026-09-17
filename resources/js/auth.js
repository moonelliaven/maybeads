document.addEventListener('DOMContentLoaded', () => {
    const currentPage = document.body.dataset.page;
    if (!currentPage) return;

    const pageTitle = document.querySelector('.auth-card h1');
    if (pageTitle && pageTitle.textContent.trim().toLowerCase().includes('welcome')) {
        pageTitle.setAttribute('aria-live', 'polite');
    }

    document.querySelectorAll('.password-toggle').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const passwordField = toggle.closest('.password-field').querySelector('input');
            const isVisible = passwordField.type === 'text';

            passwordField.type = isVisible ? 'password' : 'text';
            toggle.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            toggle.setAttribute('aria-pressed', String(!isVisible));
            toggle.classList.toggle('is-visible', !isVisible);
        });
    });
});

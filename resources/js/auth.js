document.addEventListener('DOMContentLoaded', () => {
    const currentPage = document.body.dataset.page;
    if (!currentPage) return;

    const pageTitle = document.querySelector('.auth-card h1');
    if (pageTitle && pageTitle.textContent.trim().toLowerCase().includes('welcome')) {
        pageTitle.setAttribute('aria-live', 'polite');
    }
});

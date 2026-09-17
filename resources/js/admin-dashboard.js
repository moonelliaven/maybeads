document.addEventListener('DOMContentLoaded', () => {
    const sessionStatus = document.body.dataset.sessionStatus;
    if (sessionStatus === 'guest') {
        const target = document.querySelector('.admin-page');
        if (target) {
            target.setAttribute('data-session', 'guest');
        }
    }
});

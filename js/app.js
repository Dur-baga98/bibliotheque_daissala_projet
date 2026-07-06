document.addEventListener('DOMContentLoaded', () => {
    const autoHideMessages = document.querySelectorAll('.js-auto-hide');
    autoHideMessages.forEach((message) => {
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.3s ease';
            setTimeout(() => message.remove(), 300);
        }, 4000);
    });

    const filterInput = document.querySelector('.js-book-filter');
    const bookList = document.querySelector('.js-book-list');
    const emptyState = document.querySelector('.js-empty-state');

    if (filterInput && bookList) {
        const cards = Array.from(bookList.querySelectorAll('.book-card'));

        filterInput.addEventListener('input', (event) => {
            const query = event.target.value.trim().toLowerCase();

            let visibleCount = 0;
            cards.forEach((card) => {
                const text = card.textContent.toLowerCase();
                const isVisible = text.includes(query);
                card.style.display = isVisible ? '' : 'none';
                if (isVisible) {
                    visibleCount += 1;
                }
            });

            if (emptyState) {
                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    }

    document.querySelectorAll('[data-confirm-delete="true"]').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (!window.confirm('Voulez-vous vraiment supprimer ce livre ?')) {
                event.preventDefault();
            }
        });
    });
});

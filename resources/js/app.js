const productFiltersForm = document.getElementById('product-filters-form');
let refreshProducts;

if (productFiltersForm) {
    const searchInput = productFiltersForm.querySelector('[name="search"]');
    const categorySelect = productFiltersForm.querySelector('[name="category_id"]');
    let searchTimeout;
    let activeRequest;

    const updateProducts = async (url) => {
        activeRequest?.abort();
        activeRequest = new AbortController();
        document.getElementById('product-results-body')?.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: activeRequest.signal,
            });

            if (!response.ok) {
                throw new Error('Unable to refresh products.');
            }

            const page = new DOMParser().parseFromString(await response.text(), 'text/html');

            ['product-results-body', 'product-pagination', 'active-product-filters', 'product-result-count'].forEach((id) => {
                const currentElement = document.getElementById(id);
                const updatedElement = page.getElementById(id);

                if (currentElement && updatedElement) {
                    currentElement.innerHTML = updatedElement.innerHTML;
                }
            });

            window.history.replaceState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') {
                window.location.assign(url);
            }
        } finally {
            document.getElementById('product-results-body')?.removeAttribute('aria-busy');
        }
    };
    refreshProducts = updateProducts;

    const submitFilters = () => {
        window.clearTimeout(searchTimeout);
        const url = new URL(productFiltersForm.action, window.location.origin);
        new FormData(productFiltersForm).forEach((value, key) => {
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });

        updateProducts(url);
    };

    searchInput?.addEventListener('input', () => {
        window.clearTimeout(searchTimeout);
        searchTimeout = window.setTimeout(submitFilters, 300);
    });

    productFiltersForm.addEventListener('submit', (event) => {
        event.preventDefault();
        submitFilters();
    });

    categorySelect?.addEventListener('change', () => {
        submitFilters();
    });

    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);
        searchInput.value = url.searchParams.get('search') ?? '';
        categorySelect.value = url.searchParams.get('category_id') ?? '';
        updateProducts(url);
    });
}

const closeModal = (modal) => {
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};

const openModal = (modal) => {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

const closeMenus = () => {
    document.querySelectorAll('[data-menu-content]').forEach((menu) => {
        menu.classList.add('hidden');
    });
};

document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-menu-toggle]');

    if (toggle) {
        event.stopPropagation();

        const menu = toggle.closest('[data-menu]')?.querySelector('[data-menu-content]');

        if (!menu) return;

        const isHidden = menu.classList.contains('hidden');
        closeMenus();

        if (isHidden) {
            menu.classList.remove('hidden');
            const toggleBounds = toggle.getBoundingClientRect();
            const menuHeight = menu.offsetHeight;
            const spaceBelow = window.innerHeight - toggleBounds.bottom;
            const top = spaceBelow >= menuHeight + 8
                ? toggleBounds.bottom + 4
                : Math.max(8, toggleBounds.top - menuHeight - 4);

            menu.style.top = `${top}px`;
            menu.style.left = `${Math.max(8, toggleBounds.right - menu.offsetWidth)}px`;
        }
    }
});

document.addEventListener('click', (event) => {
    if (!event.target.closest('[data-menu-toggle]')) {
        closeMenus();
    }
});

document.addEventListener('click', (event) => {
    const opener = event.target.closest('[data-modal-open]');

    if (opener) {
        const modal = document.getElementById(opener.dataset.modalOpen);

        if (!modal) return;

        closeMenus();
        openModal(modal);

        if (opener.dataset.formAction) {
            modal.querySelector('form').action = opener.dataset.formAction;
        }

        Object.entries(opener.dataset).forEach(([key, value]) => {
            const field = modal.querySelector(`[data-modal-field="${key}"]`);

            if (field) {
                if ('value' in field) {
                    field.value = value;
                } else {
                    field.textContent = value;
                }
            }
        });
        return;
    }

    const filterLink = event.target.closest('#active-product-filters a, #product-pagination a');

    if (filterLink) {
        event.preventDefault();
        if (productFiltersForm) {
            const destination = new URL(filterLink.href);
            productFiltersForm.querySelector('[name="search"]').value = destination.searchParams.get('search') ?? '';
            productFiltersForm.querySelector('[name="category_id"]').value = destination.searchParams.get('category_id') ?? '';
        }
        if (refreshProducts) {
            refreshProducts(new URL(filterLink.href));
        }
    }
});

document.querySelectorAll('[data-modal-close]').forEach((closer) => {
    closer.addEventListener('click', () => {
        const modal = closer.closest('[data-modal]');

        if (modal) {
            closeModal(modal);
        }
    });
});

document.querySelectorAll('[data-modal]').forEach((modal) => {
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal(modal);
        }
    });
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        document.querySelectorAll('[data-modal]:not(.hidden)').forEach(closeModal);
    }
});

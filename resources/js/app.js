const productFiltersForm = document.getElementById('product-filters-form');
const mobileNavigationToggle = document.querySelector('[data-mobile-nav-toggle]');
const mobileNavigation = document.getElementById('main-navigation');
const mobileNavigationMediaQuery = window.matchMedia('(min-width: 64rem)');

const setMobileNavigationOpen = (isOpen) => {
    if (!mobileNavigationToggle || !mobileNavigation) {
        return;
    }

    const shouldBeOpen = mobileNavigationMediaQuery.matches || isOpen;

    mobileNavigationToggle.setAttribute('aria-expanded', String(shouldBeOpen));
    mobileNavigationToggle.setAttribute('aria-label', shouldBeOpen ? 'Close navigation menu' : 'Open navigation menu');
    mobileNavigation.classList.toggle('pointer-events-none', !shouldBeOpen);
    mobileNavigation.classList.toggle('-translate-y-2', !shouldBeOpen);
    mobileNavigation.classList.toggle('translate-y-0', shouldBeOpen);
    mobileNavigation.classList.toggle('opacity-0', !shouldBeOpen);
    mobileNavigation.classList.toggle('opacity-100', shouldBeOpen);
    mobileNavigation.setAttribute('aria-hidden', String(!shouldBeOpen));
    mobileNavigation.inert = !shouldBeOpen;
    mobileNavigationToggle.querySelector('[data-menu-open-icon]')?.classList.toggle('hidden', shouldBeOpen);
    mobileNavigationToggle.querySelector('[data-menu-close-icon]')?.classList.toggle('hidden', !shouldBeOpen);
};

mobileNavigationToggle?.addEventListener('click', () => {
    const isOpen = mobileNavigationToggle.getAttribute('aria-expanded') === 'true';
    setMobileNavigationOpen(!isOpen);
});

mobileNavigation?.addEventListener('click', (event) => {
    if (event.target.closest('a')) {
        setMobileNavigationOpen(false);
    }
});

mobileNavigationMediaQuery.addEventListener('change', ({ matches }) => {
    setMobileNavigationOpen(matches);
});

setMobileNavigationOpen(false);

if (productFiltersForm) {
    const searchInput = productFiltersForm.querySelector('[name="search"]');
    const categorySelect = productFiltersForm.querySelector('[name="category_id"]');
    const sortSelect = productFiltersForm.querySelector('[name="sort"]');
    const stockStatusSelect = productFiltersForm.querySelector('[name="stock_status"]');
    const statusInput = productFiltersForm.querySelector('[name="status"]');
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

    const syncFiltersFromUrl = (url) => {
        searchInput.value = url.searchParams.get('search') ?? '';
        categorySelect.value = url.searchParams.get('category_id') ?? '';
        stockStatusSelect.value = url.searchParams.get('stock_status') ?? 'all';
        statusInput.value = url.searchParams.get('status') ?? 'active';

        const requestedSort = url.searchParams.get('sort') ?? 'newest';
        const hasSortOption = Array.from(sortSelect.options).some((option) => option.value === requestedSort);
        sortSelect.value = hasSortOption ? requestedSort : 'newest';
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

    sortSelect?.addEventListener('change', () => {
        submitFilters();
    });

    stockStatusSelect?.addEventListener('change', () => {
        submitFilters();
    });

    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);
        syncFiltersFromUrl(url);
        updateProducts(url);
    });
}

const categoryFiltersForm = document.getElementById('category-filters-form');

if (categoryFiltersForm) {
    const searchInput = categoryFiltersForm.querySelector('[name="search"]');
    const sortSelect = categoryFiltersForm.querySelector('[name="sort"]');
    let searchTimeout;
    let activeRequest;

    const updateCategories = async (url) => {
        activeRequest?.abort();
        activeRequest = new AbortController();
        document.getElementById('category-results-body')?.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: activeRequest.signal,
            });

            if (!response.ok) {
                throw new Error('Unable to refresh categories.');
            }

            const page = new DOMParser().parseFromString(await response.text(), 'text/html');

            ['category-results-body', 'category-pagination', 'category-result-count'].forEach((id) => {
                const currentElement = document.getElementById(id);
                const updatedElement = page.getElementById(id);

                if (currentElement && updatedElement) {
                    currentElement.innerHTML = updatedElement.innerHTML;
                    currentElement.classList.toggle('hidden', updatedElement.classList.contains('hidden'));
                }
            });

            window.history.replaceState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') {
                window.location.assign(url);
            }
        } finally {
            document.getElementById('category-results-body')?.removeAttribute('aria-busy');
        }
    };

    const submitCategoryFilters = () => {
        window.clearTimeout(searchTimeout);
        const url = new URL(categoryFiltersForm.action, window.location.origin);
        new FormData(categoryFiltersForm).forEach((value, key) => {
            if (value && !(key === 'sort' && value === 'newest')) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });

        updateCategories(url);
    };

    searchInput?.addEventListener('input', () => {
        window.clearTimeout(searchTimeout);
        searchTimeout = window.setTimeout(submitCategoryFilters, 300);
    });

    categoryFiltersForm.addEventListener('submit', (event) => {
        event.preventDefault();
        submitCategoryFilters();
    });

    sortSelect?.addEventListener('change', submitCategoryFilters);

    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);
        searchInput.value = url.searchParams.get('search') ?? '';
        sortSelect.value = url.searchParams.get('sort') ?? 'newest';
        updateCategories(url);
    });
}

const setupLiveFilters = ({ formId, searchName, resultBodyId, paginationId, resultCountId, selectNames = [] }) => {
    const form = document.getElementById(formId);

    if (!form) {
        return;
    }

    const searchInput = form.querySelector(`[name="${searchName}"]`);
    const selects = selectNames
        .map((name) => form.querySelector(`[name="${name}"]`))
        .filter(Boolean);
    let searchTimeout;
    let activeRequest;

    const updateResults = async (url) => {
        activeRequest?.abort();
        activeRequest = new AbortController();
        document.getElementById(resultBodyId)?.setAttribute('aria-busy', 'true');

        try {
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: activeRequest.signal,
            });

            if (!response.ok) {
                throw new Error('Unable to refresh filtered results.');
            }

            const page = new DOMParser().parseFromString(await response.text(), 'text/html');

            [resultBodyId, paginationId, resultCountId].forEach((id) => {
                const currentElement = document.getElementById(id);
                const updatedElement = page.getElementById(id);

                if (currentElement && updatedElement) {
                    currentElement.innerHTML = updatedElement.innerHTML;
                    currentElement.classList.toggle('hidden', updatedElement.classList.contains('hidden'));
                }
            });

            window.history.replaceState({}, '', url);
        } catch (error) {
            if (error.name !== 'AbortError') {
                window.location.assign(url);
            }
        } finally {
            document.getElementById(resultBodyId)?.removeAttribute('aria-busy');
        }
    };

    const submitFilters = () => {
        window.clearTimeout(searchTimeout);
        const url = new URL(form.action, window.location.origin);

        new FormData(form).forEach((value, key) => {
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
        });

        updateResults(url);
    };

    searchInput?.addEventListener('input', () => {
        window.clearTimeout(searchTimeout);
        searchTimeout = window.setTimeout(submitFilters, 300);
    });

    selects.forEach((select) => select.addEventListener('change', submitFilters));
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        submitFilters();
    });

    window.addEventListener('popstate', () => {
        const url = new URL(window.location.href);

        if (searchInput) {
            searchInput.value = url.searchParams.get(searchName) ?? '';
        }

        selects.forEach((select) => {
            select.value = url.searchParams.get(select.name) ?? '';
        });

        updateResults(url);
    });
};

setupLiveFilters({
    formId: 'stock-filters-form',
    searchName: 'search',
    resultBodyId: 'stock-results-body',
    paginationId: 'stock-pagination',
    resultCountId: 'stock-result-count',
    selectNames: ['type'],
});

setupLiveFilters({
    formId: 'activity-filters-form',
    searchName: 'search',
    resultBodyId: 'activity-results-body',
    paginationId: 'activity-pagination',
    resultCountId: 'activity-result-count',
    selectNames: ['module'],
});

setupLiveFilters({
    formId: 'stock-adjustment-filters-form',
    searchName: 'search',
    resultBodyId: 'stock-adjustment-results-body',
    paginationId: 'stock-adjustment-pagination',
    resultCountId: 'stock-adjustment-result-count',
    selectNames: ['reason'],
});

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

        if (modal.id === 'stockInModal' || modal.id === 'stockOutModal') {
            const quantityField = modal.querySelector('[name="quantity"]');
            const remarksField = modal.querySelector('[name="remarks"]');
            const currentStock = Number(opener.dataset.currentStock ?? 0);

            if (quantityField) {
                quantityField.value = '';
                quantityField.removeAttribute('max');
            }

            if (remarksField) {
                remarksField.value = '';
            }

            const transactionDataFields = {
                product_name: 'productName',
                product_sku: 'productSku',
                current_stock: 'currentStock',
            };

            Object.entries(transactionDataFields).forEach(([fieldName, dataField]) => {
                const field = modal.querySelector(`[name="${fieldName}"]`);

                if (field) {
                    field.value = opener.dataset[dataField] ?? '';
                }
            });

            if (modal.id === 'stockOutModal' && quantityField) {
                quantityField.max = String(currentStock);
            }
        }

        if (modal.id === 'stockAdjustmentModal') {
            const newQuantityField = modal.querySelector('[name="new_quantity"]');
            const reasonField = modal.querySelector('[name="reason"]');
            const adjustmentRemarksField = modal.querySelector('[name="remarks"]');

            if (newQuantityField) {
                newQuantityField.value = '';
            }

            if (reasonField) {
                reasonField.value = '';
            }

            if (adjustmentRemarksField) {
                adjustmentRemarksField.value = '';
            }
        }

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
        setMobileNavigationOpen(false);
        document.querySelectorAll('[data-modal]:not(.hidden)').forEach(closeModal);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const sortDropdown = document.getElementById('sortDropdown');
    const sortOptions = document.querySelectorAll('.dropdown-menu a');

    const cardViewBtn = document.getElementById('cardViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const cardsView = document.getElementById('cardsView');
    const listView = document.getElementById('listView');

    const urlParams = new URLSearchParams(window.location.search);
    const viewParam = urlParams.get('view');

    const sortBy = urlParams.get('sort_by');
    const sortOrder = urlParams.get('sort_order');

    sortOptions.forEach(option => {
        option.addEventListener('click', function() {
            const sortText = this.textContent.trim();

            let sortBy = 'name';
            let sortOrder = 'asc';

            switch (sortText) {
                case 'Precio: menor a mayor':
                    sortBy = 'price';
                    sortOrder = 'asc';
                    break;
                case 'Precio: mayor a menor':
                    sortBy = 'price';
                    sortOrder = 'desc';
                    break;
                case 'Nombre: A-Z':
                    sortBy = 'name';
                    sortOrder = 'asc';
                    break;
                case 'Nombre: Z-A':
                    sortBy = 'name';
                    sortOrder = 'desc';
                    break;
                default:
                    sortBy = '';
                    sortOrder = '';
                    break;
            }

            sortDropdown.innerHTML = '<i class="bi bi-sort-down me-1"></i>${sortText}';

            const url = new URL(window.location);
            url.searchParams.set('sort_by', sortBy);
            url.searchParams.set('sort_order', sortOrder);

            window.location.href = url.toString();
        });
    });

    if (sortBy && sortOrder) {
        let sortText = '';
        if (sortBy === 'price' && sortOrder === 'asc') {
            sortText = 'Precio: menor a mayor';
        } else if (sortBy === 'price' && sortOrder === 'desc') {
            sortText = 'Precio: mayor a menor';
        } else if (sortBy === 'name' && sortOrder === 'asc') {
            sortText = 'Nombre: A-Z';
        } else if (sortBy === 'name' && sortOrder === 'desc') {
            sortText = 'Nombre: Z-A';
        } else {
            sortText = 'Sin ordenar';
        }
        
        if (sortText) {
            sortDropdown.innerHTML = `<i class="bi bi-sort-down me-1"></i>${sortText}`;
        }
    }

    if (viewParam === 'list') {
        activateListView();
    } else {
        activateCardView();
    }

    // Función para cambiar a vista de tarjetas
    cardViewBtn.addEventListener('click', function() {
        activateCardView();
        updateUrlParam('cards');
    });

    // Función para cambiar a vista de lista
    listViewBtn.addEventListener('click', function() {
        activateListView();
        updateUrlParam('list');
    });

    // Función para activar vista de tarjetas
    function activateCardView() {
        cardsView.classList.remove('d-none');
        listView.classList.add('d-none');
        cardViewBtn.classList.add('active');
        cardViewBtn.classList.remove('btn-secondary');
        cardViewBtn.classList.add('btn-primary');
        cardViewBtn.disabled = true;
        listViewBtn.classList.remove('active');
        listViewBtn.classList.remove('btn-primary');
        listViewBtn.classList.add('btn-secondary');
        listViewBtn.disabled = false;
    }

    // Función para activar vista de lista
    function activateListView() {
        cardsView.classList.add('d-none');
        listView.classList.remove('d-none');
        listViewBtn.classList.add('active');
        listViewBtn.classList.remove('btn-secondary');
        listViewBtn.classList.add('btn-primary');
        listViewBtn.disabled = true;
        cardViewBtn.classList.remove('active');
        cardViewBtn.classList.remove('btn-primary');
        cardViewBtn.classList.add('btn-secondary');
        cardViewBtn.disabled = false;
    }

    function updateUrlParam(viewType) {
        const url = new URL(window.location);
        url.searchParams.set('view', viewType);
        history.replaceState({}, '', url);

        updatePaginationLinks(viewType);
    }

    // Función para actualizar los enlaces de paginación
    function updatePaginationLinks(viewType) {
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            if (link.href) {
                const linkUrl = new URL(link.href);
                linkUrl.searchParams.set('view', viewType);
                link.href = linkUrl.toString();
            }
        });
    }

    if (viewParam) {
        updatePaginationLinks(viewParam);
    }
});
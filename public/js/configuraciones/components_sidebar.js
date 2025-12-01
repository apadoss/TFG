import { componentTypeMap, createOrUpdateHiddenInput } from './logic.js';

/** Funciones de manipulación de la Sidebar **/

export function filterComponents(searchText) {
    const componentCards = document.querySelectorAll('.component-card');
    const searchLower = searchText.toLowerCase();
    
    componentCards.forEach(card => {
        const title = card.querySelector('h4').textContent.toLowerCase();
        card.style.display = title.includes(searchLower) ? 'flex' : 'none';
    });
}

// Función para crear una tarjeta de componente
export function createComponentCard(component, componentType) {
    const card = document.createElement('div');
    card.className = 'component-card';
    card.dataset.id = component.id;
    
    const cardImage = document.createElement('img');
    cardImage.className = 'component-image';
    cardImage.src = component.image;
    cardImage.alt = component.name;
    
    const cardBody = document.createElement('div');
    cardBody.className = 'component-body';
    
    const cardTitle = document.createElement('h4');
    cardTitle.textContent = component.name;
    
    const cardPrice = document.createElement('p');
    cardPrice.className = 'component-price';
    cardPrice.textContent = `${component.price} €`;
    
    const stockBadge = document.createElement('span');
    if (component.in_stock) {
        stockBadge.className = 'badge bg-success-subtle text-success mt-2 mb-2';
        stockBadge.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>En Stock';
    } else {
        stockBadge.className = 'badge bg-danger-subtle text-danger mt-2 mb-2';
        stockBadge.innerHTML = '<i class="bi bi-x-circle-fill me-1"></i>Sin Stock';
    }

    const selectBtn = document.createElement('button');
    selectBtn.className = 'btn btn-primary btn-sm';
    selectBtn.textContent = component.in_stock ? 'Seleccionar' : 'Agotado';
    selectBtn.disabled = !component.in_stock;
    
    // Lógica del evento click del botón "Seleccionar"
    selectBtn.addEventListener('click', function() {
        const sidebar = document.getElementById('components-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        const originalButtons = document.querySelectorAll('.card-body');
        let targetButton = null;
        let targetCard = null;

        originalButtons.forEach(cardBody => {
            const title = cardBody.querySelector('.card-title');
            if (title && title.textContent === componentType) {
                targetButton = cardBody.querySelector('.btn-primary');
                targetCard = cardBody.closest('.card');
            }
        });

        if (targetButton && targetCard) {
            targetButton.textContent = `Seleccionado: ${component.name}`;
            targetButton.classList.add('selected');

            const componentSlug = componentTypeMap[componentType];
            const removeButton = targetCard.querySelector(`.btn-remove[data-component="${componentSlug}"]`);
            if (removeButton) {
                removeButton.style.display = 'block';
            }

            const imageElement = document.getElementById(`${componentSlug}-image`);
            if (imageElement && component.image) {
                imageElement.src = component.image;
                imageElement.style.display = 'block';
            }

            // Llamar a la función de lógica para guardar el ID
            const success = createOrUpdateHiddenInput(componentSlug, component.id);
            if (success) {
                // Cerrar sidebar al seleccionar
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        }
    });

    cardBody.appendChild(cardTitle);
    cardBody.appendChild(cardPrice);
    cardBody.appendChild(stockBadge);
    cardBody.appendChild(selectBtn);

    card.appendChild(cardImage);
    card.appendChild(cardBody);

    return card;
}
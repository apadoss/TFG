document.addEventListener('DOMContentLoaded', function () {
    // Crear el contenedor de la sidebar
    const sidebar = document.createElement('div');
    sidebar.id = 'components-sidebar';
    sidebar.className = 'components-sidebar';

    // Detectar altura del header de la página
    const adjustSidebarPosition = () => {
        // Buscar el header de la página
        const siteHeader = document.querySelector('header') || document.querySelector('.navbar') || document.querySelector('.header');
        
        if (siteHeader) {
            const headerHeight = siteHeader.offsetHeight;
            sidebar.style.paddingTop = `${headerHeight}px`;
            console.log(`Ajustando sidebar con padding-top: ${headerHeight}px`);
        } else {
            // Si no se encuentra el header, usar un valor predeterminado
            sidebar.style.paddingTop = '70px';
            console.log('No se encontró el header, usando padding-top predeterminado');
        }
    };

    window.addEventListener('load', adjustSidebarPosition);
    window.addEventListener('resize', adjustSidebarPosition);
    
    // Crear el header de la sidebar
    const sidebarHeader = document.createElement('div');
    sidebarHeader.className = 'sidebar-header';
    
    // Título de la sidebar
    const sidebarTitle = document.createElement('h3');
    sidebarTitle.textContent = 'Componentes disponibles';
    
    // Overlay para oscurecer el fondo cuando la sidebar está abierta
    const overlay = document.createElement('div');
    overlay.id = 'sidebar-overlay';
    overlay.className = 'sidebar-overlay';
    
    // Botón para cerrar la sidebar
    const closeButton = document.createElement('button');
    closeButton.className = 'close-btn';
    closeButton.innerHTML = '&times;';
    closeButton.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    });
    
    // Barra de búsqueda
    const searchContainer = document.createElement('div');
    searchContainer.className = 'search-container';
    
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = 'search-input';
    searchInput.placeholder = 'Buscar componente...';
    searchInput.addEventListener('input', function() {
        filterComponents(this.value);
    });
    
    searchContainer.appendChild(searchInput);
    
    // Contenedor para el contenido de los componentes
    const sidebarContent = document.createElement('div');
    sidebarContent.className = 'sidebar-content';
    
    // Añadir elementos al DOM
    sidebarHeader.appendChild(sidebarTitle);
    sidebarHeader.appendChild(closeButton);
    sidebar.appendChild(sidebarHeader);
    sidebar.appendChild(searchContainer);
    sidebar.appendChild(sidebarContent);
    document.body.appendChild(sidebar);
    
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    });
    document.body.appendChild(overlay);

    // Función para filtrar componentes según el texto de búsqueda
    function filterComponents(searchText) {
        const componentCards = document.querySelectorAll('.component-card');
        const searchLower = searchText.toLowerCase();
        
        componentCards.forEach(card => {
            const title = card.querySelector('h4').textContent.toLowerCase();
            if (title.includes(searchLower)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function findElementByText(selector, text) {
        const elements = document.querySelectorAll(selector);
        for (const element of elements) {
            if (element.textContent.includes(text)) {
                return element;
            }
        }
        return null;
    }

    const componentTypeMap = {
        'Procesador': 'procesador',
        'Tarjeta gráfica': 'tarjeta_grafica',
        'Placa Base': 'placa_base',
        'Memoria RAM': 'memoria_ram',
        'Almacenamiento': 'almacenamiento',
        'Fuente de Alimentación': 'fuente_de_alimentacion'
    };

    function processUrlParameters() {
        const params = new URLSearchParams(window.location.search);
        
        // Mapeo de parámetros URL a tipos de componentes
        const paramToTypeMap = {
            'procesador': 'Procesador',
            'tarjeta-grafica': 'Tarjeta gráfica',
            'placa-base': 'Placa Base',
            'ram': 'Memoria RAM',
            'almacenamiento': 'Almacenamiento',
            'fuente-alimentacion': 'Fuente de Alimentación'
        };
        
        // Para cada tipo de componente en la URL
        for (const [param, type] of Object.entries(paramToTypeMap)) {
            if (params.has(param)) {
                const componentName = decodeURIComponent(params.get(param));
                if (componentName) {
                    // Buscar y preseleccionar este componente
                    preSelectComponent(type, componentName);
                }
            }
        }
    }

    function preSelectComponent(componentType, componentName) {
        console.log(`Buscando componente: ${componentType} - ${componentName}`);
        
        // Determinar endpoint según el tipo de componente
        let endpoint = '';
        switch(componentType) {
            case 'Procesador':
                endpoint = '/api/v1/components/cpus';
                break;
            case 'Tarjeta gráfica':
                endpoint = '/api/v1/components/graphic-cards';
                break;
            case 'Placa Base':
                endpoint = '/api/v1/components/motherboards';
                break;
            case 'Fuente de Alimentación':
                endpoint = '/api/v1/components/power-supplies';
                break;
            case 'Memoria RAM':
                endpoint = '/api/v1/components/rams';
                break;
            case 'Almacenamiento':
                endpoint = '/api/v1/components/storage-devices';
                break;
            default:
                return;
        }
        
        // Agregar parámetro de búsqueda por nombre
        endpoint += `?name=${encodeURIComponent(componentName)}`;

        console.log(`Buscando componente: ${componentType} - ${componentName}`);
        
        // Buscar en la API
        fetch(endpoint, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al buscar el componente: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Si encontramos resultados
            if (data && data.length > 0) {
                // Tomar el primer componente que coincide
                const component = data[0];
                
                // Buscar el botón correspondiente usando nuestra función auxiliar
                const cardTitle = findElementByText('.card-title', componentType);
                
                if (cardTitle) {
                    const cardBody = cardTitle.closest('.card-body');
                    const targetButton = cardBody.querySelector('.btn-primary');
                    const removeButton = cardBody.querySelector('.btn-remove');
                    
                    if (targetButton) {
                        // Actualizar texto del botón
                        targetButton.textContent = `Seleccionado: ${component.name}`;
                        targetButton.classList.add('selected');
                        
                        // Mostrar el botón de quitar
                        if (removeButton) {
                            removeButton.style.display = 'block';
                        }
                        
                        // Mostrar la imagen del componente
                        const componentSlug = componentTypeMap[componentType];
                        const imageElement = document.getElementById(`${componentSlug}-image`);
                        if (imageElement && component.image) {
                            imageElement.src = component.image;
                            imageElement.style.display = 'block';
                        }
                        
                        // Agregar input hidden con el ID
                        let hiddenInput = document.querySelector(`input[name="${componentTypeMap[componentType]}"]`);
                        if (!hiddenInput) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = componentTypeMap[componentType];
                            document.querySelector('form').appendChild(hiddenInput);
                        }
                        hiddenInput.value = component.id;
                        
                        console.log(`Componente preseleccionado: ${componentType} - ${component.name} (ID: ${component.id})`);
                    }
                }
            } else {
                console.log(`No se encontraron componentes para: ${componentType} - ${componentName}`);
            }
        })
        .catch(error => {
            console.error(`Error al buscar el componente ${componentType}:`, error);
        });
    }

    // Manejar botones de quitar componente
    const removeButtons = document.querySelectorAll('.btn-remove');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const componentSlug = this.dataset.component;
            
            // Encontrar el contenedor de la tarjeta
            const cardBody = this.closest('.card-body');
            const selectButton = cardBody.querySelector('.btn-primary');
            
            // Restablecer el texto del botón
            selectButton.textContent = 'Seleccionar';
            selectButton.classList.remove('selected');
            
            // Ocultar el botón de quitar
            this.style.display = 'none';
            
            // Ocultar la imagen
            const imageElement = document.getElementById(`${componentSlug}-image`);
            if (imageElement) {
                imageElement.style.display = 'none';
            }
            
            // Eliminar el campo oculto
            const hiddenInput = document.querySelector(`input[name="${componentSlug}"]`);
            if (hiddenInput) {
                hiddenInput.remove();
            }
        });
    });

    // Agregar event listeners a todos los botones "Seleccionar"
    const selectButtons = document.querySelectorAll('.btn.btn-primary');
    selectButtons.forEach(button => {
        // Excluir el botón de guardar
        if (button.type === 'submit') return;

        button.addEventListener('click', function() {
            // Obtener el tipo de componente desde el título de la card
            const componentType = this.closest('.card-body').querySelector('.card-title').textContent;
            
            // Verificar si hay un componente ya seleccionado para mostrar en la barra de búsqueda
            const isSelected = this.classList.contains('selected');
            let selectedComponentName = '';
            
            if (isSelected) {
                // Extraer el nombre del componente del texto del botón
                const buttonText = this.textContent;
                selectedComponentName = buttonText.replace('Seleccionado: ', '');
            }
            
            // Cargar los componentes según el tipo
            loadComponents(componentType, sidebarContent, selectedComponentName);
            
            // Mostrar la sidebar y el overlay
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('sidebar-open');
        });
    });
    
    // Función para cargar los componentes en la sidebar
    function loadComponents(componentType, container, selectedComponentName = '') {
        // Actualizar el título
        sidebarTitle.textContent = `Componentes: ${componentType}`;
        
        // Limpiar el contenido actual
        container.innerHTML = '';
        
        // Mostrar un indicador de carga
        container.innerHTML = '<p class="text-center">Cargando componentes...</p>';
        
        // Colocar el nombre del componente seleccionado en la barra de búsqueda
        searchInput.value = selectedComponentName;
        
        // Aquí harías una petición AJAX para obtener los componentes desde el servidor
        fetchComponents(componentType).then(components => {
            // Limpiar el indicador de carga
            container.innerHTML = '';
            
            if (components.length === 0) {
                container.innerHTML = '<p>No hay componentes disponibles.</p>';
                return;
            }
            
            // Crear una lista de componentes
            const componentList = document.createElement('div');
            componentList.className = 'component-list';
            
            components.forEach(component => {
                const componentCard = createComponentCard(component, componentType);
                componentList.appendChild(componentCard);
            });
            
            container.appendChild(componentList);
            
            // Filtrar los componentes si hay un término de búsqueda
            if (selectedComponentName) {
                filterComponents(selectedComponentName);
            }
        });
    }
    
    // Función para obtener los componentes
    function fetchComponents(componentType) {
        let endpoint = '';

        switch(componentType) {
            case 'Procesador':
                endpoint = '/api/v1/components/cpus';
                break;
            case 'Tarjeta gráfica':
                endpoint = '/api/v1/components/graphic-cards';
                break;
            case 'Placa Base':
                endpoint = '/api/v1/components/motherboards';
                break;
            case 'Fuente de Alimentación':
                endpoint = '/api/v1/components/power-supplies';
                break;
            case 'Memoria RAM':
                endpoint = '/api/v1/components/rams';
                break;
            case 'Almacenamiento':
                endpoint = '/api/v1/components/storage-devices';
                break;
        }

        return fetch(endpoint, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error al cargar los componentes: ${response.status}`);
            }
            return response.json();
        })
        .catch(error => {
            console.error('Error al cargar los componentes:', error);
            return [];
        });
    }
    
    // Función para crear una tarjeta de componente
    function createComponentCard(component, componentType) {
        const card = document.createElement('div');
        card.className = 'component-card';
        card.dataset.id = component.id;
        
        const cardImage = document.createElement('div');
        cardImage.className = 'component-image';
        cardImage.style.backgroundImage = `url(${component.image})`;
        
        const cardBody = document.createElement('div');
        cardBody.className = 'component-body';
        
        const cardTitle = document.createElement('h4');
        cardTitle.textContent = component.name;
        
        const cardPrice = document.createElement('p');
        cardPrice.className = 'component-price';
        cardPrice.textContent = `${component.price} €`;
        
        const selectBtn = document.createElement('button');
        selectBtn.className = 'btn btn-primary btn-sm';
        selectBtn.textContent = 'Seleccionar';
        selectBtn.addEventListener('click', function() {
            // Encontrar el botón "Seleccionar" original correspondiente al tipo de componente
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
                // Actualizar el botón para mostrar el componente seleccionado
                targetButton.textContent = `Seleccionado: ${component.name}`;
                targetButton.classList.add('selected');
                
                // Mostrar el botón de quitar
                const componentSlug = componentTypeMap[componentType];
                const removeButton = targetCard.querySelector(`.btn-remove[data-component="${componentSlug}"]`);
                if (removeButton) {
                    removeButton.style.display = 'block';
                }
                
                // Mostrar la imagen del componente
                const imageElement = document.getElementById(`${componentSlug}-image`);
                if (imageElement && component.image) {
                    imageElement.src = component.image;
                    imageElement.style.display = 'block';
                }
                
                // Agregar un input oculto al formulario para guardar el ID del componente
                let hiddenInput = document.querySelector(`input[name="${componentSlug}"]`);
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = componentSlug;
                    document.querySelector('form').appendChild(hiddenInput);
                }
                hiddenInput.value = component.id;
                
                // Cerrar la sidebar
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.classList.remove('sidebar-open');
            }
        });
        
        cardBody.appendChild(cardTitle);
        cardBody.appendChild(cardPrice);
        cardBody.appendChild(selectBtn);
        
        card.appendChild(cardImage);
        card.appendChild(cardBody);
        
        return card;
    }

    processUrlParameters();
});
import { getCpuId, getMotherboardId, getGpuId, fetchComponents, componentTypeMap, createOrUpdateHiddenInput } from './logic.js';
import { filterComponents, createComponentCard } from './components_sidebar.js';

// Mapeo de tipos de componente para la preselección desde URL
const paramToTypeMap = {
    'procesador': 'Procesador',
    'tarjeta-grafica': 'Tarjeta gráfica',
    'placa-base': 'Placa Base',
    'ram': 'Memoria RAM',
    'almacenamiento': 'Almacenamiento',
    'fuente-alimentacion': 'Fuente de Alimentación'
};

document.addEventListener('DOMContentLoaded', function () {
    // -----------------------------------------------------------------
    // 1. CREACIÓN Y SETUP DE LA SIDEBAR EN EL DOM
    // -----------------------------------------------------------------
    const sidebar = document.createElement('div');
    sidebar.id = 'components-sidebar';
    sidebar.className = 'components-sidebar';

    const sidebarHeader = document.createElement('div');
    sidebarHeader.className = 'sidebar-header';
    
    const sidebarTitle = document.createElement('h3');
    sidebarTitle.textContent = 'Componentes disponibles';
    
    const overlay = document.createElement('div');
    overlay.id = 'sidebar-overlay';
    overlay.className = 'sidebar-overlay';
    
    const closeButton = document.createElement('button');
    closeButton.className = 'close-btn';
    closeButton.innerHTML = '&times;';

    const searchContainer = document.createElement('div');
    searchContainer.className = 'search-container';
    
    const searchInput = document.createElement('input');
    searchInput.type = 'text';
    searchInput.className = 'search-input';
    searchInput.placeholder = 'Buscar componente...';
    
    const sidebarContent = document.createElement('div');
    sidebarContent.className = 'sidebar-content';
    
    // Adjuntar elementos al DOM
    sidebarHeader.appendChild(sidebarTitle);
    sidebarHeader.appendChild(closeButton);
    sidebar.appendChild(sidebarHeader);
    searchContainer.appendChild(searchInput);
    sidebar.appendChild(searchContainer);
    sidebar.appendChild(sidebarContent);
    document.body.appendChild(sidebar);
    document.body.appendChild(overlay);

    // -----------------------------------------------------------------
    // 2. LISTENERS DE LA SIDEBAR Y POSICIONAMIENTO
    // -----------------------------------------------------------------

    // Ajuste de posición de la sidebar basado en la altura del header
    const adjustSidebarPosition = () => {
        const siteHeader = document.querySelector('header') || document.querySelector('.navbar') || document.querySelector('.header');
        
        if (siteHeader) {
            const headerHeight = siteHeader.offsetHeight;
            sidebar.style.paddingTop = `${headerHeight}px`;
        } else {
            sidebar.style.paddingTop = '70px';
        }
    };

    window.addEventListener('load', adjustSidebarPosition);
    window.addEventListener('resize', adjustSidebarPosition);
    
    // Cierre de la sidebar
    const closeSidebar = () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
        if (typeof window.handleCompareSelect === 'function') {
            delete window.handleCompareSelect;
        }
    };

    closeButton.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);
    
    // Filtrado de componentes (usa la función importada)
    searchInput.addEventListener('input', function() {
        filterComponents(this.value);
    });
    
    // -----------------------------------------------------------------
    // 3. LÓGICA DE CARGA DE COMPONENTES
    // -----------------------------------------------------------------

    // Función para cargar los componentes en la sidebar
    function loadComponents(componentType, container, selectedComponentName = '') {
        sidebarTitle.textContent = `Componentes: ${componentType}`;
        container.innerHTML = '<p class="text-center">Cargando componentes...</p>';
        searchInput.value = selectedComponentName;
        
        // Llamada a la función de la API importada
        fetchComponents(componentType).then(components => {
            container.innerHTML = '';
            
            if (components.length === 0) {
                container.innerHTML = '<p>No hay componentes disponibles.</p>';
                return;
            }
            
            const componentList = document.createElement('div');
            componentList.className = 'component-list';
            
            components.forEach(component => {
                // Creación de la tarjeta con la función importada
                const componentCard = createComponentCard(component, componentType);
                componentList.appendChild(componentCard);
            });
            
            container.appendChild(componentList);
            
            // Aplicar filtro si hay un nombre preseleccionado
            if (selectedComponentName) {
                filterComponents(selectedComponentName);
            }
        });
    }

    // -----------------------------------------------------------------
    // 4. LÓGICA DE BOTONES DE CONFIGURACIÓN
    // -----------------------------------------------------------------

    // Manejar botones de quitar componente (solo en la vista de configuración)
    const removeButtons = document.querySelectorAll('.btn-remove');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const componentSlug = this.dataset.component;
            const cardBody = this.closest('.card-body');
            const selectButton = cardBody.querySelector('.btn-primary');
            
            selectButton.textContent = 'Seleccionar';
            selectButton.classList.remove('selected');
            
            this.style.display = 'none';
            
            const imageElement = document.getElementById(`${componentSlug}-image`);
            if (imageElement) {
                imageElement.style.display = 'none';
            }
            
            const hiddenInput = document.querySelector(`input[name="${componentSlug}"]`);
            if (hiddenInput) {
                hiddenInput.remove();
            }
        });
    });

    // Agregar event listeners a todos los botones "Seleccionar" para abrir la sidebar (solo en la vista de configuración)
    const selectButtons = document.querySelectorAll('.btn.btn-primary');
    selectButtons.forEach(button => {
        // Excluir el botón de guardar del formulario y el botón de comparación
        if (button.type === 'submit' || button.id === 'open-sidebar-compare-btn') return;

        button.addEventListener('click', function() {
            const componentType = this.closest('.card-body').querySelector('.card-title').textContent;
            
            const isSelected = this.classList.contains('selected');
            let selectedComponentName = '';
            
            if (isSelected) {
                const buttonText = this.textContent;
                selectedComponentName = buttonText.replace('Seleccionado: ', '');
            }
            
            // Asegurarse de que no esté definida la función de comparación
            if (typeof window.handleCompareSelect === 'function') {
                delete window.handleCompareSelect;
            }

            loadComponents(componentType, sidebarContent, selectedComponentName);
            
            // Mostrar la sidebar
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('sidebar-open');
        });
    });
    
    // -----------------------------------------------------------------
    // 5. LÓGICA DE LA FUENTE DE ALIMENTACIÓN (Solo en la vista de configuración)
    // -----------------------------------------------------------------
    
    // Obtener el botón de la Fuente de Alimentación
    const fuenteBtn = document.querySelector('#psu-image') // Asumo que el ID es 'psu-image'
        ?.closest('.card')
        ?.querySelector('.btn-primary');
    
    if (fuenteBtn) {
        fuenteBtn.disabled = true;
        fuenteBtn.title = 'Debes seleccionar un procesador primero'; 
    
        function updateFuenteButtonState() {
            const cpuSelected = !!getCpuId();
    
            if (cpuSelected) {
                fuenteBtn.disabled = false;
                fuenteBtn.title = 'Seleccionar fuente de alimentación';
            } else {
                fuenteBtn.disabled = true;
                fuenteBtn.title = 'Debes seleccionar un procesador primero';
            }
        }
    
        updateFuenteButtonState();
    
        document.addEventListener('change', updateFuenteButtonState);
        document.addEventListener('click', updateFuenteButtonState);
    }
    
    // -----------------------------------------------------------------
    // 6. LÓGICA DE BOTÓN DE COMPARACIÓN (VISTA DE COMPARACIÓN)
    // -----------------------------------------------------------------
    const compareButton = document.getElementById('open-sidebar-compare-btn');

    if (compareButton) {
        compareButton.addEventListener('click', function() {
            if (typeof COMPONENT_TYPE_SLUG === 'undefined' || typeof PRODUCT_1_ID === 'undefined') {
                console.error('Variables globales de comparación (COMPONENT_TYPE_SLUG/PRODUCT_1_ID) no encontradas.');
                return;
            }

            const componentTypeSlug = COMPONENT_TYPE_SLUG;
            
            const slugToNameMap = {
                'procesadores': 'Procesador',
                'tarjetas-graficas': 'Tarjeta gráfica',
                'placas-base': 'Placa Base',
                'almacenamiento': 'Almacenamiento', // Asumo que este es el nombre completo
                'ram': 'Memoria RAM', // Asumo que este es el nombre completo
                'fuentes-alimentacion': 'Fuente de Alimentación',
                'portatiles': 'Portátil' // Aunque portátiles no se compara con el mismo método, lo incluimos por robustez
            };
            
            let componentTypeName = slugToNameMap[componentTypeSlug];

            if (!componentTypeName) {
                componentTypeName = componentTypeSlug.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
                console.warn(`Mapeo de slug no definido para: ${componentTypeSlug}. Usando: ${componentTypeName}`);
            }
            
            // Cargar componentes en la sidebar
            loadComponents(componentTypeName, sidebarContent);
            
            // **CLAVE:** Definir la función global que 'sidebar.js' llamará al seleccionar
            window.handleCompareSelect = (productId) => {
                // Redirigir a la URL de comparación con los dos productos
                const baseUrl = window.location.origin;
                const redirectUrl = `${baseUrl}/products/${componentTypeSlug}/compare/${PRODUCT_1_ID}/${productId}`;
                window.location.href = redirectUrl;
            };

            // Mostrar la sidebar
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('sidebar-open');
        });
    }

    // -----------------------------------------------------------------
    // 7. LÓGICA DE PRESELECCIÓN POR URL (Deep Linking - Solo en Configuración)
    // -----------------------------------------------------------------
    
    // ... (Mantener la lógica de pre-selección aquí si es relevante para tu vista de configuración)
    function findElementByText(selector, text) {
        const elements = document.querySelectorAll(selector);
        for (const element of elements) {
            if (element.textContent.includes(text)) {
                return element;
            }
        }
        return null;
    }
    
    function preSelectComponent(componentType, componentName) {
        let endpoint = '';
        switch(componentType) {
            case 'Procesador': endpoint = '/api/v1/components/cpus'; break;
            case 'Tarjeta gráfica': endpoint = '/api/v1/components/graphic-cards'; break;
            case 'Placa Base': endpoint = '/api/v1/components/motherboards'; break;
            case 'Fuente de Alimentación': endpoint = '/api/v1/components/power-supplies'; break;
            case 'Memoria RAM': endpoint = '/api/v1/components/rams'; break;
            case 'Almacenamiento': endpoint = '/api/v1/components/storage-devices'; break;
            default: return;
        }
        
        endpoint += `?name=${encodeURIComponent(componentName)}`;
    
        fetch(endpoint, { method: 'GET', headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', } })
        .then(response => response.ok ? response.json() : Promise.reject(`Error ${response.status}`))
        .then(data => {
            if (data && data.length > 0) {
                const component = data[0];
                const cardTitle = findElementByText('.card-title', componentType);
    
                if (cardTitle) {
                    const cardBody = cardTitle.closest('.card-body');
                    const targetButton = cardBody.querySelector('.btn-primary');
                    const removeButton = cardBody.querySelector('.btn-remove');
    
                    if (targetButton) {
                        targetButton.textContent = `Seleccionado: ${component.name}`;
                        targetButton.classList.add('selected');
    
                        if (removeButton) {
                            removeButton.style.display = 'block';
                        }
    
                        const componentSlug = componentTypeMap[componentType];
                        const imageElement = document.getElementById(`${componentSlug}-image`);
                        if (imageElement && component.image) {
                            imageElement.src = component.image;
                            imageElement.style.display = 'block';
                        }
    
                        // Usamos la función de lógica para guardar el ID
                        createOrUpdateHiddenInput(componentSlug, component.id);
                    }
                }
            }
        })
        .catch(error => {
            console.error(`Error al buscar el componente ${componentType}:`, error);
        });
    }
    
    function processUrlParameters() {
        const params = new URLSearchParams(window.location.search);
        
        for (const [param, type] of Object.entries(paramToTypeMap)) {
            if (params.has(param)) {
                const componentName = decodeURIComponent(params.get(param));
                if (componentName) {
                    preSelectComponent(type, componentName);
                }
            }
        }
    }
    
    // Ejecutar la preselección al cargar
    processUrlParameters();
});
document.addEventListener('DOMContentLoaded', function () {
    // Crear el contenedor de la sidebar
    const sidebar = document.createElement('div');
    sidebar.id = 'components-sidebar';
    sidebar.className = 'components-sidebar';
    
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
        overlay.classList.remove('active');  // Línea corregida
        document.body.classList.remove('sidebar-open');
    });
    
    // Contenedor para el contenido de los componentes
    const sidebarContent = document.createElement('div');
    sidebarContent.className = 'sidebar-content';
    
    // Añadir elementos al DOM
    sidebarHeader.appendChild(sidebarTitle);
    sidebarHeader.appendChild(closeButton);
    sidebar.appendChild(sidebarHeader);
    sidebar.appendChild(sidebarContent);
    document.body.appendChild(sidebar);
    
    overlay.addEventListener('click', function() {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.classList.remove('sidebar-open');
    });
    document.body.appendChild(overlay);
    
    // Agregar event listeners a todos los botones "Seleccionar"
    const selectButtons = document.querySelectorAll('.btn.btn-primary');
    selectButtons.forEach(button => {
        // Excluir el botón de guardar
        if (button.type === 'submit') return;

        button.addEventListener('click', function() {
            // Obtener el tipo de componente desde el título de la card
            const componentType = this.closest('.card-body').querySelector('.card-title').textContent;
            
            // Cargar los componentes según el tipo
            loadComponents(componentType, sidebarContent);
            
            // Mostrar la sidebar y el overlay
            sidebar.classList.add('active');
            overlay.classList.add('active');
            document.body.classList.add('sidebar-open');
        });
    });
    
    // Función para cargar los componentes en la sidebar
    function loadComponents(componentType, container) {
        // Actualizar el título
        sidebarTitle.textContent = `Componentes: ${componentType}`;
        
        // Limpiar el contenido actual
        container.innerHTML = '';
        
        // Mostrar un indicador de carga
        container.innerHTML = '<p class="text-center">Cargando componentes...</p>';
        
        // Aquí harías una petición AJAX para obtener los componentes desde el servidor
        // Por ahora, simularemos la carga con una función
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
        });
    }
    
    // Función para simular la obtención de componentes (reemplazar con AJAX real)
    function fetchComponents(componentType) {
        // Simulamos una petición asíncrona
        return new Promise((resolve) => {
            setTimeout(() => {
                // Datos de ejemplo según el tipo de componente
                let components = [];
                
                switch(componentType) {
                    case 'Procesador':
                        components = [
                            { id: 1, name: 'Intel Core i7-12700K', price: '389.99', image: '/path/to/cpu1.jpg' },
                            { id: 2, name: 'AMD Ryzen 9 5900X', price: '349.99', image: '/path/to/cpu2.jpg' },
                            { id: 3, name: 'Intel Core i5-12600K', price: '289.99', image: '/path/to/cpu3.jpg' }
                        ];
                        break;
                    case 'Tarjeta gráfica':
                        components = [
                            { id: 4, name: 'NVIDIA RTX 3080', price: '699.99', image: '/path/to/gpu1.jpg' },
                            { id: 5, name: 'AMD Radeon RX 6800 XT', price: '649.99', image: '/path/to/gpu2.jpg' },
                            { id: 6, name: 'NVIDIA RTX 3070', price: '499.99', image: '/path/to/gpu3.jpg' }
                        ];
                        break;
                    case 'Placa Base':
                        components = [
                            { id: 7, name: 'ASUS ROG Strix Z690-E', price: '349.99', image: '/path/to/mb1.jpg' },
                            { id: 8, name: 'MSI MPG B550 Gaming Edge', price: '189.99', image: '/path/to/mb2.jpg' },
                            { id: 9, name: 'Gigabyte Z690 Aorus Pro', price: '289.99', image: '/path/to/mb3.jpg' }
                        ];
                        break;
                    // Agregar más casos para otros tipos de componentes
                    default:
                        components = [
                            { id: 10, name: 'Componente de ejemplo 1', price: '99.99', image: '/path/to/default1.jpg' },
                            { id: 11, name: 'Componente de ejemplo 2', price: '129.99', image: '/path/to/default2.jpg' }
                        ];
                }
                
                resolve(components);
            }, 300); // Simular tiempo de carga
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
            
            originalButtons.forEach(cardBody => {
                const title = cardBody.querySelector('.card-title');
                if (title && title.textContent === componentType) {
                    targetButton = cardBody.querySelector('.btn');
                }
            });
            
            if (targetButton) {
                // Actualizar el botón para mostrar el componente seleccionado
                targetButton.textContent = `Seleccionado: ${component.name}`;
                targetButton.classList.add('selected');
                
                // Agregar un input oculto al formulario para guardar el ID del componente
                let hiddenInput = document.querySelector(`input[name="${componentType.toLowerCase().replace(/\s+/g, '_')}"]`);
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = componentType.toLowerCase().replace(/\s+/g, '_');
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
});
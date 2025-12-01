/** Funciones de obtención de IDs de configuración **/
export const getCpuId = () => document.querySelector('input[name="procesador"]')?.value || null;
export const getMotherboardId = () => document.querySelector('input[name="placa_base"]')?.value || null;
export const getGpuId = () => document.querySelector('input[name="tarjeta_grafica"]')?.value || null;

/** Mapeo de tipos de componente a slugs de formulario **/
export const componentTypeMap = {
    'Procesador': 'procesador',
    'Tarjeta gráfica': 'tarjeta_grafica',
    'Placa Base': 'placa_base',
    'Memoria RAM': 'memoria_ram',
    'Almacenamiento': 'almacenamiento',
    'Fuente de Alimentación': 'fuente_de_alimentacion'
};


/** Manipulación del formulario y campos ocultos **/
export function getConfigurationForm() {
    const form = document.getElementById('configuracion-form');
    if (!form) {
        console.error('No se encontró el formulario de configuraciones');
        return null;
    }
    return form;
}

export function createOrUpdateHiddenInput(componentSlug, componentId) {
    const form = getConfigurationForm();
    if (!form) return false;
    
    let hiddenInput = form.querySelector(`input[name="${componentSlug}"]`);
    
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = componentSlug;
        form.appendChild(hiddenInput);
    }

    hiddenInput.value = componentId;
    form.dispatchEvent(new Event('change')); 
    
    return true;
}

/** Obtención de componentes de la API **/
export function fetchComponents(componentType) {
    let endpoint = '';
    let params = new URLSearchParams();
    const mb = getMotherboardId();
    const cpu = getCpuId();
    const gpu = getGpuId();

    // Lógica para determinar el endpoint y parámetros
    switch(componentType) {
        case 'Procesador':
            endpoint = '/api/v1/components/cpus';
            if (mb) params.append('motherboard_id', mb);
            break;
        case 'Tarjeta gráfica':
            endpoint = '/api/v1/components/graphic-cards';
            break;
        case 'Placa Base':
            endpoint = '/api/v1/components/motherboards';
            if (cpu) params.append('cpu_id', cpu);
            break;
        case 'Fuente de Alimentación':
            endpoint = '/api/v1/components/power-supplies';        
            if (cpu) params.append('cpu_id', cpu);
            if (gpu) params.append('gpu_id', gpu);
            break;
        case 'Memoria RAM':
            endpoint = '/api/v1/components/rams';
            break;
        case 'Almacenamiento':
            endpoint = '/api/v1/components/storage-devices';
            break;
    }

    const url = params.toString() ? `${endpoint}?${params.toString()}` : endpoint;

    return fetch(url, {
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
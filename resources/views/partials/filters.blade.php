@php
    $currentSegment = request()->segment(2);
@endphp

@switch($currentSegment)
    @case("procesadores")
        <label for="brand-selector">Marca:</label>
        <select id="brand-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">Intel</option>
            <option value="2">AMD</option>
        </select>

        <label for="socket-selector" class="mb-2">Socket:</label>
        <select id="socket-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">LGA 1700</option>
            <option value="2">FCLGA1851</option>
            <option value="3">AM4</option>
            <option value="4">AM5</option>
        </select>

        <p class="mb-2">Nº núcleos:</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="coreRangeMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="coreRangeMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <p class="mb-2">Frecuencia reloj:</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="clockSpeedMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="clockSpeedMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <p class="mb-2">Precio:</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="priceMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="priceMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <div class="form-check">
            <label class="form-check-label">
                <input type="checkbox" class="form-check-input" name="" id="" value="checkedValue"> Gráficos Integrados
            </label>
        </div>

    @case("tarjetas-graficas")
        <label for="brand-selector">Marca:</label>
        <select id="brand-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">NVIDIA</option>
            <option value="2">AMD</option>
            <option value="3">Intel</option>
        </select>

        <p class="mb-2">Memoria VRAM</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="coreRangeMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="coreRangeMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>
        
        <label for="mem-type-selector">Tipo Memoria:</label>
        <select id="mem-type-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">GDDR6</option>
            <option value="2">GDDR6X</option>
            <option value="3">GDDR7</option>
        </select>
    
        <label for="interface-selector">Interfaz:</label>
        <select id="interface-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">PCIe 3.0</option>
            <option value="2">PCIe 4.0</option>
            <option value="3">PCIe 5.0</option>
        </select>

    @case("placas-base")
        <label for="brand-selector">Marca:</label>
        <select id="brand-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">Asus</option>
            <option value="2">MSI</option>
            <option value="3">Gigabyte</option>
        </select>

        <label for="socket-selector" class="mb-2">Socket:</label>
        <select id="socket-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">LGA 1700</option>
            <option value="2">FCLGA1851</option>
            <option value="3">AM4</option>
            <option value="4">AM5</option>
        </select>

        <label for="form-factor-selector" class="mb-2">Factor de forma:</label>
        <select id="form-factor-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">ATX</option>
            <option value="2">Micro ATX</option>
            <option value="3">Mini ITX</option>
            <option value="4">Extended ATX</option>
        </select>

        <label for="chipset-selector" class="mb-2">Chipset:</label>
        <select id="chipset-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">AMD B550</option>
            <option value="2">Intel Z790</option>
            <option value="3">AMD B650</option>
            <option value="4">Intel B760</option>
        </select>
    
    @case("almacenamiento")
        <label for="brand-selector">Marca:</label>
        <select id="brand-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">Western Digital</option>
            <option value="2">Samsung</option>
            <option value="3">Seagate</option>
            <option value="4">Kingston</option>
            <option value="5">Toshiba</option>
        </select>

        <label for="type-selector">Marca:</label>
        <select id="type-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">HDD</option>
            <option value="2">SSD</option>
        </select>

        <p class="mb-2">Capacidad (TB):</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="capacityMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="capacityMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>
@endswitch
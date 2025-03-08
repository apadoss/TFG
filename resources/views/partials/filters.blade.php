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

        @break
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

        @break
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

        @break
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

        <label for="type-selector">Tipo:</label>
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

        @break
    
    @case("ram")
        <label for="brand-selector">Marca:</label>
        <select id="brand-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">Corsair</option>
            <option value="2">Kingston</option>
            <option value="3">Crucial</option>
        </select>

        <p class="mb-2">Tipo:</p>
        <select name="type" id="type" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">DDR5</option>
            <option value="2">DDR4</option>
            <option value="3">DDR3</option>
        </select>

        <p class="mb-2">Velocidad (GHz):</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="speedMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="speedMax"
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <p class="mb-2">Latencia:</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="latencyMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="latencyMax"
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <p class="mb-2">Capacidad (GB):</p>
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

        @break
    
    @case("fuentes-alimentacion")
        <label for="brand-selector">Marca:</label>
        <select id="brand-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">Corsair</option>
            <option value="2">Seasonic</option>
            <option value="3">MSI</option>
            <option value="4">EVGA</option>
            <option value="5">Asus</option>
            <option value="6">Cooler Master</option>
        </select>

        <p class="mb-2">Potencia (W):</p>
        <div class="d-flex align-items-center gap-2">
            <input
                id="powerMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="powerMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <p class="mb-2">Certificación:</p>
        <select name="certification" id="certification" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">80 Plus Standard</option>
            <option value="2">80 Plus Bronze</option>
            <option value="3">80 Plus Silver</option>
            <option value="4">80 Plus Gold</option>
            <option value="5">80 Plus Platinum</option>
            <option value="6">80 Plus Titanium</option>
        </select>

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

        @break
    
    @case("portatiles")
        <label for="brand-selector">Marca:</label>
        <select id="brand-selector" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">Dell</option>
            <option value="2">HP</option>
            <option value="3">Lenovo</option>
            <option value="4">Acer</option>
            <option value="5">Asus</option>
        </select>

        <p class="mb-2">CPU:</p>
        <select name="cpu" id="cpu" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">Intel Core i3</option>
            <option value="2">Intel Core i5</option>
            <option value="3">Intel Core i7</option>
            <option value="4">Intel Core i9</option>
            <option value="5">AMD Ryzen 5</option>
            <option value="6">AMD Ryzen 7</option>
            <option value="7">AMD Ryzen 9</option>
        </select>

        <p class="mb-2">RAM (GB):</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="ramMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="ramMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <p class="mb-2">Almacenamiento (TB):</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="storageMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="storageMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <p class="mb-2">GPU:</p>
        <select name="gpu" id="gpu" class="form-select">
            <option selected>Seleccione...</option>
            <option value="1">NVIDIA</option>
            <option value="2">AMD</option>
        </select>

        <p class="mb-2">Vida batería (horas):</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="batteryLifeMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín"                
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="batteryLifeMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>

        <p class="mb-2">Peso (kg):</p>
        <div class="d-flex align-items-center gap-2">
            <input 
                id="weightMin" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Mín" 
                min="0">
            <span class="text-muted">-</span>
            <input 
                id="weightMax" 
                type="number" 
                class="form-control form-control-sm" 
                placeholder="Máx" 
                min="0">
        </div>
        @break
@endswitch
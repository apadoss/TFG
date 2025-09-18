<label for="brand-selector">Marca:</label>
<select id="brand-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="NVIDIA">NVIDIA</option>
    <option value="AMD">AMD</option>
    <option value="Int">Intel</option>
</select>

<p class="mb-2">Memoria VRAM</p>
<div class="d-flex align-items-center gap-2">
    <input 
        id="vramRangeMin" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Mín" 
        min="0">
    <span class="text-muted">-</span>
    <input 
        id="vramRangeMax" 
        type="number" 
        class="form-control form-control-sm" 
        placeholder="Máx" 
        min="0">
</div>
        
<label for="mem-type-selector">Tipo Memoria:</label>
<select id="mem-type-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="GDDR6">GDDR6</option>
    <option value="GDDR6X">GDDR6X</option>
    <option value="GDDR7">GDDR7</option>
</select>
    
<label for="interface-selector">Interfaz:</label>
<select id="interface-selector" class="form-select">
    <option selected>Seleccione...</option>
    <option value="PCIe 3.0">PCIe 3.0</option>
    <option value="PCIe 4.0">PCIe 4.0</option>
    <option value="PCIe 5.0">PCIe 5.0</option>
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

<script src="{{ asset('js/filters/gpu-filter.js') }}"></script>
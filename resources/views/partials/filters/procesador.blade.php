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
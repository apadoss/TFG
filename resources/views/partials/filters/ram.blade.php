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